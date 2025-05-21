<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Inertia\Inertia;


class ActivityLogController extends Controller
{
    
    public function index(Request $request)
    {   
        $search = $request->input('search');

        $activityLogs = ActivityLog::with('causer')->orderByDesc('created_at');

        if ($search) {
            $activityLogs->where(function ($q) use ($search) {
                $q->where('log_name', 'like', "%{$search}%")
                  ->orWhere('event', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }   
        $activityLogs = $activityLogs->paginate(20)->withQueryString();

        return Inertia::render('Activity/Index', [
            'activityLogs' => $activityLogs
        ]);
    }
}
