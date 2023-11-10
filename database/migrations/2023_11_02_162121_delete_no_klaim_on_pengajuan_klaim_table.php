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
        Schema::table('pengajuan_klaim', function(Blueprint $table) {
            $table->dropIndex('pengajuan_klaim_no_klaim_index');
            $table->dropColumn('no_klaim');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('pengajuan_klaim', function(Blueprint $table) {
            $table->string('no_klaim', 50)->after('id');
            $table->index('no_klaim');
        });
    }
};
