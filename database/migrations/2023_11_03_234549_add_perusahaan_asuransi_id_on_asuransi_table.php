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
            $table->bigInteger('perusahaan_asuransi_id', false, true)->after('id');
            $table->foreign('perusahaan_asuransi_id')->references('id')->on('mst_perusahaan_asuransi')->cascadeOnDelete();
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
            $table->dropForeign('asuransi_perusahaan_asuransi_id_foreign');
            $table->dropColumn('perusahaan_asuransi_id');
        });
    }
};
