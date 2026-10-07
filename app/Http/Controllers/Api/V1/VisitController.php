<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Visitor;
use App\Models\Vehicle;
use App\Models\VisitLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VisitController extends Controller
{
    public function recordEntry(Request $request)
    {
        $validated = $request->validate([
            'site_id' => 'required|exists:sites,id',
            'id_card_number' => 'required|string',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'license_plate' => 'nullable|string',
            'entry_photo_url' => 'nullable|url',
        ]);

        return DB::transaction(function () use ($validated, $request) {
            $visitor = Visitor::firstOrCreate(
                ['id_card_number' => $validated['id_card_number']],
                ['first_name' => $validated['first_name'], 'last_name' => $validated['last_name']]
            );

            $vehicleId = null;
            if (!empty($validated['license_plate'])) {
                $vehicle = Vehicle::firstOrCreate([
                    'license_plate' => strtoupper(trim($validated['license_plate']))
                ]);
                $vehicleId = $vehicle->id;
            }

            $visitLog = VisitLog::create([
                'site_id' => $validated['site_id'],
                'agent_id' => $request->user()->id,
                'visitor_id' => $visitor->id,
                'vehicle_id' => $vehicleId,
                'entry_time' => now(),
                'entry_photo_url' => $validated['entry_photo_url'] ?? null,
                'status' => 'INSIDE',
            ]);

            return response()->json([
                'message' => 'Entrée enregistrée avec succès',
                'data' => $visitLog->load(['visitor', 'vehicle'])
            ], 201);
        });
    }

    public function recordExit(Request $request, $id)
    {
        $visitLog = VisitLog::where('id', $id)->where('status', 'INSIDE')->firstOrFail();

        $visitLog->update([
            'exit_time' => now(),
            'status' => 'EXIT',
        ]);

        return response()->json([
            'message' => 'Sortie enregistrée avec succès',
            'data' => $visitLog
        ]);
    }
}