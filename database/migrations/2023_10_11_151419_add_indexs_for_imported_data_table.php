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
        Schema::table('imported_data', function(Blueprint $table) {
            $table->index('name');
            $table->index('tgl_po');
            $table->index('tgl_realisasi');
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
        Schema::table('imported_data', function(Blueprint $table) {
            $table->dropIndex('imported_data_name_index');
            $table->dropIndex('imported_data_tgl_po_index');
            $table->dropIndex('imported_data_tgl_realisasi_index');
            $table->dropIndex('imported_data_created_at_index');
            $table->dropIndex('imported_data_updated_at_index');
        });
    }
};
