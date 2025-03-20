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
        Schema::create('detail_transfer_cards', function (Blueprint $table) {
            $table->id();
            $table
                ->bigInteger('product_id')
                ->unsigned()
                ->index();
            $table->integer('quantity');
            $table->bigInteger('transfer_card_id')->unsigned();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();

            $table
                ->foreign('product_id')
                ->references('id')
                ->on('products')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table
                ->foreign('transfer_card_id')
                ->references('id')
                ->on('transfer_cards')
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
        Schema::dropIfExists('detail_transfer_cards');
    }
};
