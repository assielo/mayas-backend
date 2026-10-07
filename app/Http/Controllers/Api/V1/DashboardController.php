<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\AnalyticsService;
use App\Models\VisitLog;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function metrics(Request $request, AnalyticsService $analytics)
    {
        $siteId = $request->query('site_id');
        
        if (!$siteId) {
            return response()->json(['error' => 'Le paramètre site_id est requis.'], 400);
        }

        return response()->json($analytics->getSiteMetrics((int)$siteId));
    }

    public function activeVisits(Request $request)
    {
        $siteId = $request->query('site_id');

        $visits = VisitLog::with(['visitor', 'vehicle'])
            ->where('site_id', $siteId)
            ->where('status', 'INSIDE')
            ->orderBy('entry_time', 'desc')
            ->get();

        return response()->json(['data' => $visits]);
    }
}