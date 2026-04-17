<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('suivis_parcellaires', function (Blueprint $table) {
            $table->id();
            $table->string('code_suivi')->unique();
            $table->foreignId('producteur_id')->constrained()->onDelete('cascade');
            $table->foreignId('distribution_semence_id')->constrained('distributions_semences');
            $table->foreignId('animateur_id')->nullable()->constrained('animateurs');
            $table->date('date_suivi');
            $table->decimal('superficie_actuelle', 10, 2);
            $table->decimal('hauteur_plantes', 8, 2)->nullable();
            $table->string('stade_croissance');
            $table->enum('sante_cultures', ['excellente', 'bonne', 'moyenne', 'mauvaise', 'critique']);
            $table->integer('taux_levée')->nullable(); // en pourcentage
            $table->text('problemes_constates')->nullable();
            $table->text('recommandations')->nullable();
            $table->text('actions_prises')->nullable();
            $table->json('photos')->nullable();
            $table->timestamps();
            
            $table->index(['date_suivi', 'sante_cultures']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('suivis_parcellaires');
    }
};