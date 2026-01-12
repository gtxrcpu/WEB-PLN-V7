<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Simple dashboard for petugas role.
     * Shows only quick actions and module cards - no stats/charts.
     */
    public function index()
    {
        return view('dashboard.petugas');
    }
}
