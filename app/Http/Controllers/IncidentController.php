<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Incident;
use App\Models\Site;
use App\Models\AccessLog;

class IncidentController extends Controller
{
    public function index()
    {
        $incidents = Incident::latest()->get();
        $sites = class_exists(Site::class) ? Site::all() : collect();
        $recentLogs = class_exists(AccessLog::class) ? AccessLog::with(['site', 'agent'])->latest()->take(10)->get() : collect();
        
        $totalScans24h = class_exists(AccessLog::class) ? AccessLog::where('created_at', '>=', now()->subHours(24))->count() : 0;
        $sitesActifsCount = class_exists(Site::class) ? Site::count() : 0;
        $anomaliesCount = $incidents->count();

        return view('reports.index', compact(
            'incidents', 
            'sites', 
            'recentLogs', 
            'totalScans24h', 
            'sitesActifsCount', 
            'anomaliesCount'
        ));
    }

    public function store(Request $request)
    {
        $request->validate([
            'titre' => 'required|string|max:255',
            'niveau' => 'required|string',
            'site_id' => 'required|string',
            'agent_rapporteur' => 'required|string',
        ]);

        Incident::create($request->all());

        return redirect()->back()->with('success', 'Incident enregistré avec succès.');
    }
}