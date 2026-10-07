<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Reproduit fidèlement la table `sites` telle qu'elle existe déjà
     * dans mayas_sgbd_db (147 sites déjà enregistrés).
     * NB : cette table n'a pas de colonne `updated_at` en base réelle.
     */
    public function up(): void
    {
        Schema::create('sites', function (Blueprint $table) {
            $table->id();
            $table->string('nom_site', 150);
            $table->string('localisation')->nullable();
            $table->string('responsable_site', 100)->nullable();
            $table->string('contact', 50)->nullable();
            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sites');
    }
};
