<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AccessLog;
use App\Models\Incident;
use App\Models\Device;

class SyncController extends Controller
{
    // Remontée des scans de QR code / Rondes
    public function syncScan(Request $request)
    {
        $validated = $request->validate([
            'site_id'     => 'required|integer',
            'agent_id'    => 'required|integer',
            'type_action' => 'required|string', // ENTRY, EXIT, CHECKPOINT
            'qr_code'     => 'nullable|string',
            'latitude'    => 'nullable|numeric',
            'longitude'   => 'nullable|numeric',
        ]);

        $log = AccessLog::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Pointage enregistré avec succès',
            'data_id' => $log->id
        ], 201);
    }

    // Remontée des signalements d'incidents
    public function syncIncident(Request $request)
    {
        $validated = $request->validate([
            'site_id'     => 'required|integer',
            'agent_id'    => 'required|integer',
            'titre'       => 'required|string',
            'description' => 'required|string',
            'niveau'      => 'required|string', // BAS, MOYEN, CRITIQUE
        ]);

        $incident = Incident::create($validated);

        return response()->json(['status' => 'success', 'id' => $incident->id], 201);
    }

    // Signal de vie du terminal MDM (Statut Batterie, Position, Synchro)
    public function heartbeatMDM(Request $request)
    {
        $device = Device::updateOrCreate(
            ['device_uuid' => $request->device_uuid],
            [
                'agent_id' => $request->agent_id,
                'site_id'  => $request->site_id,
                'battery_level' => $request->battery_level,
                'last_seen_at' => now(),
                'status' => 'ONLINE'
            ]
        );

        return response()->json(['status' => 'acknowledged']);
    }
}