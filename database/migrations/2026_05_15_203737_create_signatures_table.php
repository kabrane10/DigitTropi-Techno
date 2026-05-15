<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Vérifier si la table existe déjà
        if (!Schema::hasTable('signatures')) {
            Schema::create('signatures', function (Blueprint $table) {
                $table->id();
                $table->morphs('signable'); // polymorphic: document_type, document_id
                $table->string('signataire_type'); // producteur, cooperative, agent, admin
                $table->unsignedBigInteger('signataire_id');
                $table->string('signataire_nom');
                $table->text('signature_data')->nullable();
                $table->string('ip_address')->nullable();
                $table->timestamp('signed_at');
                $table->timestamps();
                
                // Supprimer l'index automatique de morphs pour le recréer proprement
                // $table->index(['signable_type', 'signable_id']);
            });
        } else {
            // Si la table existe déjà, ajouter les colonnes manquantes
            Schema::table('signatures', function (Blueprint $table) {
                if (!Schema::hasColumn('signatures', 'signataire_type')) {
                    $table->string('signataire_type')->after('signable_id');
                }
                if (!Schema::hasColumn('signatures', 'signataire_id')) {
                    $table->unsignedBigInteger('signataire_id')->after('signataire_type');
                }
                if (!Schema::hasColumn('signatures', 'signataire_nom')) {
                    $table->string('signataire_nom')->after('signataire_id');
                }
                if (!Schema::hasColumn('signatures', 'signature_data')) {
                    $table->text('signature_data')->nullable()->after('signataire_nom');
                }
                if (!Schema::hasColumn('signatures', 'ip_address')) {
                    $table->string('ip_address')->nullable()->after('signature_data');
                }
                if (!Schema::hasColumn('signatures', 'signed_at')) {
                    $table->timestamp('signed_at')->nullable()->after('ip_address');
                }
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('signatures');
    }
};