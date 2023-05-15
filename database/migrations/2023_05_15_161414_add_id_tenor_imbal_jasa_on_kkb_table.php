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
        Schema::table('kkb', function(Blueprint $table) {
            $table->dropColumn('imbal_jasa');
            $table->bigInteger('id_tenor_imbal_jasa', false, true)->nullable()->after('tgl_ketersediaan_unit');

            $table->foreign('id_tenor_imbal_jasa')->references('id')->on('tenor_imbal_jasas');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kkb', function(Blueprint $table) {
            $table->string('imbal_jasa', 12)->nullable()->after('tgl_ketersediaan_unit');
            $table->dropForeign('kkb_id_tenor_imbal_jasa_foreign');
            $table->dropColumn('id_tenor_imbal_jasa');
        });
    }
};
