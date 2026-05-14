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
        Schema::create('distributions_intrants_cooperative', function (Blueprint $table) {
            $table->id();
            $table->string('code_distribution')->unique();
            $table->foreignId('cooperative_id')->constrained('cooperatives')->onDelete('cascade');
            $table->foreignId('intrant_id')->constrained('intrants')->onDelete('cascade');
            $table->foreignId('credit_id')->nullable()->constrained('credits_agricoles')->onDelete('set null');
            $table->decimal('quantite', 10, 2);
            $table->decimal('prix_unitaire', 10, 2);
            $table->decimal('montant_total', 12, 2);
            $table->date('date_distribution');
            $table->string('zone');
            $table->text('notes')->nullable();
            $table->timestamps();
            
            // Index simples (Laravel s'en sortira car les noms sont plus courts)
            $table->index('cooperative_id');
            $table->index('intrant_id');
            $table->index('date_distribution');
            $table->index('zone');
            
            // CORRECTION ICI : On donne un nom court explicitement
            $table->index(['cooperative_id', 'date_distribution'], 'dist_coop_date_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('distributions_intrants_cooperative');
    }
};