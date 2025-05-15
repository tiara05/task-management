<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Gate;

class LogController extends Controller
{
    public function index()
    {
        if (!Gate::allows('view-logs')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        return response()->json(ActivityLog::latest()->get());
    }
}
