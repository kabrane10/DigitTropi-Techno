<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // On ajoute la colonne role_id après l'id avec une valeur par defaut (1) 
            // pour tous les utilisateurs existants
           $table->foreignId('role_id')->after('id')->default(1)->constrained('roles');
        });
    }
nj
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {

            // 1. On supprime la contrainte de clé étrangère
            $table->dropForeign(['role_id']);

            // 2. On supprime la colonne
            $table->dropColumn('role_id');
        });
    }
};