<?php

namespace App\Http\Controllers;

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

        return view('monitoring', compact('progressData', 'totalTarget', 'totalCompleted', 'overallProgress'));
    }
}
