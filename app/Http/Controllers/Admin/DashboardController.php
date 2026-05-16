<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Message;
use App\Models\PageView;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

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

        // Extended stats (only if tables exist)
        $extStats = [];
        $tableMap = [
            'messages'    => ['messages',    'unread_messages',  fn() => Message::where('status', 'unread')->count()],
            'leads'       => ['leads',       'new_leads',        fn() => DB::table('leads')->where('status', 'new')->count()],
            'subscribers' => ['subscribers', 'subscribers',      fn() => DB::table('subscribers')->where('status', 'active')->count()],
            'posts'       => ['posts',       'published_posts',  fn() => DB::table('posts')->where('status', 'published')->count()],
            'tickets'     => ['tickets',     'open_tickets',     fn() => DB::table('tickets')->whereIn('status', ['open', 'in_progress'])->count()],
            'plans'       => ['plans',       'active_plans',     fn() => DB::table('plans')->where('is_active', true)->count()],
        ];

        foreach ($tableMap as $key => [$table, $statKey, $counter]) {
            try {
                if (Schema::hasTable($table)) {
                    $extStats[$statKey] = $counter();
                }
            } catch (\Throwable) {
                $extStats[$statKey] = 0;
            }
        }

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

        $chartDays   = [];
        $chartTotals = [];
        $chartUnique = [];
        for ($i = 29; $i >= 0; $i--) {
            $date          = now()->subDays($i)->format('Y-m-d');
            $chartDays[]   = now()->subDays($i)->format('M d');
            $chartTotals[] = isset($dailyVisits[$date]) ? $dailyVisits[$date]->total : 0;
            $chartUnique[] = isset($dailyVisits[$date]) ? $dailyVisits[$date]->unique_visitors : 0;
        }

        // Recent messages (if table exists)
        $recentMessages = [];
        try {
            if (Schema::hasTable('messages')) {
                $recentMessages = Message::orderBy('created_at', 'desc')->limit(5)->get();
            }
        } catch (\Throwable) {}

        $recentActivity = ActivityLog::orderBy('created_at', 'desc')->limit(10)->get();

        return view('admin.dashboard', compact(
            'stats', 'extStats', 'chartDays', 'chartTotals', 'chartUnique',
            'recentActivity', 'recentMessages'
        ));
    }
}
