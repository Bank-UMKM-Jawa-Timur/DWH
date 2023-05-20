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
        Schema::table('target', function (Blueprint $table) {
            $table->string('nominal', 12)->nullable()->change();
            $table->smallInteger('total_unit', false, true)->after('nominal');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('target', function (Blueprint $table) {
            $table->string('nominal', 12)->nullable(false)->change();
            $table->dropColumn('total_unit');
        });
    }
};
