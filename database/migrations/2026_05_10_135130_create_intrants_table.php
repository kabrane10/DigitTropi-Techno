<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('intrants')) {
            Schema::create('intrants', function (Blueprint $table) {
                $table->id();
                $table->string('code_intrant')->unique();
                $table->string('nom');
                $table->enum('type', ['engrais', 'pesticide', 'herbicide', 'semence', 'autre']);
                $table->string('unite')->default('kg');
                $table->decimal('prix_unitaire', 10, 2)->default(0);
                $table->text('description')->nullable();
                $table->boolean('est_actif')->default(true);
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('intrants');
    }
};