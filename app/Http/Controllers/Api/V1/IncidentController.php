<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\VisitLog;
use Illuminate\Http\Request;

class IncidentController extends Controller
{
    public function searchByPlate(Request $request)
    {
        $validated = $request->validate([
            'site_id' => 'required|exists:sites,id',
            'license_plate' => 'required|string|min:2',
        ]);

        $plate = strtoupper(trim($validated['license_plate']));

        $visits = VisitLog::with(['visitor', 'vehicle'])
            ->where('site_id', $validated['site_id'])
            ->whereHas('vehicle', function ($query) use ($plate) {
                $query->where('license_plate', 'LIKE', "%{$plate}%");
            })
            ->orderBy('entry_time', 'desc')
            ->get();

        return response()->json([
            'search_term' => $plate,
            'count' => $visits->count(),
            'data' => $visits
        ]);
    }
}