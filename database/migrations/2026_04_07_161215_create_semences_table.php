<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('semences', function (Blueprint $table) {
            $table->id();
            $table->string('code_semence')->unique();
            $table->string('nom');
            $table->string('variete');
            $table->enum('type', ['soja', 'arachide', 'sesame', 'mais', 'riz', 'gombo', 'autres']);
            $table->decimal('prix_unitaire', 10, 2);
            $table->decimal('stock_disponible', 10, 2)->default(0); // en kg
            $table->string('unite')->default('kg'); // kg, tonne, sac
            $table->integer('duree_conservation_mois')->nullable();
            $table->text('description')->nullable();
            $table->text('caracteristiques')->nullable();
            $table->boolean('est_certifiee')->default(true);
            $table->timestamps();
            
            $table->index('type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('semences');
    }
};