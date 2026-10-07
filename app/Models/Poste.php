<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Poste extends Model
{
    // La table `postes` en base réelle n'a ni created_at ni updated_at
    public $timestamps = false;

    protected $fillable = [
        'intitule',
        'salaire_base',
        'description',
    ];

    public function agents()
    {
        return $this->hasMany(Agent::class);
    }
}
