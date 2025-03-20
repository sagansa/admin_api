<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('movement_asset_audits', function (Blueprint $table) {
            $table->id();
            $table->string('image')->nullable();
            $table->bigInteger('movement_asset_id')->unsigned();
            $table->integer('good_cond_qty');
            $table->integer('bad_cond_qty');
            $table->bigInteger('movement_asset_result_id')->unsigned();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();

            $table
                ->foreign('movement_asset_id')
                ->references('id')
                ->on('movement_assets')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table
                ->foreign('movement_asset_result_id')
                ->references('id')
                ->on('movement_asset_results')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('movement_asset_audits');
    }
};
