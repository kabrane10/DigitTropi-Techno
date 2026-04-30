<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('animateurs', function (Blueprint $table) {
            $table->id();
            $table->string('code_animateur')->unique();
            $table->string('nom_complet');
            $table->string('contact')->unique();
            $table->string('email')->nullable();
            $table->string('zone_responsabilite');
            $table->string('region');
            $table->integer('nombre_producteurs_suivis')->default(0);
            $table->enum('statut', ['actif', 'inactif'])->default('actif');
            $table->date('date_embauche');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('animateurs');
    }
};