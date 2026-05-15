<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PageView;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AnalyticsController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->get('filter', '30');
        $filter = in_array($filter, ['7', '30', '90']) ? $filter : '30';
        $days   = (int) $filter;

        $now   = now();
        $since = $now->copy()->subDays($days)->startOfDay();

        // -----------------------------------------------------------------------
        // Summary stats
        // -----------------------------------------------------------------------

        $todayStart  = $now->copy()->startOfDay();
        $weekStart   = $now->copy()->startOfWeek();
        $monthStart  = $now->copy()->startOfMonth();

        $stats = [
            'views_today'   => PageView::where('created_at', '>=', $todayStart)->count(),
            'views_week'    => PageView::where('created_at', '>=', $weekStart)->count(),
            'views_month'   => PageView::where('created_at', '>=', $monthStart)->count(),
            'unique_today'  => PageView::where('created_at', '>=', $todayStart)
                ->distinct('ip_hash')->count('ip_hash'),
            'unique_month'  => PageView::where('created_at', '>=', $monthStart)
                ->distinct('ip_hash')->count('ip_hash'),
        ];

        // -----------------------------------------------------------------------
        // Daily chart data (last $days days, filling gaps with 0)
        // -----------------------------------------------------------------------

        $rawDaily = PageView::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as total'),
            DB::raw('COUNT(DISTINCT ip_hash) as unique_visitors')
        )
            ->where('created_at', '>=', $since)
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        $dailyData = [];
        for ($i = $days - 1; $i >= 0; $i--) {
            $date  = $now->copy()->subDays($i)->format('Y-m-d');
            $label = $now->copy()->subDays($i)->format('M d');

            $dailyData[] = [
                'date'            => $date,
                'label'           => $label,
                'total'           => isset($rawDaily[$date]) ? (int) $rawDaily[$date]->total : 0,
                'unique_visitors' => isset($rawDaily[$date]) ? (int) $rawDaily[$date]->unique_visitors : 0,
            ];
        }

        // -----------------------------------------------------------------------
        // Top 10 pages (last $days days)
        // -----------------------------------------------------------------------

        $topPages = PageView::select('page', DB::raw('COUNT(*) as total'))
            ->where('created_at', '>=', $since)
            ->groupBy('page')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        // -----------------------------------------------------------------------
        // Top 10 referrers (non-empty, last $days days)
        // -----------------------------------------------------------------------

        $topReferrers = PageView::select('referrer', DB::raw('COUNT(*) as total'))
            ->where('created_at', '>=', $since)
            ->whereNotNull('referrer')
            ->where('referrer', '!=', '')
            ->groupBy('referrer')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        return view('admin.analytics.index', compact(
            'filter',
            'stats',
            'dailyData',
            'topPages',
            'topReferrers'
        ));
    }
}
