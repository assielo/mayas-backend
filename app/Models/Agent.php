<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{
    use HasFactory;

    protected $fillable = [
        'matricule',
        'nom_prenom',
        'photo_url',
        'sexe',
        'numero_piece',
        'numero_wave',
        'salaire',
        'statut',
        'date_debut',
        'date_fin',
        'site_id',
        'poste_id',
    ];

    protected $casts = [
        'salaire' => 'decimal:2',
        'date_debut' => 'date',
        'date_fin' => 'date',
    ];

    /**
     * Génère automatiquement le matricule si non fourni, au format
     * AGT-M-001 / AGT-F-001, en se basant sur le dernier matricule
     * existant pour ce préfixe (et non sur l'id, qui peut avoir des trous).
     *
     * Limite connue : en cas de créations concurrentes, deux agents
     * pourraient recevoir le même matricule (la contrainte unique en
     * base l'empêchera, mais la 2e création échouera). La table
     * `type_agent_sequence` déjà présente en base est prévue pour gérer
     * ça proprement (compteur dédié) — on pourra la brancher plus tard
     * si besoin d'un système plus robuste.
     */
    protected static function booted(): void
    {
        static::creating(function (Agent $agent) {
            if (empty($agent->matricule)) {
                $codeSexe = $agent->sexe === 'F' ? 'F' : 'M';
                $prefixe = "AGT-{$codeSexe}-";

                $dernierMatricule = static::where('matricule', 'like', $prefixe.'%')
                    ->orderByDesc('matricule')
                    ->value('matricule');

                $prochainNumero = $dernierMatricule
                    ? ((int) substr($dernierMatricule, -3)) + 1
                    : 1;

                $agent->matricule = $prefixe.str_pad($prochainNumero, 3, '0', STR_PAD_LEFT);
            }
        });
    }

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function poste()
    {
        return $this->belongsTo(Poste::class);
    }
}
