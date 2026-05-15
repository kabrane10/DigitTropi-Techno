<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cooperatives', function (Blueprint $table) {
            // Ajouter nom_responsable
            if (!Schema::hasColumn('cooperatives', 'nom_responsable')) {
                $table->string('nom_responsable')->nullable()->after('nom');
            }
            
            // Ajouter commune
            if (!Schema::hasColumn('cooperatives', 'commune')) {
                $table->string('commune')->nullable()->after('region');
            }
            
            // Ajouter latitude et longitude pour position exacte
            if (!Schema::hasColumn('cooperatives', 'latitude')) {
                $table->decimal('latitude', 10, 8)->nullable()->after('localisation');
            }
            if (!Schema::hasColumn('cooperatives', 'longitude')) {
                $table->decimal('longitude', 11, 8)->nullable()->after('latitude');
            }
            
            // Optionnel: renommer localisation en adresse (garder compatibilité)
            if (Schema::hasColumn('cooperatives', 'localisation') && !Schema::hasColumn('cooperatives', 'adresse')) {
                $table->renameColumn('localisation', 'adresse');
            } elseif (!Schema::hasColumn('cooperatives', 'adresse')) {
                $table->string('adresse')->nullable()->after('commune');
            }
        });
    }

    public function down(): void
    {
        Schema::table('cooperatives', function (Blueprint $table) {
            $table->dropColumn(['nom_responsable', 'commune', 'latitude', 'longitude']);
            
            // Restaurer le nom localisation si nécessaire
            if (Schema::hasColumn('cooperatives', 'adresse') && !Schema::hasColumn('cooperatives', 'localisation')) {
                $table->renameColumn('adresse', 'localisation');
            }
        });
    }
};