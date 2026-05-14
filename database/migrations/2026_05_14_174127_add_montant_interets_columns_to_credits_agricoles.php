<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('credits_agricoles', function (Blueprint $table) {
            if (!Schema::hasColumn('credits_agricoles', 'montant_sans_interets')) {
                $table->decimal('montant_sans_interets', 12, 2)->nullable()->after('montant_total');
            }
            if (!Schema::hasColumn('credits_agricoles', 'montant_interets')) {
                $table->decimal('montant_interets', 12, 2)->nullable()->after('montant_sans_interets');
            }
        });
    }

    public function down(): void
    {
        Schema::table('credits_agricoles', function (Blueprint $table) {
            $table->dropColumn(['montant_sans_interets', 'montant_interets']);
        });
    }
};