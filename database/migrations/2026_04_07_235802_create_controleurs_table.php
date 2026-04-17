<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('controleurs', function (Blueprint $table) {
            $table->id();
            $table->string('code_controleur')->unique();
            $table->string('nom_complet');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('telephone');
            $table->string('region_controle');
            $table->integer('nombre_visites')->default(0);
            $table->enum('statut', ['actif', 'inactif'])->default('actif');
            $table->date('date_embauche');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('controleurs');
    }
};