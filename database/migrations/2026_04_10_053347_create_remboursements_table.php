<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('remboursements', function (Blueprint $table) {
            $table->id();
            $table->string('code_remboursement')->unique();
            $table->unsignedBigInteger('credit_id');
            $table->date('date_remboursement');
            $table->decimal('montant', 12, 2);
            $table->enum('type', ['mensuel', 'anticipe', 'total']);
            $table->enum('mode_paiement', ['especes', 'prelevement_sur_collecte', 'virement', 'mobile_money']);
            $table->string('reference')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            // Foreign key
            $table->foreign('credit_id')->references('id')->on('credits_agricoles')->onDelete('cascade');
            
            $table->index(['date_remboursement', 'credit_id']);
            $table->index('code_remboursement');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('remboursements');
    }
};