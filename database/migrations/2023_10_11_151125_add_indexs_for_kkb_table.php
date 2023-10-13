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
            $table->index('user_id');
            $table->index('tgl_ketersediaan_unit');
            $table->index('nominal_realisasi');
            $table->index('nominal_dp');
            $table->index('nominal_imbal_jasa');
            $table->index('nominal_pembayaran_imbal_jasa');
            $table->index('created_at');
            $table->index('updated_at');
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
            $table->dropIndex('kkb_user_id_index');
            $table->dropIndex('kkb_tgl_ketersediaan_unit_index');
            $table->dropIndex('kkb_nominal_realisasi_index');
            $table->dropIndex('kkb_nominal_dp_index');
            $table->dropIndex('kkb_nominal_imbal_jasa_index');
            $table->dropIndex('kkb_nominal_pembayaran_imbal_jasa_index');
            $table->dropIndex('kkb_created_at_index');
            $table->dropIndex('kkb_updated_at_index');
        });
    }
};
