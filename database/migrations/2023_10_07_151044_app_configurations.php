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
        Schema::create('app_configurations', function (Blueprint $table) {
            $table->id();
            $table->text('pusher_app_id')->nullable();
            $table->text('pusher_app_key')->nullable();
            $table->text('pusher_app_secret')->nullable();
            $table->text('pusher_cluster')->nullable();
            $table->text('los_host')->nullable();
            $table->text('los_api_host')->nullable();
            $table->text('los_asset_url')->nullable();
            $table->text('bio_interface_api_host')->nullable();
            $table->text('collection_api_host')->nullable();
            $table->text('microsoft_graph_client_id')->nullable();
            $table->text('microsoft_graph_client_secret')->nullable();
            $table->text('microsoft_graph_tenant_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
