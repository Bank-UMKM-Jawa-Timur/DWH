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
        \DB::statement("ALTER TABLE asuransi MODIFY COLUMN status ENUM('waiting approval', 'approved', 'revition', 'sended', 'canceled')");
        Schema::table('asuransi', function(Blueprint $table) {
            $table->text('tanda_terima')->nullable()->after('tanggal_akhir');
            $table->index('tanda_terima');
            $table->bigInteger('penyelia_id', false, true)->nullable()->after('user_id');
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
        \DB::statement("ALTER TABLE asuransi MODIFY COLUMN status ENUM('onprogress', 'canceled', 'done')");
        Schema::table('asuransi', function(Blueprint $table) {
            $table->dropIndex('asuransi_tanda_terima_index');
            $table->dropColumn('tanda_terima');
            $table->dropColumn('penyelia_id');
            $table->dropColumn('registered');
        });
    }
};
