<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('intrant_mouvements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('intrant_stock_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['entree', 'sortie']);
            $table->decimal('quantite', 10, 2);
            $table->string('motif');
            $table->string('reference')->nullable();
            $table->foreignId('user_id')->nullable()->constrained('admins');
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index(['type', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('intrant_mouvements');
    }
};