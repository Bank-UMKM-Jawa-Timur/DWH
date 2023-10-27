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
        Schema::table('asuransi', function (Blueprint $table) {
            $table->date('tanggal_awal')->after('tgl_rekam');
            $table->date('tanggal_akhir')->after('tanggal_awal');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('asuransi', function (Blueprint $table) {
            $table->dropColumn('tanggal_awal');
            $table->dropColumn('tanggal_kredit');
        });
    }
};
