<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('collectes', function (Blueprint $table) {
            $table->id();
            $table->string('code_collecte')->unique();
            $table->foreignId('producteur_id')->constrained()->onDelete('cascade');
            $table->foreignId('credit_id')->nullable()->constrained('credits_agricoles');
            $table->date('date_collecte');
            $table->string('produit');
            $table->decimal('quantite_brute', 10, 2);
            $table->decimal('quantite_nette', 10, 2);
            $table->decimal('prix_unitaire', 10, 2);
            $table->decimal('montant_total', 12, 2);
            $table->decimal('montant_deduict', 12, 2)->default(0);
            $table->decimal('montant_a_payer', 12, 2);
            $table->enum('statut_paiement', ['paye', 'en_attente', 'partiel'])->default('en_attente');
            $table->string('zone_collecte');
            $table->text('observations')->nullable();
            $table->timestamps();
            
            $table->index(['date_collecte', 'produit']);
            $table->index('code_collecte');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('collectes');
    }
};