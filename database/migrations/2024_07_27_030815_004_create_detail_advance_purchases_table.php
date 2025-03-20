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
        Schema::create('detail_advance_purchases', function (Blueprint $table) {
            $table->id();
            $table
                ->bigInteger('product_id')
                ->unsigned()
                ->index();
            $table->integer('quantity');
            $table->bigInteger('price');
            $table->bigInteger('unit_price');
            $table
                ->bigInteger('advance_purchase_id')
                ->unsigned()
                ->index();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();

            $table
                ->foreign('advance_purchase_id')
                ->references('id')
                ->on('advance_purchases')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table
                ->foreign('product_id')
                ->references('id')
                ->on('products')
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
        Schema::dropIfExists('detail_advance_purchases');
    }
};
