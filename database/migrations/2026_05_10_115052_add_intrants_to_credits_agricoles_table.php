<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('credits_agricoles', function (Blueprint $table) {
            // Ajouter les champs pour les intrants
            $table->string('type_intrant')->nullable()->after('montant_total');
            $table->string('quantite_intrant')->nullable()->after('type_intrant');
            $table->string('unite_intrant')->nullable()->after('quantite_intrant');
        });
    }

    public function down(): void
    {
        Schema::table('credits_agricoles', function (Blueprint $table) {
            $table->dropColumn(['type_intrant', 'quantite_intrant', 'unite_intrant']);
        });
    }
};