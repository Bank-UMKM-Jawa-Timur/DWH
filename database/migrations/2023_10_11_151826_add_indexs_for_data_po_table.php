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
        Schema::table('data_po', function(Blueprint $table) {
            $table->index('merk');
            $table->index('tipe');
            $table->index('tahun_kendaraan');
            $table->index('warna');
            $table->index('keterangan');
            $table->index('jumlah');
            $table->index('harga');
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
        Schema::table('data_po', function(Blueprint $table) {
            $table->dropIndex('merk');
            $table->dropIndex('tipe');
            $table->dropIndex('tahun_kendaraan');
            $table->dropIndex('warna');
            $table->dropIndex('keterangan');
            $table->dropIndex('jumlah');
            $table->dropIndex('harga');
            $table->dropIndex('created_at');
            $table->dropIndex('updated_at');
        });
    }
};
