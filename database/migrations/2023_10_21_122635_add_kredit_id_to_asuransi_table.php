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
            $table->bigInteger('kredit_id', false, true)->after('no_aplikasi');
            $table->foreign('kredit_id')->references('id')->on('kredits')->cascadeOnDelete();
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
            $table->dropForeign('asuransi_kredit_id_foreign');
            $table->dropColumn('kredit_id');
        });
    }
};
