<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('distributions_intrants', function (Blueprint $table) {
            $table->id();
            $table->string('code_distribution')->unique();
            
            // Champs polymorphiques pour bénéficiaire
            $table->string('beneficiaire_type')->nullable();
            $table->unsignedBigInteger('beneficiaire_id')->nullable();
            
            // Pour compatibilité et facilité de requêtage
            $table->foreignId('producteur_id')->nullable()->constrained('producteurs')->onDelete('set null');
            $table->foreignId('cooperative_id')->nullable()->constrained('cooperatives')->onDelete('set null');
            
            // Intrant et crédit
            $table->foreignId('intrant_id')->constrained('intrants')->onDelete('cascade');
            $table->foreignId('credit_id')->nullable()->constrained('credits_agricoles')->onDelete('set null');
            
            // Quantités et montants
            $table->decimal('quantite', 10, 2);
            $table->decimal('prix_unitaire', 10, 2);
            $table->decimal('montant_total', 12, 2);
            
            // Dates et localisation
            $table->date('date_distribution');
            $table->string('zone');
            
            // Notes
            $table->text('notes')->nullable();
            
            $table->timestamps();
            
            // Index
            $table->index(['beneficiaire_type', 'beneficiaire_id']);
            $table->index('producteur_id');
            $table->index('cooperative_id');
            $table->index('intrant_id');
            $table->index('date_distribution');
            $table->index('zone');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('distributions_intrants');
    }
};