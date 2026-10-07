<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AccessLog;
use App\Models\Incident;
use App\Models\Site;
use Symfony\Component\HttpFoundation\StreamedResponse;

class AuditController extends Controller
{
    public function index()
    {
        $totalScans24h = AccessLog::where('created_at', '>=', now()->subHours(24))->count();
        $anomaliesCount = class_exists(Incident::class) ? Incident::where('created_at', '>=', now()->subHours(24))->count() : 0;
        $sitesActifsCount = Site::count();

        // Chargement des logs récents et des sites
        $recentLogs = AccessLog::with(['site', 'agent'])->latest()->take(10)->get();
        $sites = Site::all();
        $incidents = class_exists(Incident::class) ? Incident::where('created_at', '>=', now()->subHours(24))->latest()->get() : collect();

      return view('reports.index', compact(
    'totalScans24h', 
    'anomaliesCount', 
    'sitesActifsCount', 
    'recentLogs', 
    'sites',
    'incidents'
));
    }

    public function exportCsv(Request $request)
    {
        $siteId = $request->input('site_id');
        $dateDebut = $request->input('date_debut');
        $dateFin = $request->input('date_fin');

        $query = AccessLog::with(['site', 'agent']);

        if ($siteId && $siteId !== 'all') {
            $query->where('site_id', $siteId);
        }

        if ($dateDebut) {
            $query->whereDate('created_at', '>=', $dateDebut);
        }

        if ($dateFin) {
            $query->whereDate('created_at', '<=', $dateFin);
        }

        $logs = $query->latest()->get();
        $fileName = 'audit_securite_' . date('Y-m-d_H-i-s') . '.csv';

        $response = new StreamedResponse(function () use ($logs) {
            $handle = fopen('php://output', 'w');
            fputs($handle, "\xEF\xBB\xBF"); // BOM UTF-8

            fputcsv($handle, ['Horodatage', 'Agent ID / Agent', 'Site', 'Type d\'action', 'QR Code'], ';');

            foreach ($logs as $log) {
                fputcsv($handle, [
                    $log->created_at->format('Y-m-d H:i:s'),
                    $log->agent->name ?? ('Agent #' . $log->agent_id),
                    $log->site->nom_site ?? 'N/A',
                    $log->type_action,
                    $log->qr_code ?? 'N/A'
                ], ';');
            }

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv; charset=utf-8');
        $response->headers->set('Content-Disposition', 'attachment; filename="' . $fileName . '"');

        return $response;
    }
}