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
        Schema::table('log_activities', function (Blueprint $table) {
            $table->bigInteger('asuransi_id')->nullable()->after('id');
            $table->boolean('is_asuransi')->nullable(false)->after('content');

            $table->foreign('asuransi_id')->references('id')->on('asuransi')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('log_activities', function (Blueprint $table) {
            $table->dropForeign('log_activities_asuransi_id_foreign');
            $table->dropColumn('asuransi_id');
            $table->dropColumn('is_asuransi');
        });
    }
};
