<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('credits_agricoles', function (Blueprint $table) {
            $table->id();
            $table->string('code_credit')->unique();
            $table->foreignId('producteur_id')->constrained()->onDelete('cascade');
            $table->foreignId('cooperative_id')->constrained();
            $table->decimal('montant_total', 12, 2);
            $table->decimal('montant_restant', 12, 2);
            $table->decimal('taux_interet', 5, 2)->default(0);
            $table->integer('duree_mois');
            $table->date('date_octroi');
            $table->date('date_echeance');
            $table->enum('statut', ['actif', 'rembourse', 'impaye', 'restructure'])->default('actif');
            $table->text('conditions')->nullable();
            $table->text('observations')->nullable();
            $table->timestamps();
            
            $table->index(['statut', 'date_echeance']);
            $table->index('code_credit');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('credits_agricoles');
    }
};