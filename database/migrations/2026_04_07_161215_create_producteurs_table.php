<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('producteurs', function (Blueprint $table) {
            $table->id();
            $table->string('code_producteur')->unique();
            $table->string('nom_complet');
            $table->string('contact')->unique();
            $table->string('email')->nullable();
            $table->string('localisation');
            $table->string('region'); // Centrale, Kara, Savanes
            $table->string('culture_pratiquee');
            $table->decimal('superficie_totale', 10, 2); // en hectares
            $table->foreignId('cooperative_id')->nullable()->constrained()->onDelete('set null');
            $table->enum('statut', ['actif', 'inactif', 'en_attente'])->default('actif');
            $table->date('date_enregistrement');
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index(['region', 'statut']);
            $table->index('code_producteur');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('producteurs');
    }
};