<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\Support\Facades\View;

class DashboardController extends Controller
{
    public function index(DashboardService $dashboardService)
    {
        return View::make('dashboard', $dashboardService->data());
    }
}
