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
        Schema::table('asuransi', function(Blueprint $table) {
            $table->dropUnique('asuransi_no_aplikasi_unique');
            $table->id();
            $table->bigInteger('jenis_asuransi_id', false, true)->after('no_aplikasi');
            $table->foreign('jenis_asuransi_id')->references('id')->on('mst_jenis_asuransi');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('asuransi', function(Blueprint $table) {
            $table->dropPrimary();
            $table->unsignedBigInteger('id');
            $table->dropForeign('asuransi_jenis_asuransi_id_foreign');
            $table->dropColumn('jenis_asuransi_id');
        });
    }
};
