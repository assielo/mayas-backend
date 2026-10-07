<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $fillable = [
        'device_uuid',
        'assigned_agent_id',
        'status',
        'latitude',
        'longitude',
        'battery_level',
        'last_ping_at',
    ];

    protected $casts = [
        'last_ping_at' => 'datetime',
    ];
}