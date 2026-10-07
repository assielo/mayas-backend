<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('incidents', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->string('niveau'); // BAS, MOYEN, CRITIQUE
            $table->string('site_id');
            $table->string('agent_rapporteur');
            $table->text('description')->nullable();
            $table->string('statut')->default('EN_COURS');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('incidents');
    }
};