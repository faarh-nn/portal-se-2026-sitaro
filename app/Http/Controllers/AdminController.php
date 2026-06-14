<?php

namespace App\Http\Controllers;

use App\Models\Usaha;
use App\Models\User;
use Illuminate\View\View;

class AdminController extends Controller
{
    /**
     * Display the admin dashboard.
     */
    public function dashboard(): View
    {
        return view('admin.dashboard', [
            'userCount' => User::count(),
            'adminCount' => User::where('role', 'admin')->count(),
            'usahaCount' => Usaha::count(),
        ]);
    }
}
