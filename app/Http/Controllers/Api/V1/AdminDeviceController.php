<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Device;
use Illuminate\Http\Request;

class AdminDeviceController extends Controller
{
    public function index()
    {
        return response()->json(Device::with('assignedAgent')->latest()->get());
    }

    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:ACTIVE,LOCKED,WIPE_PENDING',
        ]);

        $device = Device::findOrFail($id);
        $device->update(['status' => $validated['status']]);

        return response()->json([
            'message' => "Statut de l'appareil mis à jour avec succès.",
            'data' => $device
        ]);
    }
}