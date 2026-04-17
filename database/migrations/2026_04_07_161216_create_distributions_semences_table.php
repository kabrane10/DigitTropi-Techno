<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('distributions_semences', function (Blueprint $table) {
            $table->id();
            $table->string('code_distribution')->unique();
            $table->foreignId('producteur_id')->constrained()->onDelete('cascade');
            $table->foreignId('semence_id')->constrained();
            $table->foreignId('credit_id')->nullable()->constrained('credits_agricoles');
            $table->decimal('quantite', 10, 2);
            $table->decimal('superficie_emblevee', 10, 2);
            $table->date('date_distribution');
            $table->enum('saison', ['principale', 'contre-saison', 'hivernage']);
            $table->decimal('rendement_estime', 10, 2)->nullable(); // en kg/ha
            $table->text('observations')->nullable();
            $table->timestamps();
            
            $table->index(['date_distribution', 'saison']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('distributions_semences');
    }
};