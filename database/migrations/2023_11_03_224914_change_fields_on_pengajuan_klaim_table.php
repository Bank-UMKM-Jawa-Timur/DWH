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
        \DB::statement("ALTER TABLE pengajuan_klaim MODIFY COLUMN status ENUM('waiting approval', 'approved', 'revition', 'sended', 'canceled')");
        Schema::table('pengajuan_klaim', function(Blueprint $table) {
            $table->boolean('registered')
                    ->nullable()
                    ->comment('null= belum memilih registrasi ato tidak registrasi, true = memilih registrasi, false = memilih tidak registrasi');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::statement("ALTER TABLE pengajuan_klaim MODIFY COLUMN status ENUM('onprogress', 'canceled')");
        Schema::table('pengajuan_klaim', function(Blueprint $table) {
            $table->dropColumn('registered');
        });
    }
};
