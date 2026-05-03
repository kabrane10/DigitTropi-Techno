<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Créer d'abord la table des albums
        if (!Schema::hasTable('albums')) {
            Schema::create('albums', function (Blueprint $table) {
                $table->id();
                $table->string('titre');
                $table->string('slug')->unique();
                $table->string('categorie');
                $table->string('lieu')->nullable();
                $table->date('date_evenement');
                $table->text('description')->nullable();
                $table->string('couverture')->nullable();
                $table->boolean('est_publie')->default(true);
                $table->timestamps();
            });
        }
        
        // Ajouter album_id à la table galeries
        Schema::table('galeries', function (Blueprint $table) {
            if (!Schema::hasColumn('galeries', 'album_id')) {
                $table->foreignId('album_id')->nullable()->constrained()->onDelete('cascade');
            }
        });
    }

    public function down(): void
    {
        Schema::table('galeries', function (Blueprint $table) {
            $table->dropForeign(['album_id']);
            $table->dropColumn('album_id');
        });
        Schema::dropIfExists('albums');
    }
};