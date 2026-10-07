<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Device;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    public function heartbeat(Request $request)
    {
        $validated = $request->validate([
            'device_uuid' => 'required|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'battery_level' => 'nullable|integer|min:0|max:100',
        ]);

        $device = Device::updateOrCreate(
            ['device_uuid' => $validated['device_uuid']],
            [
                'assigned_agent_id' => $request->user()->id,
                'latitude' => $validated['latitude'] ?? null,
                'longitude' => $validated['longitude'] ?? null,
                'battery_level' => $validated['battery_level'] ?? null,
                'last_ping_at' => now(),
            ]
        );

        return response()->json([
            'status' => $device->status,
            'server_time' => now()
        ]);
    }
}