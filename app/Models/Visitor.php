<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Visitor extends Model
{
    protected $fillable = ['id_card_number', 'first_name', 'last_name', 'ocr_raw_payload'];

    protected $casts = [
        'ocr_raw_payload' => 'array',
    ];

    public function visitLogs(): HasMany
    {
        return $this->hasMany(VisitLog::class);
    }
}