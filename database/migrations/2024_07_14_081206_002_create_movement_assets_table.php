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
        Schema::create('movement_assets', function (Blueprint $table) {
            $table->id();
            $table->string('image')->nullable();
            $table->string('qr_code')->nullable();
            $table->bigInteger('product_id')->unsigned();
            $table->integer('good_cond_qty');
            $table->integer('bad_cond_qty');
            $table->bigInteger('store_asset_id')->unsigned();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();

            $table
                ->foreign('product_id')
                ->references('id')
                ->on('products')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table
                ->foreign('store_asset_id')
                ->references('id')
                ->on('store_assets')
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
        Schema::dropIfExists('movement_assets');
    }
};
