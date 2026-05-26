<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class MonitoringController extends Controller
{
    public function index()
    {
        // Progress data per kecamatan (target based on lapangan preferences)
        $progressData = [
            'Biaro' => ['target' => 220, 'completed' => 128],
            'Tagulandang Selatan' => ['target' => 375, 'completed' => 289],
            'Tagulandang Utara' => ['target' => 339, 'completed' => 117],
            'Tagulandang' => ['target' => 1553, 'completed' => 108],
            'Siau Barat Selatan' => ['target' => 489, 'completed' => 88],
            'Siau Timur Selatan' => ['target' => 736, 'completed' => 689],
            'Siau Barat' => ['target' => 1395, 'completed' => 55],
            'Siau Tengah' => ['target' => 233, 'completed' => 233],
            'Siau Timur' => ['target' => 2402, 'completed' => 154],
            'Siau Barat Utara' => ['target' => 420, 'completed' => 195],
        ];

        // Calculate progress percentage for each kecamatan
        foreach ($progressData as $kecamatan => &$data) {
            $data['progress'] = $data['target'] > 0 ? round(($data['completed'] / $data['target']) * 100, 1) : 0;
        }

        $totalTarget = array_sum(array_column($progressData, 'target'));
        $totalCompleted = array_sum(array_column($progressData, 'completed'));
        $overallProgress = $totalTarget > 0 ? round(($totalCompleted / $totalTarget) * 100, 1) : 0;

        // PML (Petugas Pemeriksa Lapangan) progress data
        $pmlData = [
            ['name' => 'Al Fitri', 'kecamatan' => 'Biaro', 'open' => 45, 'submit' => 32, 'reject' => 5, 'pending' => 8, 'target' => 50, 'approved' => 27],
            ['name' => 'Dade Chee', 'kecamatan' => 'Tagulandang', 'open' => 78, 'submit' => 65, 'reject' => 3, 'pending' => 10, 'target' => 90, 'approved' => 20],
            ['name' => 'Deswita', 'kecamatan' => 'Siau Timur', 'open' => 92, 'submit' => 88, 'reject' => 2, 'pending' => 2, 'target' => 100, 'approved' => 86],
            ['name' => 'Gracia Undap', 'kecamatan' => 'Siau Barat', 'open' => 56, 'submit' => 41, 'reject' => 8, 'pending' => 7, 'target' => 60, 'approved' => 60],
            ['name' => 'Hermita Kakalang', 'kecamatan' => 'Tagulandang Utara', 'open' => 34, 'submit' => 28, 'reject' => 4, 'pending' => 2, 'target' => 40, 'approved' => 10],
            ['name' => 'Linsa', 'kecamatan' => 'Siau Tengah', 'open' => 67, 'submit' => 55, 'reject' => 6, 'pending' => 6, 'target' => 75, 'approved' => 49],
            ['name' => 'Mama Aim', 'kecamatan' => 'Tagulandang Selatan', 'open' => 89, 'submit' => 72, 'reject' => 5, 'pending' => 12, 'target' => 100, 'approved' => 67],
            ['name' => 'Mauren Devina Lombone', 'kecamatan' => 'Siau Barat Selatan', 'open' => 43, 'submit' => 38, 'reject' => 2, 'pending' => 3, 'target' => 50, 'approved' => 36],
            ['name' => 'Papa Opo', 'kecamatan' => 'Siau Timur Selatan', 'open' => 95, 'submit' => 89, 'reject' => 1, 'pending' => 5, 'target' => 100, 'approved' => 88],
            ['name' => 'Pareda', 'kecamatan' => 'Siau Barat Utara', 'open' => 51, 'submit' => 44, 'reject' => 3, 'pending' => 4, 'target' => 60, 'approved' => 60],
            ['name' => 'Trisna Jacob', 'kecamatan' => 'Biaro', 'open' => 62, 'submit' => 53, 'reject' => 4, 'pending' => 5, 'target' => 70, 'approved' => 49],
            ['name' => 'Ungke', 'kecamatan' => 'Tagulandang', 'open' => 71, 'submit' => 64, 'reject' => 3, 'pending' => 4, 'target' => 85, 'approved' => 61],
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
            'pending' => array_sum(array_column($pmlData, 'pending')),
            'approved' => array_sum(array_column($pmlData, 'approved')),
        ];

        // PCL (Petugas Pencacah Lapangan) progress data - 63 officers
        $pclData = [
            ['name' => 'Abdullah', 'open' => 35, 'submit' => 28, 'reject' => 3, 'pending' => 4, 'target' => 45, 'approved' => 40],
            ['name' => 'Aisyah', 'open' => 42, 'submit' => 35, 'reject' => 2, 'pending' => 5, 'target' => 50, 'approved' => 30],
            ['name' => 'Andi', 'open' => 38, 'submit' => 30, 'reject' => 4, 'pending' => 4, 'target' => 48, 'approved' => 26],
            ['name' => 'Budi', 'open' => 50, 'submit' => 42, 'reject' => 3, 'pending' => 5, 'target' => 55, 'approved' => 55],
            ['name' => 'Dewi', 'open' => 45, 'submit' => 38, 'reject' => 2, 'pending' => 5, 'target' => 52, 'approved' => 33],
            ['name' => 'Eko', 'open' => 40, 'submit' => 32, 'reject' => 4, 'pending' => 4, 'target' => 50, 'approved' => 10],
            ['name' => 'Fitri', 'open' => 48, 'submit' => 40, 'reject' => 3, 'pending' => 5, 'target' => 55, 'approved' => 35],
            ['name' => 'Gunawan', 'open' => 55, 'submit' => 48, 'reject' => 2, 'pending' => 5, 'target' => 60, 'approved' => 43],
            ['name' => 'Hendra', 'open' => 32, 'submit' => 25, 'reject' => 3, 'pending' => 4, 'target' => 42, 'approved' => 21],
            ['name' => 'Ika', 'open' => 44, 'submit' => 36, 'reject' => 3, 'pending' => 5, 'target' => 52, 'approved' => 15],
            ['name' => 'Joko', 'open' => 52, 'submit' => 44, 'reject' => 3, 'pending' => 5, 'target' => 58, 'approved' => 39],
            ['name' => 'Kartika', 'open' => 46, 'submit' => 38, 'reject' => 3, 'pending' => 5, 'target' => 54, 'approved' => 33],
            ['name' => 'Lestari', 'open' => 41, 'submit' => 33, 'reject' => 4, 'pending' => 4, 'target' => 50, 'approved' => 29],
            ['name' => 'Mahmud', 'open' => 49, 'submit' => 41, 'reject' => 3, 'pending' => 5, 'target' => 56, 'approved' => 36],
            ['name' => 'Nurul', 'open' => 53, 'submit' => 45, 'reject' => 2, 'pending' => 6, 'target' => 58, 'approved' => 40],
            ['name' => 'Putri', 'open' => 47, 'submit' => 39, 'reject' => 3, 'pending' => 5, 'target' => 54, 'approved' => 34],
            ['name' => 'Rahman', 'open' => 39, 'submit' => 31, 'reject' => 4, 'pending' => 4, 'target' => 48, 'approved' => 27],
            ['name' => 'Siti', 'open' => 54, 'submit' => 46, 'reject' => 2, 'pending' => 6, 'target' => 60, 'approved' => 55],
            ['name' => 'Tono', 'open' => 43, 'submit' => 35, 'reject' => 3, 'pending' => 5, 'target' => 52, 'approved' => 30],
            ['name' => 'Umar', 'open' => 51, 'submit' => 43, 'reject' => 3, 'pending' => 5, 'target' => 57, 'approved' => 38],
            ['name' => 'Vina', 'open' => 48, 'submit' => 40, 'reject' => 3, 'pending' => 5, 'target' => 55, 'approved' => 35],
            ['name' => 'Wahyu', 'open' => 56, 'submit' => 48, 'reject' => 2, 'pending' => 6, 'target' => 62, 'approved' => 42],
            ['name' => 'Yanti', 'open' => 44, 'submit' => 36, 'reject' => 3, 'pending' => 5, 'target' => 52, 'approved' => 31],
            ['name' => 'Zainal', 'open' => 37, 'submit' => 29, 'reject' => 4, 'pending' => 4, 'target' => 46, 'approved' => 25],
            ['name' => 'Amelia', 'open' => 50, 'submit' => 42, 'reject' => 3, 'pending' => 5, 'target' => 56, 'approved' => 37],
            ['name' => 'Bayu', 'open' => 45, 'submit' => 37, 'reject' => 3, 'pending' => 5, 'target' => 52, 'approved' => 32],
            ['name' => 'Citra', 'open' => 41, 'submit' => 33, 'reject' => 4, 'pending' => 4, 'target' => 50, 'approved' => 29],
            ['name' => 'Dian', 'open' => 53, 'submit' => 45, 'reject' => 2, 'pending' => 6, 'target' => 58, 'approved' => 39],
            ['name' => 'Eri', 'open' => 49, 'submit' => 41, 'reject' => 3, 'pending' => 5, 'target' => 55, 'approved' => 36],
            ['name' => 'Fajar', 'open' => 46, 'submit' => 38, 'reject' => 3, 'pending' => 5, 'target' => 54, 'approved' => 33],
            ['name' => 'Galuh', 'open' => 42, 'submit' => 34, 'reject' => 3, 'pending' => 5, 'target' => 50, 'approved' => 29],
            ['name' => 'Hari', 'open' => 55, 'submit' => 47, 'reject' => 2, 'pending' => 6, 'target' => 60, 'approved' => 41],
            ['name' => 'Irfan', 'open' => 38, 'submit' => 30, 'reject' => 4, 'pending' => 4, 'target' => 48, 'approved' => 26],
            ['name' => 'Jasmine', 'open' => 52, 'submit' => 44, 'reject' => 2, 'pending' => 6, 'target' => 58, 'approved' => 38],
            ['name' => 'Kiki', 'open' => 47, 'submit' => 39, 'reject' => 3, 'pending' => 5, 'target' => 54, 'approved' => 34],
            ['name' => 'Lia', 'open' => 43, 'submit' => 35, 'reject' => 3, 'pending' => 5, 'target' => 52, 'approved' => 30],
            ['name' => 'Mila', 'open' => 51, 'submit' => 43, 'reject' => 3, 'pending' => 5, 'target' => 57, 'approved' => 38],
            ['name' => 'Nanda', 'open' => 40, 'submit' => 32, 'reject' => 4, 'pending' => 4, 'target' => 50, 'approved' => 28],
            ['name' => 'Oki', 'open' => 54, 'submit' => 46, 'reject' => 2, 'pending' => 6, 'target' => 60, 'approved' => 40],
            ['name' => 'Puspita', 'open' => 48, 'submit' => 40, 'reject' => 3, 'pending' => 5, 'target' => 55, 'approved' => 35],
            ['name' => 'Qori', 'open' => 44, 'submit' => 36, 'reject' => 3, 'pending' => 5, 'target' => 52, 'approved' => 31],
            ['name' => 'Rani', 'open' => 49, 'submit' => 41, 'reject' => 3, 'pending' => 5, 'target' => 56, 'approved' => 36],
            ['name' => 'Santi', 'open' => 46, 'submit' => 38, 'reject' => 3, 'pending' => 5, 'target' => 54, 'approved' => 33],
            ['name' => 'Tika', 'open' => 53, 'submit' => 45, 'reject' => 2, 'pending' => 6, 'target' => 58, 'approved' => 39],
            ['name' => 'Ulfa', 'open' => 41, 'submit' => 33, 'reject' => 4, 'pending' => 4, 'target' => 50, 'approved' => 29],
            ['name' => 'Vera', 'open' => 50, 'submit' => 42, 'reject' => 3, 'pending' => 5, 'target' => 56, 'approved' => 37],
            ['name' => 'Wati', 'open' => 45, 'submit' => 37, 'reject' => 3, 'pending' => 5, 'target' => 52, 'approved' => 32],
            ['name' => 'Yuni', 'open' => 52, 'submit' => 44, 'reject' => 2, 'pending' => 6, 'target' => 58, 'approved' => 38],
            ['name' => 'Zahra', 'open' => 47, 'submit' => 39, 'reject' => 3, 'pending' => 5, 'target' => 54, 'approved' => 34],
            ['name' => 'Adi', 'open' => 39, 'submit' => 31, 'reject' => 4, 'pending' => 4, 'target' => 48, 'approved' => 27],
            ['name' => 'Bella', 'open' => 55, 'submit' => 47, 'reject' => 2, 'pending' => 6, 'target' => 60, 'approved' => 41],
            ['name' => 'Candra', 'open' => 42, 'submit' => 34, 'reject' => 4, 'pending' => 4, 'target' => 50, 'approved' => 30],
            ['name' => 'Diah', 'open' => 48, 'submit' => 40, 'reject' => 3, 'pending' => 5, 'target' => 55, 'approved' => 35],
            ['name' => 'Eka', 'open' => 44, 'submit' => 36, 'reject' => 3, 'pending' => 5, 'target' => 52, 'approved' => 31],
            ['name' => 'Feri', 'open' => 51, 'submit' => 43, 'reject' => 3, 'pending' => 5, 'target' => 57, 'approved' => 38],
            ['name' => 'Gita', 'open' => 46, 'submit' => 38, 'reject' => 3, 'pending' => 5, 'target' => 54, 'approved' => 33],
            ['name' => 'Hana', 'open' => 53, 'submit' => 45, 'reject' => 2, 'pending' => 6, 'target' => 58, 'approved' => 39],
            ['name' => 'Indra', 'open' => 40, 'submit' => 32, 'reject' => 4, 'pending' => 4, 'target' => 50, 'approved' => 28],
            ['name' => 'Juni', 'open' => 49, 'submit' => 41, 'reject' => 3, 'pending' => 5, 'target' => 56, 'approved' => 36],
            ['name' => 'Krisna', 'open' => 54, 'submit' => 46, 'reject' => 2, 'pending' => 6, 'target' => 60, 'approved' => 53],
            ['name' => 'Lina', 'open' => 47, 'submit' => 39, 'reject' => 3, 'pending' => 5, 'target' => 54, 'approved' => 34],
            ['name' => 'Mila', 'open' => 43, 'submit' => 35, 'reject' => 3, 'pending' => 5, 'target' => 52, 'approved' => 30],
            ['name' => 'Nia', 'open' => 50, 'submit' => 42, 'reject' => 3, 'pending' => 5, 'target' => 56, 'approved' => 37],
            ['name' => 'Omar', 'open' => 45, 'submit' => 37, 'reject' => 3, 'pending' => 5, 'target' => 52, 'approved' => 32],
        ];

        // PML to PCL mapping (each PML oversees 4-6 PCL)
        $pmlNames = array_column($pmlData, 'name');
        $pmlIndex = 0;
        $pclPerPmlCount = [];
        $pclToPmlMap = [];

        foreach ($pclData as $index => $pcl) {
            if ($pmlIndex >= count($pmlNames)) {
                $pmlIndex = 0;
            }

            if (! isset($pclPerPmlCount[$pmlIndex])) {
                $pclPerPmlCount[$pmlIndex] = 0;
            }

            $maxPclPerPml = ($pmlIndex < 4) ? 6 : 5;
            if ($pclPerPmlCount[$pmlIndex] >= $maxPclPerPml) {
                $pmlIndex++;
                if ($pmlIndex >= count($pmlNames)) {
                    $pmlIndex = 0;
                }
                $pclPerPmlCount[$pmlIndex] = 0;
            }

            $pclToPmlMap[$index] = $pmlNames[$pmlIndex];
            $pclPerPmlCount[$pmlIndex]++;
        }

        // Assign PML to each PCL
        foreach ($pclData as $index => &$pcl) {
            $pcl['pml'] = $pclToPmlMap[$index];
        }

        // Calculate PCL progress percentage and submit ratio
        foreach ($pclData as &$pcl) {
            $pcl['progress'] = $pcl['target'] > 0 ? round(($pcl['approved'] / $pcl['target']) * 100, 1) : 0;
            $pcl['submit_ratio'] = $pcl['target'] > 0 ? round(($pcl['submit'] / $pcl['target']) * 100, 1) : 0;
        }

        // Calculate PCL totals
        $pclTotals = [
            'open' => array_sum(array_column($pclData, 'open')),
            'submit' => array_sum(array_column($pclData, 'submit')),
            'reject' => array_sum(array_column($pclData, 'reject')),
            'pending' => array_sum(array_column($pclData, 'pending')),
            'approved' => array_sum(array_column($pclData, 'approved')),
        ];

        // Get unique PML names for filtering dropdown
        $pmlList = array_unique(array_column($pclData, 'pml'));
        sort($pmlList);

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

        // PCL data - same as in index method
        $pclData = [
            ['name' => 'Abdullah', 'open' => 35, 'submit' => 28, 'reject' => 3, 'pending' => 4, 'target' => 45, 'approved' => 40],
            ['name' => 'Aisyah', 'open' => 42, 'submit' => 35, 'reject' => 2, 'pending' => 5, 'target' => 50, 'approved' => 30],
            ['name' => 'Andi', 'open' => 38, 'submit' => 30, 'reject' => 4, 'pending' => 4, 'target' => 48, 'approved' => 26],
            ['name' => 'Budi', 'open' => 50, 'submit' => 42, 'reject' => 3, 'pending' => 5, 'target' => 55, 'approved' => 39],
            ['name' => 'Dewi', 'open' => 45, 'submit' => 38, 'reject' => 2, 'pending' => 5, 'target' => 52, 'approved' => 33],
            ['name' => 'Eko', 'open' => 40, 'submit' => 32, 'reject' => 4, 'pending' => 4, 'target' => 50, 'approved' => 28],
            ['name' => 'Fitri', 'open' => 48, 'submit' => 40, 'reject' => 3, 'pending' => 5, 'target' => 55, 'approved' => 35],
            ['name' => 'Gunawan', 'open' => 55, 'submit' => 48, 'reject' => 2, 'pending' => 5, 'target' => 60, 'approved' => 43],
            ['name' => 'Hendra', 'open' => 32, 'submit' => 25, 'reject' => 3, 'pending' => 4, 'target' => 42, 'approved' => 21],
            ['name' => 'Ika', 'open' => 44, 'submit' => 36, 'reject' => 3, 'pending' => 5, 'target' => 52, 'approved' => 31],
            ['name' => 'Joko', 'open' => 52, 'submit' => 44, 'reject' => 3, 'pending' => 5, 'target' => 58, 'approved' => 39],
            ['name' => 'Kartika', 'open' => 46, 'submit' => 38, 'reject' => 3, 'pending' => 5, 'target' => 54, 'approved' => 33],
            ['name' => 'Lestari', 'open' => 41, 'submit' => 33, 'reject' => 4, 'pending' => 4, 'target' => 50, 'approved' => 29],
            ['name' => 'Mahmud', 'open' => 49, 'submit' => 41, 'reject' => 3, 'pending' => 5, 'target' => 56, 'approved' => 36],
            ['name' => 'Nurul', 'open' => 53, 'submit' => 45, 'reject' => 2, 'pending' => 6, 'target' => 58, 'approved' => 40],
            ['name' => 'Putri', 'open' => 47, 'submit' => 39, 'reject' => 3, 'pending' => 5, 'target' => 54, 'approved' => 34],
            ['name' => 'Rahman', 'open' => 39, 'submit' => 31, 'reject' => 4, 'pending' => 4, 'target' => 48, 'approved' => 27],
            ['name' => 'Siti', 'open' => 54, 'submit' => 46, 'reject' => 2, 'pending' => 6, 'target' => 60, 'approved' => 55],
            ['name' => 'Tono', 'open' => 43, 'submit' => 35, 'reject' => 3, 'pending' => 5, 'target' => 52, 'approved' => 30],
            ['name' => 'Umar', 'open' => 51, 'submit' => 43, 'reject' => 3, 'pending' => 5, 'target' => 57, 'approved' => 38],
            ['name' => 'Vina', 'open' => 48, 'submit' => 40, 'reject' => 3, 'pending' => 5, 'target' => 55, 'approved' => 35],
            ['name' => 'Wahyu', 'open' => 56, 'submit' => 48, 'reject' => 2, 'pending' => 6, 'target' => 62, 'approved' => 42],
            ['name' => 'Yanti', 'open' => 44, 'submit' => 36, 'reject' => 3, 'pending' => 5, 'target' => 52, 'approved' => 31],
            ['name' => 'Zainal', 'open' => 37, 'submit' => 29, 'reject' => 4, 'pending' => 4, 'target' => 46, 'approved' => 25],
            ['name' => 'Amelia', 'open' => 50, 'submit' => 42, 'reject' => 3, 'pending' => 5, 'target' => 56, 'approved' => 37],
            ['name' => 'Bayu', 'open' => 45, 'submit' => 37, 'reject' => 3, 'pending' => 5, 'target' => 52, 'approved' => 32],
            ['name' => 'Citra', 'open' => 41, 'submit' => 33, 'reject' => 4, 'pending' => 4, 'target' => 50, 'approved' => 29],
            ['name' => 'Dian', 'open' => 53, 'submit' => 45, 'reject' => 2, 'pending' => 6, 'target' => 58, 'approved' => 39],
            ['name' => 'Eri', 'open' => 49, 'submit' => 41, 'reject' => 3, 'pending' => 5, 'target' => 55, 'approved' => 36],
            ['name' => 'Fajar', 'open' => 46, 'submit' => 38, 'reject' => 3, 'pending' => 5, 'target' => 54, 'approved' => 33],
            ['name' => 'Galuh', 'open' => 42, 'submit' => 34, 'reject' => 3, 'pending' => 5, 'target' => 50, 'approved' => 29],
            ['name' => 'Hari', 'open' => 55, 'submit' => 47, 'reject' => 2, 'pending' => 6, 'target' => 60, 'approved' => 41],
            ['name' => 'Irfan', 'open' => 38, 'submit' => 30, 'reject' => 4, 'pending' => 4, 'target' => 48, 'approved' => 26],
            ['name' => 'Jasmine', 'open' => 52, 'submit' => 44, 'reject' => 2, 'pending' => 6, 'target' => 58, 'approved' => 38],
            ['name' => 'Kiki', 'open' => 47, 'submit' => 39, 'reject' => 3, 'pending' => 5, 'target' => 54, 'approved' => 34],
            ['name' => 'Lia', 'open' => 43, 'submit' => 35, 'reject' => 3, 'pending' => 5, 'target' => 52, 'approved' => 30],
            ['name' => 'Mila', 'open' => 51, 'submit' => 43, 'reject' => 3, 'pending' => 5, 'target' => 57, 'approved' => 38],
            ['name' => 'Nanda', 'open' => 40, 'submit' => 32, 'reject' => 4, 'pending' => 4, 'target' => 50, 'approved' => 28],
            ['name' => 'Oki', 'open' => 54, 'submit' => 46, 'reject' => 2, 'pending' => 6, 'target' => 60, 'approved' => 40],
            ['name' => 'Puspita', 'open' => 48, 'submit' => 40, 'reject' => 3, 'pending' => 5, 'target' => 55, 'approved' => 35],
            ['name' => 'Qori', 'open' => 44, 'submit' => 36, 'reject' => 3, 'pending' => 5, 'target' => 52, 'approved' => 31],
            ['name' => 'Rani', 'open' => 49, 'submit' => 41, 'reject' => 3, 'pending' => 5, 'target' => 56, 'approved' => 36],
            ['name' => 'Santi', 'open' => 46, 'submit' => 38, 'reject' => 3, 'pending' => 5, 'target' => 54, 'approved' => 33],
            ['name' => 'Tika', 'open' => 53, 'submit' => 45, 'reject' => 2, 'pending' => 6, 'target' => 58, 'approved' => 39],
            ['name' => 'Ulfa', 'open' => 41, 'submit' => 33, 'reject' => 4, 'pending' => 4, 'target' => 50, 'approved' => 29],
            ['name' => 'Vera', 'open' => 50, 'submit' => 42, 'reject' => 3, 'pending' => 5, 'target' => 56, 'approved' => 37],
            ['name' => 'Wati', 'open' => 45, 'submit' => 37, 'reject' => 3, 'pending' => 5, 'target' => 52, 'approved' => 32],
            ['name' => 'Yuni', 'open' => 52, 'submit' => 44, 'reject' => 2, 'pending' => 6, 'target' => 58, 'approved' => 38],
            ['name' => 'Zahra', 'open' => 47, 'submit' => 39, 'reject' => 3, 'pending' => 5, 'target' => 54, 'approved' => 34],
            ['name' => 'Adi', 'open' => 39, 'submit' => 31, 'reject' => 4, 'pending' => 4, 'target' => 48, 'approved' => 27],
            ['name' => 'Bella', 'open' => 55, 'submit' => 47, 'reject' => 2, 'pending' => 6, 'target' => 60, 'approved' => 41],
            ['name' => 'Candra', 'open' => 42, 'submit' => 34, 'reject' => 4, 'pending' => 4, 'target' => 50, 'approved' => 30],
            ['name' => 'Diah', 'open' => 48, 'submit' => 40, 'reject' => 3, 'pending' => 5, 'target' => 55, 'approved' => 35],
            ['name' => 'Eka', 'open' => 44, 'submit' => 36, 'reject' => 3, 'pending' => 5, 'target' => 52, 'approved' => 31],
            ['name' => 'Feri', 'open' => 51, 'submit' => 43, 'reject' => 3, 'pending' => 5, 'target' => 57, 'approved' => 38],
            ['name' => 'Gita', 'open' => 46, 'submit' => 38, 'reject' => 3, 'pending' => 5, 'target' => 54, 'approved' => 33],
            ['name' => 'Hana', 'open' => 53, 'submit' => 45, 'reject' => 2, 'pending' => 6, 'target' => 58, 'approved' => 39],
            ['name' => 'Indra', 'open' => 40, 'submit' => 32, 'reject' => 4, 'pending' => 4, 'target' => 50, 'approved' => 28],
            ['name' => 'Juni', 'open' => 49, 'submit' => 41, 'reject' => 3, 'pending' => 5, 'target' => 56, 'approved' => 36],
            ['name' => 'Krisna', 'open' => 54, 'submit' => 46, 'reject' => 2, 'pending' => 6, 'target' => 60, 'approved' => 53],
            ['name' => 'Lina', 'open' => 47, 'submit' => 39, 'reject' => 3, 'pending' => 5, 'target' => 54, 'approved' => 34],
            ['name' => 'Mila', 'open' => 43, 'submit' => 35, 'reject' => 3, 'pending' => 5, 'target' => 52, 'approved' => 30],
            ['name' => 'Nia', 'open' => 50, 'submit' => 42, 'reject' => 3, 'pending' => 5, 'target' => 56, 'approved' => 37],
            ['name' => 'Omar', 'open' => 45, 'submit' => 37, 'reject' => 3, 'pending' => 5, 'target' => 52, 'approved' => 32],
        ];

        // PML names for mapping (same as in index method)
        $pmlDataForMapping = [
            ['name' => 'Al Fitri'],
            ['name' => 'Dade Chee'],
            ['name' => 'Deswita'],
            ['name' => 'Gracia Undap'],
            ['name' => 'Hermita Kakalang'],
            ['name' => 'Linsa'],
            ['name' => 'Mama Aim'],
            ['name' => 'Mauren Devina Lombone'],
            ['name' => 'Papa Opo'],
            ['name' => 'Pareda'],
            ['name' => 'Trisna Jacob'],
            ['name' => 'Ungke'],
        ];

        // PML to PCL mapping
        $pmlNames = array_column($pmlDataForMapping, 'name');
        $pmlIndex = 0;
        $pclPerPmlCount = [];
        $pclToPmlMap = [];

        foreach ($pclData as $index => $pcl) {
            if ($pmlIndex >= count($pmlNames)) {
                $pmlIndex = 0;
            }

            if (! isset($pclPerPmlCount[$pmlIndex])) {
                $pclPerPmlCount[$pmlIndex] = 0;
            }

            $maxPclPerPml = ($pmlIndex < 4) ? 6 : 5;
            if ($pclPerPmlCount[$pmlIndex] >= $maxPclPerPml) {
                $pmlIndex++;
                if ($pmlIndex >= count($pmlNames)) {
                    $pmlIndex = 0;
                }
                $pclPerPmlCount[$pmlIndex] = 0;
            }

            $pclToPmlMap[$index] = $pmlNames[$pmlIndex];
            $pclPerPmlCount[$pmlIndex]++;
        }

        // Assign PML to each PCL
        foreach ($pclData as $index => &$pcl) {
            $pcl['pml'] = $pclToPmlMap[$index];
        }

        foreach ($pclData as &$pcl) {
            $pcl['progress'] = $pcl['target'] > 0 ? round(($pcl['approved'] / $pcl['target']) * 100, 1) : 0;
            $pcl['submit_ratio'] = $pcl['target'] > 0 ? round(($pcl['submit'] / $pcl['target']) * 100, 1) : 0;
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
        $sortBy = $request->get('sortBy', '');
        $sortDirection = $request->get('sortDirection', 'asc');
        if ($sortBy && in_array($sortBy, ['submit_ratio', 'progress'])) {
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
