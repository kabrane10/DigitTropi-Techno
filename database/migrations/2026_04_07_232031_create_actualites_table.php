<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('actualites', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->string('slug')->unique();
            $table->text('contenu');
            $table->string('image_couverture')->nullable();
            $table->string('categorie'); // campagne, evenement, formation, annonce
            $table->date('date_publication');
            $table->date('date_fin')->nullable(); // pour les campagnes
            $table->string('lieu')->nullable();
            $table->boolean('est_en_avant')->default(false);
            $table->boolean('est_publie')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('actualites');
    }
};