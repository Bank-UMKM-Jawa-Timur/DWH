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
        Schema::table('kkb', function (Blueprint $table) {
            $table->bigInteger('id_tenor_imbal_jasa', false, true)->nullable()->change();
            $table->bigInteger('nominal_realisasi', false, true)->nullable()->after('id_tenor_imbal_jasa');
            $table->bigInteger('nominal_dp', false, true)->nullable()->after('nominal_realisasi');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kkb', function (Blueprint $table) {
            $table->bigInteger('id_tenor_imbal_jasa', false, true)->nullable(false)->change();
            $table->dropColumn('nominal_realisasi');
            $table->dropColumn('nominal_dp');
        });
    }
};
