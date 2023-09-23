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
        Schema::table('kredits', function(Blueprint $table) {
            $table->bigInteger('pengajuan_id', false, true)->nullable()->change();
            $table->bigInteger('imported_data_id', false, true)->nullable()->after('pengajuan_id');
            $table->foreign('imported_data_id')->references('id')->on('imported_data')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kredits', function(Blueprint $table) {
            $table->bigInteger('pengajuan_id', false, true)->nullable(false)->change();
            $table->dropForeign('kredits_imported_data_id_foreign');
            $table->dropColumn('imported_data_id');
        });
    }
};
