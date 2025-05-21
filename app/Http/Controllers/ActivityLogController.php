<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Inertia\Inertia;


class ActivityLogController extends Controller
{
    
    public function index(Request $request)
    {
        $activityLogs = ActivityLog::with('causer')->orderByDesc('created_at')->paginate(20);
        // dd($activityLogs->toArray());
        return Inertia::render('Activity/Index', [
            'activityLogs' => $activityLogs
        ]);
    }
}
