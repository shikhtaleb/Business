<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\PageView;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $today      = now()->startOfDay();
        $weekStart  = now()->startOfWeek();
        $monthStart = now()->startOfMonth();

        $stats = [
            'views_today'   => PageView::where('created_at', '>=', $today)->count(),
            'views_week'    => PageView::where('created_at', '>=', $weekStart)->count(),
            'views_month'   => PageView::where('created_at', '>=', $monthStart)->count(),
            'unique_today'  => PageView::where('created_at', '>=', $today)->distinct('ip_hash')->count('ip_hash'),
        ];

        // Daily visits for last 30 days
        $dailyVisits = PageView::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as total'),
            DB::raw('COUNT(DISTINCT ip_hash) as unique_visitors')
        )
            ->where('created_at', '>=', now()->subDays(30))
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        // Fill in missing days
        $chartDays   = [];
        $chartTotals = [];
        $chartUnique = [];
        for ($i = 29; $i >= 0; $i--) {
            $date          = now()->subDays($i)->format('Y-m-d');
            $chartDays[]   = now()->subDays($i)->format('M d');
            $chartTotals[] = isset($dailyVisits[$date]) ? $dailyVisits[$date]->total : 0;
            $chartUnique[] = isset($dailyVisits[$date]) ? $dailyVisits[$date]->unique_visitors : 0;
        }

        $recentActivity = ActivityLog::orderBy('created_at', 'desc')->limit(10)->get();

        return view('admin.dashboard', compact('stats', 'chartDays', 'chartTotals', 'chartUnique', 'recentActivity'));
    }
}
