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
        Schema::table('kredits', function (Blueprint $table) {
            $table->boolean('is_asuransi')->default(false)->after('is_continue_import');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('kredits', function (Blueprint $table) {
            $table->dropColumn('is_asuransi');
        });
    }
};
