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
        Schema::table('kkb', function (Blueprint $table) {
            $table->renameColumn('tgl_ketersedian_unit', 'tgl_ketersediaan_unit');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kkb', function (Blueprint $table) {
            $table->renameColumn('tgl_ketersediaan_unit', 'tgl_ketersedian_unit');
        });
    }
};
