<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('estimation_besoins', function (Blueprint $table) {
            $table->decimal('cout_semences', 15, 2)->nullable()->after('credit_montant');
            $table->decimal('cout_intrants', 15, 2)->nullable()->after('cout_semences');
            $table->decimal('autres_frais', 15, 2)->nullable()->after('cout_intrants');
            $table->decimal('total_estimation', 15, 2)->nullable()->after('autres_frais');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('estimation_besoins', function (Blueprint $table) {
            $table->dropColumn(['cout_semences', 'cout_intrants', 'autres_frais', 'total_estimation']);
        });
    }
};