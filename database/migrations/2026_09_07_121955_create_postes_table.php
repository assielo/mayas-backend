<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Reproduit fidèlement la table `postes` telle qu'elle existe déjà
     * dans mayas_sgbd_db.
     * NB : cette table n'a ni `created_at` ni `updated_at` en base réelle.
     */
    public function up(): void
    {
        Schema::create('postes', function (Blueprint $table) {
            $table->id();
            $table->string('intitule', 100);
            $table->decimal('salaire_base', 10, 2)->default(0);
            $table->text('description')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('postes');
    }
};
