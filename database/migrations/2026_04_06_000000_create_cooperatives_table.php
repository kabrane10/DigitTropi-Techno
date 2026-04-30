<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cooperatives', function (Blueprint $table) {
            $table->id();
            $table->string('nom');
            $table->string('code_cooperative')->unique();
            $table->string('contact');
            $table->string('email')->nullable();
            $table->string('localisation');
            $table->string('region');
            $table->integer('nombre_membres')->default(0);
            $table->date('date_creation');
            $table->enum('statut', ['active', 'suspendue'])->default('active');
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cooperatives');
    }
};