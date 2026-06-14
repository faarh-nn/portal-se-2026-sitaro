<?php

namespace App\Http\Controllers;

use App\Models\KecamatanProgress;
use App\Models\OfficerMapping;
use App\Models\PclPml;
use App\Models\PclProgress;
use App\Models\PclTotalAssignment;
use App\Models\PmlProgress;
use App\Models\PmlTotalAssignment;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class MonitoringController extends Controller
{
    public function index()
    {
        // Get latest data from database for kecamatan progress
        $kecamatanProgress = KecamatanProgress::selectRaw('
            kecamatan,
            SUM(total_assignment) as total_assignment,
            SUM(submit) as submit
        ')->groupBy('kecamatan')->get();

        // Format progress data for each kecamatan
        $progressData = [];
        foreach ($kecamatanProgress as $data) {
            $progress = $data->total_assignment > 0
                ? round(($data->submit / $data->total_assignment) * 100, 1)
                : 0;
            $progressData[$data->kecamatan] = [
                'target' => $data->total_assignment,
                'completed' => $data->submit,
                'progress' => $progress,
            ];
        }

        // Calculate totals
        $totalTarget = $kecamatanProgress->sum('total_assignment');
        $totalCompleted = $kecamatanProgress->sum('submit');
        $overallProgress = $totalTarget > 0 ? round(($totalCompleted / $totalTarget) * 100, 1) : 0;

        // PML (Petugas Pemeriksa Lapangan) progress data from database
        $pmlProgressData = PmlProgress::selectRaw('
            email,
            SUM(submit) as submit,
            SUM(reject) as reject,
            SUM(approve) as approved
        ')->groupBy('email')->get();

        // Get PML total assignments
        $pmlTotalAssignments = PmlTotalAssignment::pluck('total_assignment', 'email');

        // Format PML data
        $pmlData = [];
        foreach ($pmlProgressData as $pml) {
            $officer = OfficerMapping::where('email', $pml->email)->first();
            $pmlName = $officer->name ?? $pml->email;

            $totalAssignment = $pmlTotalAssignments[$pml->email] ?? 0;
            $progress = $totalAssignment > 0
                ? round(($pml->approved / $totalAssignment) * 100, 1)
                : 0;

            $pmlData[] = [
                'name' => $pmlName,
                'email' => $pml->email,
                'submit' => $pml->submit,
                'reject' => $pml->reject,
                'approved' => $pml->approved,
                'target' => $totalAssignment,
                'progress' => $progress,
            ];
        }

        // Calculate PML totals
        $pmlTotals = [
            'submit' => $pmlProgressData->sum('submit'),
            'reject' => $pmlProgressData->sum('reject'),
            'approved' => $pmlProgressData->sum('approved'),
        ];

        // PCL (Petugas Pencacah Lapangan) progress data from database
        // Group by email to get aggregated progress for each PCL
        $pclProgressData = PclProgress::selectRaw('
            email,
            SUM(open) as open,
            SUM(submit) as submit,
            SUM(reject) as reject,
            SUM(completed) as completed
        ')->groupBy('email')->get();

        // Get PCL total assignments
        $pclTotalAssignments = PclTotalAssignment::pluck('total_assignment', 'email');

        // Get PCL to PML mapping
        $pclPmlMappings = PclPml::with('pml')->get()->keyBy('pcl_email');

        // Get all PML names for filter dropdown
        $pmlList = OfficerMapping::where('type', 'PML')->pluck('name')->toArray();
        sort($pmlList);

        // Format PCL data
        $pclData = [];
        foreach ($pclProgressData as $pcl) {
            $officer = OfficerMapping::where('email', $pcl->email)->first();
            $pclName = $officer->name ?? $pcl->email;

            // Get PML name from mapping
            $pmlMapping = $pclPmlMappings->get($pcl->email);
            $pmlName = $pmlMapping && $pmlMapping->pml ? $pmlMapping->pml->name : '-';

            $totalAssignment = $pclTotalAssignments[$pcl->email] ?? 0;
            $submitRatio = $totalAssignment > 0
                ? round(($pcl->submit / $totalAssignment) * 100, 1)
                : 0;

            $pclData[] = [
                'name' => $pclName,
                'email' => $pcl->email,
                'open' => $pcl->open,
                'submit' => $pcl->submit,
                'reject' => $pcl->reject,
                'completed' => $pcl->completed,
                'target' => $totalAssignment,
                'pml' => $pmlName,
                'submit_ratio' => $submitRatio,
            ];
        }

        // Calculate PCL totals
        $pclTotals = [
            'open' => $pclProgressData->sum('open'),
            'submit' => $pclProgressData->sum('submit'),
            'reject' => $pclProgressData->sum('reject'),
            'completed' => $pclProgressData->sum('completed'),
        ];

        // Paginate PCL data (10 per page) using Laravel's LengthAwarePaginator
        $pclDataPaginated = new LengthAwarePaginator(
            array_slice($pclData, 0, 10),
            count($pclData),
            10,
            1,
            ['path' => route('monitoring')]
        );

        return view('monitoring', compact(
            'progressData',
            'totalTarget',
            'totalCompleted',
            'overallProgress',
            'pmlData',
            'pmlTotals',
            'pclData',
            'pclTotals',
            'pclDataPaginated',
            'pmlList'
        ));
    }

    public function getPclTablePage(Request $request)
    {
        $page = $request->get('page', 1);
        $search = $request->get('search', '');
        $pmlFilter = $request->get('pml', '');
        $sortBy = $request->get('sortBy', '');
        $sortDirection = $request->get('sortDirection', 'asc');

        // PCL progress data from database
        $pclProgressData = PclProgress::selectRaw('
            email,
            SUM(open) as open,
            SUM(submit) as submit,
            SUM(reject) as reject,
            SUM(completed) as completed
        ')->groupBy('email')->get();

        // Get PCL total assignments
        $pclTotalAssignments = PclTotalAssignment::pluck('total_assignment', 'email');

        // Get PCL to PML mapping
        $pclPmlMappings = PclPml::with('pml')->get()->keyBy('pcl_email');

        // Format PCL data
        $pclData = [];
        foreach ($pclProgressData as $pcl) {
            $officer = OfficerMapping::where('email', $pcl->email)->first();
            $pclName = $officer->name ?? $pcl->email;

            // Get PML name from mapping
            $pmlMapping = $pclPmlMappings->get($pcl->email);
            $pmlName = $pmlMapping && $pmlMapping->pml ? $pmlMapping->pml->name : '-';

            $totalAssignment = $pclTotalAssignments[$pcl->email] ?? 0;
            $submitRatio = $totalAssignment > 0
                ? round(($pcl->submit / $totalAssignment) * 100, 1)
                : 0;

            $pclData[] = [
                'name' => $pclName,
                'email' => $pcl->email,
                'open' => $pcl->open,
                'submit' => $pcl->submit,
                'reject' => $pcl->reject,
                'completed' => $pcl->completed,
                'target' => $totalAssignment,
                'pml' => $pmlName,
                'submit_ratio' => $submitRatio,
            ];
        }

        // Filter by search if provided
        if (! empty($search)) {
            $searchLower = strtolower($search);
            $pclData = array_filter($pclData, function ($pcl) use ($searchLower) {
                return strpos(strtolower($pcl['name']), $searchLower) !== false;
            });
            $pclData = array_values($pclData);
        }

        // Filter by PML if provided
        if (! empty($pmlFilter)) {
            $pclData = array_filter($pclData, function ($pcl) use ($pmlFilter) {
                return $pcl['pml'] === $pmlFilter;
            });
            $pclData = array_values($pclData);
        }

        // Sort by column if provided
        if ($sortBy && in_array($sortBy, ['submit_ratio'])) {
            usort($pclData, function ($a, $b) use ($sortBy, $sortDirection) {
                if ($sortDirection === 'asc') {
                    return $a[$sortBy] <=> $b[$sortBy];
                } else {
                    return $b[$sortBy] <=> $a[$sortBy];
                }
            });
        }

        $totalCount = count($pclData);
        $perPage = 10;
        $offset = ($page - 1) * $perPage;
        $slicedData = array_slice($pclData, $offset, $perPage);

        $pclPaginated = new LengthAwarePaginator(
            $slicedData,
            $totalCount,
            $perPage,
            $page,
            ['path' => route('pcl.table.page')]
        );

        return response()->json([
            'html' => view('components.partials.pcl-table-rows', [
                'pclPaginated' => $pclPaginated,
            ])->render(),
            'pagination' => view('components.partials.pcl-table-pagination', [
                'pclPaginated' => $pclPaginated,
            ])->render(),
            'total' => $totalCount,
        ]);
    }
}
