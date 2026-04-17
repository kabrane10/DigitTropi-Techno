<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->string('produit');
            $table->string('zone'); // Centrale, Kara, Savanes
            $table->string('entrepot')->nullable();
            $table->decimal('quantite_entree', 10, 2)->default(0);
            $table->decimal('quantite_sortie', 10, 2)->default(0);
            $table->decimal('stock_actuel', 10, 2)->default(0);
            $table->decimal('seuil_alerte', 10, 2)->default(100);
            $table->string('unite')->default('kg');
            $table->date('dernier_mouvement')->nullable();
            $table->timestamps();
            
            $table->unique(['produit', 'zone']);
            $table->index(['zone', 'stock_actuel']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stocks');
    }
};