<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VisitLog extends Model
{
    protected $fillable = [
        'site_id',
        'agent_id',
        'visitor_id',
        'vehicle_id',
        'entry_time',
        'exit_time',
        'entry_photo_url',
        'status',
    ];

    public function visitor(): BelongsTo
    {
        return $this->belongsTo(Visitor::class);
    }

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }
}