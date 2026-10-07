<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

Schema::create('access_logs', function (Blueprint $table) {
    $table->id();
    $table->foreignId('site_id')->nullable()->constrained('sites')->nullOnDelete();
    $table->string('agent_nom');
    $table->string('action');
    $table->string('statut')->default('Validé');
    $table->timestamps();
});
