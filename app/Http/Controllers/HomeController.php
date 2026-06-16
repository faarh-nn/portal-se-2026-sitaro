<?php

namespace App\Http\Controllers;

use App\Models\Usaha;
use App\Models\UsahaGmaps;
use App\Models\KecamatanProgress;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search', '');
        $kategori = $request->get('kategori', '');

        $usahas = Usaha::all();

        $totalUsaha = $usahas->count();

        $statsBySkala = [
            'UMK' => $usahas->where('skala_usaha', 'UMK')->count(),
            'UM' => $usahas->where('skala_usaha', 'UM')->count(),
            'UB' => $usahas->where('skala_usaha', 'UB')->count(),
        ];

        $statsByKecamatan = $usahas
            ->filter(fn ($u) => $u->nama_kecamatan)
            ->groupBy('nama_kecamatan')
            ->map(fn ($group) => [
                'total' => $group->count(),
                'UMK' => $group->where('skala_usaha', 'UMK')->count(),
                'UM' => $group->where('skala_usaha', 'UM')->count(),
                'UB' => $group->where('skala_usaha', 'UB')->count(),
            ])
            ->sortBy(fn ($item, $key) => $key)
            ->toArray();

        $statsByDesa = $usahas
            ->filter(fn ($u) => $u->nama_desa)
            ->groupBy('nama_desa')
            ->map(fn ($group) => [
                'kecamatan' => $group->first()->nama_kecamatan,
                'total' => $group->count(),
                'UMK' => $group->where('skala_usaha', 'UMK')->count(),
                'UM' => $group->where('skala_usaha', 'UM')->count(),
                'UB' => $group->where('skala_usaha', 'UB')->count(),
            ])
            ->sortBy(fn ($item, $key) => $key)
            ->toArray();

        $usahaGmaps = UsahaGmaps::whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get();

        $query = UsahaGmaps::query();

        if ($search) {
            $searchLower = strtolower($search);
            $query->where(function ($q) use ($searchLower) {
                $q->whereRaw('LOWER(nama_usaha) LIKE ?', ['%'.$searchLower.'%'])
                    ->orWhereRaw('LOWER(alamat) LIKE ?', ['%'.$searchLower.'%']);
            });
        }

        if ($kategori) {
            $query->where('kategori', $kategori);
        }

        $allUsahaGmaps = $query->paginate(10);

        $kategoriOptions = UsahaGmaps::select('kategori')
            ->whereNotNull('kategori')
            ->distinct()
            ->orderBy('kategori')
            ->pluck('kategori');

        $statsGmaps = [
            'total' => UsahaGmaps::count(),
            'dalam_sbr' => UsahaGmaps::where('is_in_sbr', true)->count(),
            'luar_sbr' => UsahaGmaps::where('is_in_sbr', false)->count(),
        ];

        // Data untuk hero section (dari monitoring)
        $kecamatanProgress = KecamatanProgress::all();
        $totalTarget = $kecamatanProgress->sum('total_assignment');
        $totalCompleted = $kecamatanProgress->sum('submit');
        $overallProgress = $totalTarget > 0 ? round(($totalCompleted / $totalTarget) * 100, 1) : 0;

        return view('welcome', compact(
            'totalUsaha',
            'statsBySkala',
            'statsByKecamatan',
            'statsByDesa',
            'usahaGmaps',
            'statsGmaps',
            'allUsahaGmaps',
            'kategoriOptions',
            'totalTarget',
            'totalCompleted',
            'overallProgress'
        ));
    }

    public function getTablePage(Request $request)
    {
        $page = $request->get('page', 1);
        $search = $request->get('search', '');
        $kategori = $request->get('kategori', '');

        $query = UsahaGmaps::query();

        if ($search) {
            $searchLower = strtolower($search);
            $query->where(function ($q) use ($searchLower) {
                $q->whereRaw('LOWER(nama_usaha) LIKE ?', ['%'.$searchLower.'%'])
                    ->orWhereRaw('LOWER(alamat) LIKE ?', ['%'.$searchLower.'%']);
            });
        }

        if ($kategori) {
            $query->where('kategori', $kategori);
        }

        $allUsahaGmaps = $query->paginate(10, ['*'], 'page', $page);

        return response()->json([
            'html' => view('components.partials.gmaps-table-rows', [
                'allUsahaGmaps' => $allUsahaGmaps,
            ])->render(),
            'pagination' => view('components.partials.gmaps-table-pagination', [
                'allUsahaGmaps' => $allUsahaGmaps,
            ])->render(),
            'search' => $search,
            'kategori' => $kategori,
        ]);
    }
}
