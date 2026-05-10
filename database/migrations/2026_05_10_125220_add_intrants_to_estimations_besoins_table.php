<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('estimation_besoins', function (Blueprint $table) {
            $table->json('intrants')->nullable()->after('credit_montant');
        });
    }

    public function down(): void
    {
        Schema::table('estimation_besoins', function (Blueprint $table) {
            $table->dropColumn('intrants');
        });
    }
};