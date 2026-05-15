<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function index(Request $request)
    {
        $logName = $request->get('log');
        $search  = $request->get('search');

        $query = ActivityLog::orderBy('created_at', 'desc');

        if ($logName && $logName !== 'all') {
            $query->where('log_name', $logName);
        }

        if ($search) {
            $query->where('description', 'like', '%' . $search . '%');
        }

        $logs     = $query->paginate(25)->withQueryString();
        $logNames = ActivityLog::select('log_name')->distinct()->orderBy('log_name')->pluck('log_name');

        return view('admin.activity.index', compact('logs', 'logNames', 'logName', 'search'));
    }
}
