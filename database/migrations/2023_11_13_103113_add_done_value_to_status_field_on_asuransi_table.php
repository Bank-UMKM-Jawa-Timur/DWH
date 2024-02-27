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
        \DB::statement("ALTER TABLE asuransi MODIFY COLUMN status ENUM('waiting approval', 'approved', 'revition', 'sended', 'canceled', 'done') DEFAULT 'waiting approval'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::statement("ALTER TABLE asuransi MODIFY COLUMN status ENUM('waiting approval', 'approved', 'revition', 'sended', 'canceled') DEFAULT 'waiting approval'");
    }
};
