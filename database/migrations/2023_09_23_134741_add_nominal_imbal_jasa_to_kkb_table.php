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
            $table->bigInteger('nominal_imbal_jasa', false, true)->nullable()->after('nominal_dp');
            $table->bigInteger('nominal_pembayaran_imbal_jasa', false, true)->nullable()->after('nominal_imbal_jasa');
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
            $table->dropColumn('nominal_imbal_jasa');
            $table->dropColumn('nominal_pembayaran_imbal_jasa');
        });
    }
};
