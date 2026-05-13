<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('producteurs', function (Blueprint $table) {
            // Ajouter le champ commune
            $table->string('commune')->nullable()->after('localisation');
            
            // Ajouter les champs de position GPS
            $table->decimal('latitude', 10, 7)->nullable()->after('commune');
            $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
            
            // Index pour faciliter les recherches géographiques
            $table->index('commune');
            $table->index(['latitude', 'longitude']);
        });
    }

    public function down(): void
    {
        Schema::table('producteurs', function (Blueprint $table) {
            $table->dropColumn(['commune', 'latitude', 'longitude']);
        });
    }
};