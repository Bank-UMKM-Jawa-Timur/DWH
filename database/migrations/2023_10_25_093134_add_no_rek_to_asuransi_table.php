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
            $table->string('no_rek', 100)->after('no_pk');
            $table->index('no_rek');
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
            $table->dropIndex('asuransi_no_rek_index');
            $table->dropColumn('no_rek');
        });
    }
};
