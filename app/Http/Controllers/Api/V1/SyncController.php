<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Visitor;
use App\Models\Vehicle;
use App\Models\VisitLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SyncController extends Controller
{
    public function syncOfflineBatch(Request $request)
    {
        $validated = $request->validate([
            'visits' => 'required|array|min:1',
            'visits.*.site_id' => 'required|exists:sites,id',
            'visits.*.id_card_number' => 'required|string',
            'visits.*.first_name' => 'required|string',
            'visits.*.last_name' => 'required|string',
            'visits.*.license_plate' => 'nullable|string',
            'visits.*.entry_time' => 'required|date',
            'visits.*.exit_time' => 'nullable|date',
            'visits.*.status' => 'required|in:INSIDE,EXIT',
            'visits.*.offline_uuid' => 'required|string',
        ]);

        $syncedIds = [];

        DB::transaction(function () use ($validated, $request, &$syncedIds) {
            foreach ($validated['visits'] as $item) {
                // Création ou récupération du visiteur
                $visitor = Visitor::firstOrCreate(
                    ['id_card_number' => $item['id_card_number']],
                    ['first_name' => $item['first_name'], 'last_name' => $item['last_name']]
                );

                // Gestion du véhicule s'il existe
                $vehicleId = null;
                if (!empty($item['license_plate'])) {
                    $vehicle = Vehicle::firstOrCreate([
                        'license_plate' => strtoupper(trim($item['license_plate']))
                    ]);
                    $vehicleId = $vehicle->id;
                }

                // Insertion de la visite synchronisée
                VisitLog::create([
                    'site_id' => $item['site_id'],
                    'agent_id' => $request->user()->id,
                    'visitor_id' => $visitor->id,
                    'vehicle_id' => $vehicleId,
                    'entry_time' => $item['entry_time'],
                    'exit_time' => $item['exit_time'] ?? null,
                    'status' => $item['status'],
                ]);

                $syncedIds[] = $item['offline_uuid'];
            }
        });

        return response()->json([
            'message' => 'Synchronisation hors-ligne terminée avec succès.',
            'synced_offline_uuids' => $syncedIds
        ], 200);
    }
}