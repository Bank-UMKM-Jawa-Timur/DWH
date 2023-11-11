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
        Schema::table('mst_perusahaan_asuransi', function (Blueprint $table) {
            $table->text('endpoint')->nullable()->after('telp');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mst_perusahaan_asuransi', function (Blueprint $table) {
            $table->dropColumn('endpoint');
        });
    }
};
