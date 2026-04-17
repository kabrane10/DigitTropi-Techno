<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('producteurs', function (Blueprint $table) {
            $table->foreignId('agent_terrain_id')->nullable()->constrained('agents_terrain')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('producteurs', function (Blueprint $table) {
            $table->dropForeign(['agent_terrain_id']);
            $table->dropColumn('agent_terrain_id');
        });
    }
};