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
        \DB::statement("ALTER TABLE mst_jenis_asuransi MODIFY COLUMN jenis ENUM('Jaminan', 'Jiwa', 'Kredit(Penjaminan)')");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mst_jenis_asuransi', function(Blueprint $table) {
            $table->string('jenis', 20)->change();
        });
    }
};
