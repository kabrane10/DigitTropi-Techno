<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('intrant_stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('intrant_id')->constrained()->onDelete('cascade');
            $table->string('zone');
            $table->decimal('stock_actuel', 10, 2)->default(0);
            $table->decimal('seuil_alerte', 10, 2)->default(100);
            $table->string('unite')->default('kg');
            $table->string('emplacement')->nullable();
            $table->timestamps();
            
            $table->unique(['intrant_id', 'zone']);
            $table->index('zone');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('intrant_stocks');
    }
};