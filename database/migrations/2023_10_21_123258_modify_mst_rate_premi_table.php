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
        Schema::table('mst_rate_premi', function(Blueprint $table) {
            $table->smallInteger('masa_asuransi', false, true)
                ->comment('bulan')->default(0)->change();
            $table->smallInteger('masa_asuransi2', false, true)
                ->comment('bulan')->default(0)->after('masa_asuransi');
            $table->renameColumn('masa_asuransi', 'masa_asuransi1');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mst_rate_premi', function(Blueprint $table) {
            $table->smallInteger('masa_asuransi1', false, true)->comment('bulan')->change();
            $table->dropColumn('masa_asuransi2');
            $table->renameColumn('masa_asuransi1', 'masa_asuransi');
        });
    }
};
