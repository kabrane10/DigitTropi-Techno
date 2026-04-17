<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('galeries', function (Blueprint $table) {
            $table->id();
            $table->string('titre');
            $table->string('image');
            $table->text('description')->nullable();
            $table->string('categorie'); // terrain, formation, recolte, producteurs, evenement
            $table->string('lieu')->nullable();
            $table->date('date_prise');
            $table->boolean('est_publie')->default(true);
            $table->integer('ordre')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('galeries');
    }
};