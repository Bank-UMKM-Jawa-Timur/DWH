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
            $table->index('pengajuan_id');
            $table->index('is_continue_import');
            $table->index('kode_cabang');
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
        Schema::table('kredits', function(Blueprint $table) {
            $table->dropIndex('kredits_pengajuan_id_index');
            $table->index('kredits_is_continue_import_inde');
            $table->index('kredits_kode_cabang_inde');
            $table->index('kredits_created_at_inde');
            $table->index('kredits_updated_at_inde');
        });
    }
};
