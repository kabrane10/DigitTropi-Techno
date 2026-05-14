<?php
// database/migrations/2024_01_01_000005_add_beneficiaire_fields_to_existing_tables.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Table distributions_semences
        Schema::table('distributions_semences', function (Blueprint $table) {
            if (!Schema::hasColumn('distributions_semences', 'cooperative_id')) {
                $table->foreignId('cooperative_id')->nullable()->after('producteur_id')
                      ->constrained('cooperatives')->onDelete('set null');
            }
            if (!Schema::hasColumn('distributions_semences', 'beneficiaire_type')) {
                $table->string('beneficiaire_type')->nullable()->after('cooperative_id');
            }
            if (!Schema::hasColumn('distributions_semences', 'beneficiaire_id')) {
                $table->unsignedBigInteger('beneficiaire_id')->nullable()->after('beneficiaire_type');
            }
            if (!Schema::hasColumn('distributions_semences', 'prix_unitaire')) {
                $table->decimal('prix_unitaire', 10, 2)->nullable()->after('quantite');
            }
            if (!Schema::hasColumn('distributions_semences', 'montant_total')) {
                $table->decimal('montant_total', 12, 2)->nullable()->after('prix_unitaire');
            }
            $table->index(['beneficiaire_type', 'beneficiaire_id']);
        });

        // 2. Table collectes
        Schema::table('collectes', function (Blueprint $table) {
            if (!Schema::hasColumn('collectes', 'cooperative_id')) {
                $table->foreignId('cooperative_id')->nullable()->after('producteur_id')
                      ->constrained('cooperatives')->onDelete('set null');
            }
            if (!Schema::hasColumn('collectes', 'beneficiaire_type')) {
                $table->string('beneficiaire_type')->nullable()->after('cooperative_id');
            }
            if (!Schema::hasColumn('collectes', 'beneficiaire_id')) {
                $table->unsignedBigInteger('beneficiaire_id')->nullable()->after('beneficiaire_type');
            }
            $table->index(['beneficiaire_type', 'beneficiaire_id']);
        });

        // 3. Table credits_agricoles
        Schema::table('credits_agricoles', function (Blueprint $table) {
            if (!Schema::hasColumn('credits_agricoles', 'cooperative_id')) {
                $table->foreignId('cooperative_id')->nullable()->after('producteur_id')
                      ->constrained('cooperatives')->onDelete('set null');
            }
            if (!Schema::hasColumn('credits_agricoles', 'beneficiaire_type')) {
                $table->string('beneficiaire_type')->nullable()->after('cooperative_id');
            }
            if (!Schema::hasColumn('credits_agricoles', 'beneficiaire_id')) {
                $table->unsignedBigInteger('beneficiaire_id')->nullable()->after('beneficiaire_type');
            }
            $table->index(['beneficiaire_type', 'beneficiaire_id']);
        });
    }

    public function down(): void
    {
        Schema::table('distributions_semences', function (Blueprint $table) {
            $table->dropForeign(['cooperative_id']);
            $table->dropColumn(['cooperative_id', 'beneficiaire_type', 'beneficiaire_id', 'prix_unitaire', 'montant_total']);
        });

        Schema::table('collectes', function (Blueprint $table) {
            $table->dropForeign(['cooperative_id']);
            $table->dropColumn(['cooperative_id', 'beneficiaire_type', 'beneficiaire_id']);
        });

        Schema::table('credits_agricoles', function (Blueprint $table) {
            $table->dropForeign(['cooperative_id']);
            $table->dropColumn(['cooperative_id', 'beneficiaire_type', 'beneficiaire_id']);
        });
    }
};