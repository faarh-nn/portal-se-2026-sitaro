<?php

namespace App\Http\Controllers;

use App\Models\KecamatanProgress;
use App\Models\OfficerMapping;
use App\Models\PclPml;
use App\Models\PclProgress;
use App\Models\PclTotalAssignment;
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

        // PML (Petugas Pemeriksa Lapangan) progress data
        $pmlData = [
            ['name' => 'Al Fitri', 'kecamatan' => 'Biaro', 'open' => 45, 'submit' => 32, 'reject' => 5, 'completed' => 8, 'target' => 50, 'approved' => 27],
            ['name' => 'Dade Chee', 'kecamatan' => 'Tagulandang', 'open' => 78, 'submit' => 65, 'reject' => 3, 'completed' => 10, 'target' => 90, 'approved' => 20],
            ['name' => 'Deswita', 'kecamatan' => 'Siau Timur', 'open' => 92, 'submit' => 88, 'reject' => 2, 'completed' => 2, 'target' => 100, 'approved' => 86],
            ['name' => 'Gracia Undap', 'kecamatan' => 'Siau Barat', 'open' => 56, 'submit' => 41, 'reject' => 8, 'completed' => 7, 'target' => 60, 'approved' => 60],
            ['name' => 'Hermita Kakalang', 'kecamatan' => 'Tagulandang Utara', 'open' => 34, 'submit' => 28, 'reject' => 4, 'completed' => 2, 'target' => 40, 'approved' => 10],
            ['name' => 'Linsa', 'kecamatan' => 'Siau Tengah', 'open' => 67, 'submit' => 55, 'reject' => 6, 'completed' => 6, 'target' => 75, 'approved' => 49],
            ['name' => 'Mama Aim', 'kecamatan' => 'Tagulandang Selatan', 'open' => 89, 'submit' => 72, 'reject' => 5, 'completed' => 12, 'target' => 100, 'approved' => 67],
            ['name' => 'Mauren Devina Lombone', 'kecamatan' => 'Siau Barat Selatan', 'open' => 43, 'submit' => 38, 'reject' => 2, 'completed' => 3, 'target' => 50, 'approved' => 36],
            ['name' => 'Papa Opo', 'kecamatan' => 'Siau Timur Selatan', 'open' => 95, 'submit' => 89, 'reject' => 1, 'completed' => 5, 'target' => 100, 'approved' => 88],
            ['name' => 'Pareda', 'kecamatan' => 'Siau Barat Utara', 'open' => 51, 'submit' => 44, 'reject' => 3, 'completed' => 4, 'target' => 60, 'approved' => 60],
            ['name' => 'Trisna Jacob', 'kecamatan' => 'Biaro', 'open' => 62, 'submit' => 53, 'reject' => 4, 'completed' => 5, 'target' => 70, 'approved' => 49],
            ['name' => 'Ungke', 'kecamatan' => 'Tagulandang', 'open' => 71, 'submit' => 64, 'reject' => 3, 'completed' => 4, 'target' => 85, 'approved' => 61],
        ];

        // Calculate PML progress percentage for approved column
        foreach ($pmlData as &$pml) {
            $pml['progress'] = $pml['target'] > 0 ? round(($pml['approved'] / $pml['target']) * 100, 1) : 0;
        }

        // Calculate totals
        $pmlTotals = [
            'open' => array_sum(array_column($pmlData, 'open')),
            'submit' => array_sum(array_column($pmlData, 'submit')),
            'reject' => array_sum(array_column($pmlData, 'reject')),
            'completed' => array_sum(array_column($pmlData, 'completed')),
            'approved' => array_sum(array_column($pmlData, 'approved')),
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
