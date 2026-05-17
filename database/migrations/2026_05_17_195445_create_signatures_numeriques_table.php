<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('signatures_numeriques', function (Blueprint $table) {
            $table->id();
            $table->string('document_type');      // credit, estimation, collecte, distribution_semence, distribution_intrant, bordereau_achat, etc.
            $table->unsignedBigInteger('document_id');
            $table->string('signataire_type');    // producteur, cooperative, agent, admin
            $table->unsignedBigInteger('signataire_id');
            $table->string('signataire_nom');
            $table->text('signature_data');       // Base64 ou chemin du fichier
            $table->string('ip_address');
            $table->string('user_agent')->nullable();
            $table->string('hash_unique');         // Empreinte unique pour validation
            $table->text('commentaire')->nullable();
            $table->timestamp('signed_at');
            $table->timestamps();
            
            $table->index(['document_type', 'document_id']);
            $table->index('hash_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('signatures_numeriques');
    }
};