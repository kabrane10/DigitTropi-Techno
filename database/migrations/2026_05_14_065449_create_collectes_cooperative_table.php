<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('collectes_cooperative', function (Blueprint $table) {
            $table->id();
            $table->string('code_collecte')->unique();
            $table->foreignId('cooperative_id')->constrained('cooperatives')->onDelete('cascade');
            $table->foreignId('credit_id')->nullable()->constrained('credits_agricoles')->onDelete('set null');
            $table->date('date_collecte');
            $table->string('produit');
            $table->decimal('quantite_brute', 10, 2);
            $table->decimal('quantite_nette', 10, 2);
            $table->decimal('prix_unitaire', 10, 2);
            $table->decimal('montant_total', 12, 2);
            $table->decimal('montant_deduit', 12, 2)->default(0);
            $table->decimal('montant_a_payer', 12, 2);
            $table->enum('statut_paiement', ['en_attente', 'partiel', 'paye'])->default('en_attente');
            $table->string('zone_collecte');
            $table->text('observations')->nullable();
            $table->timestamps();
            
            // Index pour optimiser les recherches
            $table->index('cooperative_id');
            $table->index('credit_id');
            $table->index('date_collecte');
            $table->index('produit');
            $table->index('zone_collecte');
            $table->index(['cooperative_id', 'date_collecte']);
            $table->index(['cooperative_id', 'statut_paiement']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('collectes_cooperative');
    }
};