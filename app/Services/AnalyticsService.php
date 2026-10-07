<?php

namespace App\Services;

use App\Models\VisitLog;
use Illuminate\Support\Facades\DB;

class AnalyticsService
{
    public function getSiteMetrics(int $siteId): array
    {
        $activeVisits = VisitLog::where('site_id', $siteId)
            ->where('status', 'INSIDE')
            ->count();

        $activeVehicles = VisitLog::where('site_id', $siteId)
            ->where('status', 'INSIDE')
            ->whereNotNull('vehicle_id')
            ->count();

        // Calcul SQL de la durée moyenne des visites terminées aujourd'hui (en minutes)
        $avgDurationMinutes = VisitLog::where('site_id', $siteId)
            ->where('status', 'EXIT')
            ->whereDate('entry_time', now()->today())
            ->select(DB::raw('AVG(TIMESTAMPDIFF(MINUTE, entry_time, exit_time)) as avg_duration'))
            ->value('avg_duration');

        return [
            'active_visitors_count' => $activeVisits,
            'active_vehicles_count' => $activeVehicles,
            'average_visit_duration_minutes' => round($avgDurationMinutes ?? 0, 1),
        ];
    }
}