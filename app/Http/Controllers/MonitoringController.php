<?php

namespace App\Http\Controllers;

use App\Models\KecamatanProgress;
use App\Models\MonitoringImport;
use App\Models\OfficerMapping;
use App\Models\PclDailySubmit;
use App\Models\PclPml;
use App\Models\PclProgress;
use App\Models\PclTotalAssignment;
use App\Models\PmlDailySubmit;
use App\Models\PmlProgress;
use App\Models\PmlTotalAssignment;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Response;

class MonitoringController extends Controller
{
    public function index()
    {
        // Get latest import_id for filtering
        $latestPmlImportId = PmlProgress::max('import_id');
        $latestPclImportId = PclProgress::max('import_id');
        $latestKecamatanImportId = KecamatanProgress::max('import_id');

        // Get latest data from database for kecamatan progress
        // Filter by latest import_id to avoid double counting from cumulative data
        $kecamatanProgress = KecamatanProgress::where('import_id', $latestKecamatanImportId)->get();

        // Format progress data for each kecamatan
        $progressData = [];
        foreach ($kecamatanProgress as $data) {
            $submitAndApprove = ($data->submit ?? 0) + ($data->approve ?? 0);
            $progress = $data->total_assignment > 0
                ? round(($submitAndApprove / $data->total_assignment) * 100, 1)
                : 0;
            $progressData[$data->kecamatan] = [
                'target' => $data->total_assignment,
                'completed' => $submitAndApprove,
                'progress' => $progress,
            ];
        }

        // Calculate totals
        $totalTarget = $kecamatanProgress->sum('total_assignment');
        $totalCompleted = $kecamatanProgress->sum('submit') + $kecamatanProgress->sum('approve');
        $totalProcessed = $kecamatanProgress->sum('submit') + $kecamatanProgress->sum('approve') + $kecamatanProgress->sum('reject');
        $overallProgress = $totalTarget > 0 ? round(($totalCompleted / $totalTarget) * 100, 1) : 0;
        $processingProgress = $totalTarget > 0 ? round(($totalProcessed / $totalTarget) * 100, 1) : 0;

        // Calculate status distribution for donut chart
        $statusDistribution = [
            'open' => $kecamatanProgress->sum('open'),
            'draft' => $kecamatanProgress->sum('draft'),
            'submit' => $kecamatanProgress->sum('submit'),
            'approve' => $kecamatanProgress->sum('approve'),
            'reject' => $kecamatanProgress->sum('reject'),
        ];
        $totalStatus = array_sum($statusDistribution);

        // PML (Petugas Pemeriksa Lapangan) progress data from database
        // Filter by latest import_id to avoid double counting from cumulative data
        $pmlProgressData = PmlProgress::where('import_id', $latestPmlImportId)
            ->selectRaw('
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
        // Filter by latest import_id to avoid double counting from cumulative data
        $pclProgressData = PclProgress::where('import_id', $latestPclImportId)
            ->selectRaw('
                email,
                SUM(open) as open,
                SUM(submit) as submit,
                SUM(reject) as reject,
                SUM(approve) as approve
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
            $submitAndApproveRatio = $totalAssignment > 0
                ? round(($pcl->submit + $pcl->approve) / $totalAssignment * 100, 1)
                : 0;

            $pclData[] = [
                'name' => $pclName,
                'email' => $pcl->email,
                'open' => $pcl->open,
                'submit' => $pcl->submit,
                'reject' => $pcl->reject,
                'approve' => $pcl->approve,
                'target' => $totalAssignment,
                'pml' => $pmlName,
                'submit_and_approve_ratio' => $submitAndApproveRatio,
            ];
        }

        // Calculate PCL totals
        $pclTotals = [
            'open' => $pclProgressData->sum('open'),
            'submit' => $pclProgressData->sum('submit'),
            'reject' => $pclProgressData->sum('reject'),
            'approve' => $pclProgressData->sum('approve'),
        ];

        // Paginate PCL data (10 per page) using Laravel's LengthAwarePaginator
        $pclDataPaginated = new LengthAwarePaginator(
            array_slice($pclData, 0, 10),
            count($pclData),
            10,
            1,
            ['path' => route('monitoring')]
        );

        // Get latest leaderboard data from pcl_daily_submits
        // Use created_at from pcl_daily_submits for last update timestamp
        $latestDataDate = PclDailySubmit::orderByDesc('data_date')->value('data_date');
        $latestPclRecord = PclDailySubmit::where('data_date', $latestDataDate)
            ->orderBy('created_at', 'desc')
            ->first();
        $leaderboardLastUpdate = $latestPclRecord?->created_at;

        if ($latestDataDate) {
            $leaderboardQuery = PclDailySubmit::where('data_date', $latestDataDate)
                ->orderByDesc('daily_submit')
                ->orderBy('name');

            $leaderboardData = $leaderboardQuery->get()->map(function ($item) {
                return [
                    'name' => $item->name ?? $item->email,
                    'email' => $item->email,
                    'daily_submit' => $item->daily_submit,
                    'total_submit' => $item->total_submit,
                    'target_met' => $item->target_met,
                    'kecamatan_string' => $item->kecamatan_string,
                ];
            });

            // Paginate leaderboard data (5 per page)
            $leaderboardPerPage = 5;
            $leaderboardPage = (int) request()->get('leaderboard_page', 1);
            $leaderboardOffset = ($leaderboardPage - 1) * $leaderboardPerPage;
            $leaderboardSliced = $leaderboardData->slice($leaderboardOffset, $leaderboardPerPage)->values();

            $leaderboardDataPaginated = new LengthAwarePaginator(
                $leaderboardSliced,
                $leaderboardData->count(),
                $leaderboardPerPage,
                $leaderboardPage,
                ['path' => route('monitoring', ['tab' => 'leaderboard'])]
            );
        } else {
            $leaderboardData = collect();
            $leaderboardDataPaginated = new LengthAwarePaginator([], 0, 5, 1, ['path' => route('monitoring')]);
        }

        // Get latest PML leaderboard data from pml_daily_submits
        // Use created_at from pml_daily_submits for last update timestamp
        $latestPmlDataDate = PmlDailySubmit::orderByDesc('data_date')->value('data_date');
        $latestPmlRecord = PmlDailySubmit::where('data_date', $latestPmlDataDate)
            ->orderBy('created_at', 'desc')
            ->first();
        $pmlLeaderboardLastUpdate = $latestPmlRecord?->created_at;

        if ($latestPmlDataDate) {
            $pmlLeaderboardQuery = PmlDailySubmit::where('data_date', $latestPmlDataDate)
                ->orderByDesc('daily_combined')
                ->orderBy('name');

            $pmlLeaderboardData = $pmlLeaderboardQuery->get()->map(function ($item) {
                return [
                    'name' => $item->name ?? $item->email,
                    'email' => $item->email,
                    'daily_reject' => $item->daily_reject,
                    'daily_approve' => $item->daily_approve,
                    'daily_combined' => $item->daily_combined,
                    'pcl_count' => $item->pcl_count,
                    'target_met' => $item->target_met,
                    'target_threshold' => 5 * $item->pcl_count,
                ];
            });

            // Paginate PML leaderboard data (5 per page)
            $pmlLeaderboardPerPage = 5;
            $pmlLeaderboardPage = (int) request()->get('pml_leaderboard_page', 1);
            $pmlLeaderboardOffset = ($pmlLeaderboardPage - 1) * $pmlLeaderboardPerPage;
            $pmlLeaderboardSliced = $pmlLeaderboardData->slice($pmlLeaderboardOffset, $pmlLeaderboardPerPage)->values();

            $pmlLeaderboardDataPaginated = new LengthAwarePaginator(
                $pmlLeaderboardSliced,
                $pmlLeaderboardData->count(),
                $pmlLeaderboardPerPage,
                $pmlLeaderboardPage,
                ['path' => route('monitoring', ['tab' => 'pml_leaderboard'])]
            );
        } else {
            $pmlLeaderboardData = collect();
            $pmlLeaderboardDataPaginated = new LengthAwarePaginator([], 0, 5, 1, ['path' => route('monitoring')]);
        }

        // Get last update timestamp from completed imports for PML
        $pmlLastUpdate = MonitoringImport::where('type', 'data_pml')
            ->where('status', 'completed')
            ->orderBy('imported_at', 'desc')
            ->first();

        // Get last update timestamp from completed imports for PCL
        $pclLastUpdate = MonitoringImport::where('type', 'data_pcl')
            ->where('status', 'completed')
            ->orderBy('imported_at', 'desc')
            ->first();

        return view('monitoring', compact(
            'progressData',
            'totalTarget',
            'totalCompleted',
            'totalProcessed',
            'totalStatus',
            'overallProgress',
            'processingProgress',
            'statusDistribution',
            'pmlData',
            'pmlTotals',
            'pclData',
            'pclTotals',
            'pclDataPaginated',
            'pmlList',
            'pmlLastUpdate',
            'pclLastUpdate',
            'leaderboardData',
            'leaderboardDataPaginated',
            'leaderboardLastUpdate',
            'pmlLeaderboardData',
            'pmlLeaderboardDataPaginated',
            'pmlLeaderboardLastUpdate',
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
        // Filter by latest import_id to avoid double counting from cumulative data
        $latestPclImportId = PclProgress::max('import_id');
        $pclProgressData = PclProgress::where('import_id', $latestPclImportId)
            ->selectRaw('
                email,
                SUM(open) as open,
                SUM(submit) as submit,
                SUM(reject) as reject,
                SUM(approve) as approve
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
            $submitAndApproveRatio = $totalAssignment > 0
                ? round(($pcl->submit + $pcl->approve) / $totalAssignment * 100, 1)
                : 0;

            $pclData[] = [
                'name' => $pclName,
                'email' => $pcl->email,
                'open' => $pcl->open,
                'submit' => $pcl->submit,
                'reject' => $pcl->reject,
                'approve' => $pcl->approve,
                'target' => $totalAssignment,
                'pml' => $pmlName,
                'submit_and_approve_ratio' => $submitAndApproveRatio,
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
        if ($sortBy && in_array($sortBy, ['submit_and_approve_ratio'])) {
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

    public function getLeaderboardPage(Request $request)
    {
        $page = (int) $request->get('page', 1);
        $statusFilter = $request->get('status', '');
        $perPage = 5;

        // Get latest leaderboard data
        $latestDataDate = PclDailySubmit::orderByDesc('data_date')->value('data_date');

        $leaderboardData = collect();

        if ($latestDataDate) {
            $leaderboardQuery = PclDailySubmit::where('data_date', $latestDataDate)
                ->orderByDesc('daily_submit')
                ->orderBy('name');

            $allData = $leaderboardQuery->get()->map(function ($item) {
                return [
                    'name' => $item->name ?? $item->email,
                    'email' => $item->email,
                    'daily_submit' => $item->daily_submit,
                    'total_submit' => $item->total_submit,
                    'target_met' => $item->target_met,
                    'kecamatan_string' => $item->kecamatan_string,
                ];
            });

            // Apply status filter
            if ($statusFilter === 'met') {
                $allData = $allData->filter(function ($item) {
                    return $item['target_met'] === true;
                })->values();
            } elseif ($statusFilter === 'not_met') {
                $allData = $allData->filter(function ($item) {
                    return $item['target_met'] === false;
                })->values();
            }

            $offset = ($page - 1) * $perPage;
            $slicedData = $allData->slice($offset, $perPage)->values();

            $leaderboardPaginated = new LengthAwarePaginator(
                $slicedData,
                $allData->count(),
                $perPage,
                $page,
                ['path' => route('leaderboard.pcl.page')]
            );
        } else {
            $leaderboardPaginated = new LengthAwarePaginator([], 0, $perPage, $page, ['path' => route('leaderboard.pcl.page')]);
        }

        return response()->json([
            'html' => view('components.partials.leaderboard-table-rows', [
                'leaderboardPaginated' => $leaderboardPaginated,
                'allDataCount' => $leaderboardPaginated->total(),
            ])->render(),
            'pagination' => view('components.partials.leaderboard-pagination', [
                'leaderboardPaginated' => $leaderboardPaginated,
            ])->render(),
            'total' => $leaderboardPaginated->total(),
        ]);
    }

    public function getPmlLeaderboardPage(Request $request)
    {
        $page = (int) $request->get('page', 1);
        $statusFilter = $request->get('status', '');
        $perPage = 5;

        // Get latest PML leaderboard data
        $latestDataDate = PmlDailySubmit::orderByDesc('data_date')->value('data_date');

        $pmlLeaderboardData = collect();

        if ($latestDataDate) {
            $pmlLeaderboardQuery = PmlDailySubmit::where('data_date', $latestDataDate)
                ->orderByDesc('daily_combined')
                ->orderBy('name');

            $allData = $pmlLeaderboardQuery->get()->map(function ($item) {
                return [
                    'name' => $item->name ?? $item->email,
                    'email' => $item->email,
                    'daily_reject' => $item->daily_reject,
                    'daily_approve' => $item->daily_approve,
                    'daily_combined' => $item->daily_combined,
                    'pcl_count' => $item->pcl_count,
                    'target_met' => $item->target_met,
                    'target_threshold' => 5 * $item->pcl_count,
                ];
            });

            // Apply status filter
            if ($statusFilter === 'met') {
                $allData = $allData->filter(function ($item) {
                    return $item['target_met'] === true;
                })->values();
            } elseif ($statusFilter === 'not_met') {
                $allData = $allData->filter(function ($item) {
                    return $item['target_met'] === false;
                })->values();
            }

            $offset = ($page - 1) * $perPage;
            $slicedData = $allData->slice($offset, $perPage)->values();

            $pmlLeaderboardPaginated = new LengthAwarePaginator(
                $slicedData,
                $allData->count(),
                $perPage,
                $page,
                ['path' => route('pml.leaderboard.page')]
            );
        } else {
            $pmlLeaderboardPaginated = new LengthAwarePaginator([], 0, $perPage, $page, ['path' => route('pml.leaderboard.page')]);
        }

        return response()->json([
            'html' => view('components.partials.pml-leaderboard-table-rows', [
                'pmlLeaderboardPaginated' => $pmlLeaderboardPaginated,
            ])->render(),
            'pagination' => view('components.partials.pml-leaderboard-pagination', [
                'pmlLeaderboardPaginated' => $pmlLeaderboardPaginated,
            ])->render(),
            'total' => $pmlLeaderboardPaginated->total(),
        ]);
    }

    public function exportPclExcel()
    {
        // Get PCL progress data from database
        $latestPclImportId = PclProgress::max('import_id');
        $pclProgressData = PclProgress::where('import_id', $latestPclImportId)
            ->selectRaw('
                email,
                SUM(open) as open,
                SUM(submit) as submit,
                SUM(reject) as reject,
                SUM(approve) as approve
            ')->groupBy('email')->get();

        // Get PCL total assignments
        $pclTotalAssignments = PclTotalAssignment::pluck('total_assignment', 'email');

        // Get PCL to PML mapping
        $pclPmlMappings = PclPml::with('pml')->get()->keyBy('pcl_email');

        // Format PCL data
        $pclData = [];
        $no = 1;
        foreach ($pclProgressData as $pcl) {
            $officer = OfficerMapping::where('email', $pcl->email)->first();
            $pclName = $officer->name ?? $pcl->email;

            // Get PML name from mapping
            $pmlMapping = $pclPmlMappings->get($pcl->email);
            $pmlName = $pmlMapping && $pmlMapping->pml ? $pmlMapping->pml->name : '-';

            $totalAssignment = $pclTotalAssignments[$pcl->email] ?? 0;
            $submitAndApproveRatio = $totalAssignment > 0
                ? round(($pcl->submit + $pcl->approve) / $totalAssignment * 100, 1)
                : 0;

            $pclData[] = [
                'no' => $no,
                'nama' => $pclName,
                'email' => $pcl->email,
                'open' => $pcl->open,
                'submit' => $pcl->submit,
                'reject' => $pcl->reject,
                'approve' => $pcl->approve,
                'target' => $totalAssignment,
                'pml' => $pmlName,
                'submit_and_approve_ratio' => $submitAndApproveRatio,
            ];
            $no++;
        }

        // Create CSV content
        $csvContent = "No,Nama PCL,Email,Open,Submit,Reject,Approve,Target,PML,Progress (%)\n";
        foreach ($pclData as $row) {
            $csvContent .= sprintf(
                "%d,%s,%s,%d,%d,%d,%d,%d,%s,%.1f\n",
                $row['no'],
                '"'.str_replace('"', '""', $row['nama']).'"',
                $row['email'],
                $row['open'],
                $row['submit'],
                $row['reject'],
                $row['approve'],
                $row['target'],
                '"'.str_replace('"', '""', $row['pml']).'"',
                $row['submit_and_approve_ratio']
            );
        }

        $filename = 'pcl_progress_'.date('Y-m-d_His').'.csv';

        return Response::make($csvContent, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ]);
    }

    public function exportPmlExcel()
    {
        // Get PML progress data from database
        $latestPmlImportId = PmlProgress::max('import_id');
        $pmlProgressData = PmlProgress::where('import_id', $latestPmlImportId)
            ->selectRaw('
                email,
                SUM(submit) as submit,
                SUM(reject) as reject,
                SUM(approve) as approved
            ')->groupBy('email')->get();

        // Get PML total assignments
        $pmlTotalAssignments = PmlTotalAssignment::pluck('total_assignment', 'email');

        // Format PML data
        $pmlData = [];
        $no = 1;
        foreach ($pmlProgressData as $pml) {
            $officer = OfficerMapping::where('email', $pml->email)->first();
            $pmlName = $officer->name ?? $pml->email;

            $totalAssignment = $pmlTotalAssignments[$pml->email] ?? 0;
            $progressRatio = $totalAssignment > 0
                ? round(($pml->approved / $totalAssignment) * 100, 1)
                : 0;

            $pmlData[] = [
                'no' => $no,
                'nama' => $pmlName,
                'email' => $pml->email,
                'submit' => $pml->submit,
                'reject' => $pml->reject,
                'approved' => $pml->approved,
                'target' => $totalAssignment,
                'progress' => $progressRatio,
            ];
            $no++;
        }

        // Create CSV content
        $csvContent = "No,Nama PML,Email,Submit,Reject,Approved,Target,Progress (%)\n";
        foreach ($pmlData as $row) {
            $csvContent .= sprintf(
                "%d,%s,%s,%d,%d,%d,%d,%.1f\n",
                $row['no'],
                '"'.str_replace('"', '""', $row['nama']).'"',
                $row['email'],
                $row['submit'],
                $row['reject'],
                $row['approved'],
                $row['target'],
                $row['progress']
            );
        }

        $filename = 'pml_progress_'.date('Y-m-d_His').'.csv';

        return Response::make($csvContent, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="'.$filename.'"',
        ]);
    }
}
