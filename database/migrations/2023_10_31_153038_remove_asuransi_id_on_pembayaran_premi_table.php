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
            $table->dropForeign('pembayaran_premi_asuransi_id_foreign');
            $table->dropColumn('asuransi_id');
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
            $table->bigInteger('asuransi_id', false, true)->after('id');
            $table->foreign('asuransi_id')->references('id')->on('asuransi')->cascadeOnDelete();
        });
    }
};
