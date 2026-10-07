<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Device;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureDeviceIsActive
{
    public function handle(Request $request, Closure $next): Response
    {
        $deviceUuid = $request->header('X-Device-UUID');

        if (!$deviceUuid) {
            return response()->json(['error' => 'En-tête X-Device-UUID manquant.'], 400);
        }

        $device = Device::where('device_uuid', $deviceUuid)->first();

        if (!$device) {
            return response()->json(['error' => 'Appareil non enregistré dans la flotte.'], 403);
        }

        if ($device->status === 'LOCKED') {
            return response()->json(['error' => 'Cet appareil a été verrouillé à distance.'], 403);
        }

        if ($device->status === 'WIPE_PENDING') {
            return response()->json([
                'action' => 'WIPE_DATA',
                'error' => 'Ordre d\'effacement des données exécuté.'
            ], 410);
        }

        $request->attributes->set('current_device', $device);

        return $next($request);
    }
}