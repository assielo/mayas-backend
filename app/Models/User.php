<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens; // <--- Importer

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable; // <--- Ajouter HasApiTokens ici

   protected $fillable = [
    'name',
    'email',
    'password',
    'telephone',
    'otp_code',
    'otp_expires_at',
];

protected $casts = [
    'otp_expires_at' => 'datetime',
];

    protected $hidden = [
        'password',
        'remember_token',
    ];
}