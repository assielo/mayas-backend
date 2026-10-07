<?php

namespace App\Http\Controllers;

use App\Models\Agent;
use Illuminate\Http\Request;

class AgentController extends Controller
{
    /**
     * Liste des agents (avec le nom du site), + quelques statistiques
     * utilisées par les cartes KPI du dashboard.
     */
    public function index(Request $request)
    {
        $query = Agent::with('site')->orderByDesc('id');

        if ($request->filled('site_id')) {
            $query->where('site_id', $request->input('site_id'));
        }

        if ($request->filled('statut')) {
            $query->where('statut', $request->input('statut'));
        }

        if ($request->filled('q')) {
            $q = $request->input('q');
            $query->where(function ($sub) use ($q) {
                $sub->where('nom_prenom', 'like', "%{$q}%")
                    ->orWhere('matricule', 'like', "%{$q}%")
                    ->orWhere('numero_wave', 'like', "%{$q}%");
            });
        }

        $agents = $query->get();

        return response()->json([
            'success' => true,
            'stats' => [
                'total' => $agents->count(),
                'actifs' => $agents->where('statut', 'Actif')->count(),
                'masse_salariale' => $agents->where('statut', 'Actif')->sum('salaire'),
            ],
            'data' => $agents->map(fn (Agent $agent) => $this->format($agent)),
        ]);
    }

    public function show(Agent $agent)
    {
        return response()->json([
            'success' => true,
            'data' => $this->format($agent->load('site')),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom_prenom' => 'required|string|max:150',
            'sexe' => 'required|in:M,F',
            'numero_piece' => 'nullable|string|max:50|unique:agents,numero_piece',
            'numero_wave' => 'required|string|max:20',
            'salaire' => 'required|numeric|min:0',
            'statut' => 'required|in:Actif,Inactif,Suspendu,Rupture de contrat',
            'date_debut' => 'required|date',
            'date_fin' => 'nullable|date',
            'site_id' => 'nullable|exists:sites,id',
            'poste_id' => 'nullable|exists:postes,id',
        ]);

        // Le matricule est toujours généré côté serveur (voir Agent::booted).
        $agent = Agent::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Agent enregistré avec succès.',
            'data' => $this->format($agent->load('site')),
        ], 201);
    }

    public function update(Request $request, Agent $agent)
    {
        $validated = $request->validate([
            'nom_prenom' => 'sometimes|required|string|max:150',
            'sexe' => 'sometimes|required|in:M,F',
            'numero_piece' => 'nullable|string|max:50|unique:agents,numero_piece,'.$agent->id,
            'numero_wave' => 'sometimes|required|string|max:20',
            'salaire' => 'sometimes|required|numeric|min:0',
            'statut' => 'sometimes|required|in:Actif,Inactif,Suspendu,Rupture de contrat',
            'date_debut' => 'sometimes|required|date',
            'date_fin' => 'nullable|date',
            'site_id' => 'nullable|exists:sites,id',
            'poste_id' => 'nullable|exists:postes,id',
        ]);

        $agent->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Agent mis à jour avec succès.',
            'data' => $this->format($agent->fresh('site')),
        ]);
    }

    public function destroy(Agent $agent)
    {
        $agent->delete();

        return response()->json([
            'success' => true,
            'message' => 'Agent supprimé avec succès.',
        ]);
    }

    /**
     * Export JSON des agents actifs pour Wave (paiement mobile money).
     */
    public function exportWave()
    {
        $agents = Agent::where('statut', 'Actif')
            ->select('matricule', 'nom_prenom', 'numero_wave', 'salaire')
            ->get();

        return response()->json([
            'success' => true,
            'count' => $agents->count(),
            'data' => $agents,
        ]);
    }

    /**
     * Export CSV direct pour import dans Wave.
     */
    public function downloadWaveCsv()
    {
        $fileName = 'export_wave_'.date('Y-m-d').'.csv';
        $agents = Agent::where('statut', 'Actif')
            ->select('numero_wave', 'salaire', 'nom_prenom')
            ->get();

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename={$fileName}",
        ];

        $callback = function () use ($agents) {
            $file = fopen('php://output', 'w');
            fputcsv($file, ['Telephone', 'Montant', 'Nom_Beneficiaire'], ';');

            foreach ($agents as $agent) {
                fputcsv($file, [$agent->numero_wave, $agent->salaire, $agent->nom_prenom], ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Met en forme un agent pour le front (noms de clés attendus par le
     * dashboard JS : nom_prenom, site_nom, salaire...).
     */
    private function format(Agent $agent): array
    {
        return [
            'id' => $agent->id,
            'matricule' => $agent->matricule,
            'nom_prenom' => $agent->nom_prenom,
            'sexe' => $agent->sexe,
            'numero_piece' => $agent->numero_piece,
            'numero_wave' => $agent->numero_wave,
            'salaire' => (float) $agent->salaire,
            'statut' => $agent->statut,
            'date_debut' => optional($agent->date_debut)->format('Y-m-d'),
            'date_fin' => optional($agent->date_fin)->format('Y-m-d'),
            'site_id' => $agent->site_id,
            'site_nom' => $agent->site->nom_site ?? 'Non assigné',
            'poste_id' => $agent->poste_id,
        ];
    }
}
