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
        Schema::table('actualites', function (Blueprint $table) {
            $table->string('image_couverture_public_id')->nullable()->after('image_couverture');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('actualites', function (Blueprint $table) {
            $table->dropColumn('image_couverture_public_id');
        });
    }
};