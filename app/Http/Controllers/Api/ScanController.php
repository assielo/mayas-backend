<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AccessLog;

class ScanController extends Controller 
{
    public function store(Request $request) 
    {
        $validated = $request->validate([
            'site_id'     => 'required|integer',
            'agent_id'    => 'required|integer',
            'qr_code'     => 'required|string',
            'type_action' => 'required|in:ENTRY,EXIT,CHECKPOINT',
            'latitude'    => 'nullable|numeric',
            'longitude'   => 'nullable|numeric',
        ]);

        $log = AccessLog::create($validated);

        return response()->json(['status' => 'success', 'data' => $log], 201);
    }
}