<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccessLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'site_id', 
        'agent_id', 
        'type_action', 
        'qr_code', 
        'latitude', 
        'longitude'
    ];

    public function site()
    {
        return $this->belongsTo(Site::class);
    }

    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }
}