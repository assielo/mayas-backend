<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Reproduit fidèlement la table `agents` telle qu'elle existe déjà
     * dans mayas_sgbd_db. Remplace l'ancienne migration qui utilisait
     * des noms de colonnes inventés (nom_prenoms, salaire_mensuel,
     * site_affectation, superviseur_id) absents de la vraie base.
     *
     * Champs volontairement omis car absents de la vraie table `agents` :
     * `role`, `superviseur_id`. Si un jour tu veux distinguer les
     * superviseurs, ça peut passer par la table `postes` (poste_id).
     */
    public function up(): void
    {
        Schema::create('agents', function (Blueprint $table) {
            $table->id();
            $table->string('matricule', 50)->unique();
            $table->string('nom_prenom', 150);
            $table->string('photo_url')->nullable();
            $table->enum('sexe', ['M', 'F']);
            $table->string('numero_piece', 50)->nullable()->unique();
            $table->string('numero_wave', 20);
            $table->decimal('salaire', 10, 2);
            $table->enum('statut', ['Actif', 'Inactif', 'Suspendu', 'Rupture de contrat'])
                ->default('Actif');
            $table->date('date_debut');
            $table->date('date_fin')->nullable();
            $table->foreignId('site_id')->nullable()->constrained('sites')->nullOnDelete();
            $table->foreignId('poste_id')->nullable()->constrained('postes');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('agents');
    }
};
