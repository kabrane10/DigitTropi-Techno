<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('animateurs', function (Blueprint $table) {
            // Ajouter la colonne password
            $table->string('password')->after('email')->nullable();
            
            // Ajouter remember_token pour la connexion
            $table->rememberToken()->after('password');
        });
    }

    public function down(): void
    {
        Schema::table('animateurs', function (Blueprint $table) {
            $table->dropColumn(['password', 'remember_token']);
        });
    }
};