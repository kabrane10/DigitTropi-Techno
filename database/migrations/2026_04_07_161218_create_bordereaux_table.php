<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('bordereaux', function (Blueprint $table) {
            $table->id();
            $table->string('code_bordereau')->unique();
            $table->enum('type', ['collecte', 'achat', 'contre_passee', 'chargement', 'livraison']);
            $table->foreignId('reference_id'); // ID de la référence selon le type
            $table->string('reference_type'); // Type de la référence (Collecte, Achat, etc.)
            $table->date('date_emission');
            $table->json('contenu');
            $table->string('emetteur');
            $table->string('destinataire')->nullable();
            $table->enum('statut', ['valide', 'annule', 'en_attente'])->default('valide');
            $table->text('observations')->nullable();
            $table->timestamps();
            
            $table->index(['type', 'date_emission']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('bordereaux');
    }
};