<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('controleurs', function (Blueprint $table) {
            $table->timestamp('last_login')->nullable()->after('statut');
        });
    }

    public function down(): void
    {
        Schema::table('controleurs', function (Blueprint $table) {
            $table->dropColumn('last_login');
        });
    }
};