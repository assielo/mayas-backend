<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Site extends Model
{
    use HasFactory;

    // Indique à Eloquent de ne pas mettre à jour la colonne 'updated_at'
    const UPDATED_AT = null;

    protected $fillable = [
        'nom_site', 
        'localisation', 
        'responsable_site', 
        'contact'
    ];

    public function accessLogs()
    {
        return $this->hasMany(AccessLog::class);
    }
}