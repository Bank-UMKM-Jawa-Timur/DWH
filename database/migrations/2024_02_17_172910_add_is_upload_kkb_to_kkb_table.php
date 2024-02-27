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
            $table->enum('is_upload_kkb',['vendor','cabang'])->nullable()->after('user_id');
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
            $table->enum('is_upload_kkb',['vendor','cabang'])->nullable()->after('user_id');
        });
    }
};
