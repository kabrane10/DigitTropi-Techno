<?php
// database/migrations/2024_01_01_000008_fix_cooperative_id_nullable_in_credits_agricoles.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('credits_agricoles', function (Blueprint $table) {
            // Rendre cooperative_id nullable
            $table->foreignId('cooperative_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('credits_agricoles', function (Blueprint $table) {
            $table->foreignId('cooperative_id')->nullable(false)->change();
        });
    }
};