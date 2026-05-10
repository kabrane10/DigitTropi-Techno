<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('estimations_besoins', function (Blueprint $table) {
            $table->id();
            $table->string('code_estimation')->unique();
            $table->foreignId('producteur_id')->constrained()->onDelete('cascade');
            $table->foreignId('semence_id')->constrained();
            $table->decimal('quantite_estimee', 10, 2);
            $table->decimal('superficie_prevue', 10, 2);
            $table->decimal('credit_montant', 12, 2)->nullable();
            $table->date('date_estimation');
            $table->enum('statut', ['en_attente', 'approuve', 'rejete'])->default('en_attente');
            $table->text('observations')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('estimations_besoins');
    }
};