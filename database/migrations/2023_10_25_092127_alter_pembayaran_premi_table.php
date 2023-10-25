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
        Schema::table('pembayaran_premi', function(Blueprint $table) {
            $table->dropIndex('pembayaran_premi_no_rek_index');
            $table->dropIndex('pembayaran_premi_periode_bayar_index');
            $table->dropIndex('pembayaran_premi_total_periode_index');
            $table->dropColumn('no_rek');
            $table->dropColumn('no_pk');
            $table->dropColumn('periode_bayar');
            $table->dropColumn('total_periode');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pembayaran_premi', function(Blueprint $table) {
            $table->string('no_rek', 26)->after('total_premi');
            $table->string('no_pk', 30)->after('no_rek');
            $table->integer('periode_bayar', false, true)->after('no_pk');
            $table->integer('total_periode', false, true)->after('total_periode');
            
            $table->index('no_rek');
            $table->index('periode_bayar');
            $table->index('total_periode');
        });
    }
};
