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
        Schema::create('mst_form_asuransi', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('perusahaan_id', false, true);
            $table->smallInteger('level', false, true);
            $table->bigInteger('parent_id', false, true)->nullable();
            $table->enum('type', ['text', 'number', 'option', 'radio', 'file', 'email', 'password'])
                    ->nullable()
                    ->comment("");
            $table->text('formula')->nullable();
            $table->integer('sequence', false, true)->unique();
            $table->boolean('have_default_value')
                    ->default(false)
                    ->comment('Value from other process, if this field value true = field readonly will be true');
            $table->boolean('rupiah')->default(false);
            $table->boolean('readonly')->default(false);
            $table->boolean('hidden')->default(false);
            $table->boolean('disabled')->default(false);
            $table->boolean('required')->default(false);
            $table->timestamps();

            $table->foreign('perusahaan_id')
                    ->references('id')
                    ->on('mst_perusahaan_asuransi')
                    ->cascadeOnDelete();

            // Create index
            $table->index('level');
            $table->index('parent_id');
            $table->index('type');
            $table->index('formula');
            $table->index('sequence');
            $table->index('rupiah');
            $table->index('readonly');
            $table->index('hidden');
            $table->index('disabled');
            $table->index('required');
            $table->index('created_at');
            $table->index('updated_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mst_form_asuransi');
    }
};
