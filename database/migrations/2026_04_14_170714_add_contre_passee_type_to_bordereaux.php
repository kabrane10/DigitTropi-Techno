<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bordereaux', function (Blueprint $table) {
            // Modifier l'enum pour ajouter 'contre_passee'
            $table->enum('type', ['collecte', 'achat', 'chargement', 'livraison', 'contre_passee'])->change();
        });
    }

    public function down(): void
    {
        Schema::table('bordereaux', function (Blueprint $table) {
            $table->enum('type', ['collecte', 'achat', 'chargement', 'livraison'])->change();
        });
    }
};