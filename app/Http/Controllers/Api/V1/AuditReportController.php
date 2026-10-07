<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\VisitLog;
use Illuminate\Http\Request;

class AuditReportController extends Controller
{
   public function downloadCsv(Request $request)
    {
        $validated = $request->validate([
            'site_id' => 'required|exists:sites,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $fileName = 'audit_passages_' . now()->format('Ymd_His') . '.csv';

        $logs = VisitLog::with(['visitor', 'vehicle'])
            ->where('site_id', $validated['site_id'])
            ->whereBetween('entry_time', [$validated['start_date'], $validated['end_date']])
            ->orderBy('entry_time', 'asc')
            ->get();

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $callback = function () use ($logs) {
            $file = fopen('php://output', 'w');
            // En-têtes CSV
            fputcsv($file, ['ID', 'Nom Visiteur', 'CNI', 'Plaque', 'Heure Entree', 'Heure Sortie', 'Statut']);

            foreach ($logs as $log) {
                fputcsv($file, [
                    $log->id,
                    $log->visitor->first_name . ' ' . $log->visitor->last_name,
                    $log->visitor->id_card_number,
                    $log->vehicle?->license_plate ?? 'N/A',
                    $log->entry_time,
                    $log->exit_time ?? 'Sur site',
                    $log->status
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }}