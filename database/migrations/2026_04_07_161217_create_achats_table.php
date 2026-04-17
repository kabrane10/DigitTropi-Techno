<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('achats', function (Blueprint $table) {
            $table->id();
            $table->string('code_achat')->unique();
            $table->foreignId('collecte_id')->constrained()->onDelete('cascade');
            $table->date('date_achat');
            $table->string('acheteur');
            $table->string('reference_facture')->nullable();
            $table->decimal('quantite', 10, 2);
            $table->decimal('prix_achat', 10, 2);
            $table->decimal('montant_total', 12, 2);
            $table->enum('mode_paiement', ['especes', 'virement', 'cheque', 'mobile_money']);
            $table->enum('statut', ['confirme', 'en_attente', 'annule'])->default('confirme');
            $table->date('date_paiement')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index(['date_achat', 'statut']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('achats');
    }
};