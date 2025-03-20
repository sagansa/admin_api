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
        Schema::create('closing_store_invoice_purchase', function (
            Blueprint $table
        ) {
            $table->bigInteger('invoice_purchase_id')->unsigned();
            $table->bigInteger('closing_store_id')->unsigned();

            $table
                ->foreign('invoice_purchase_id')
                ->references('id')
                ->on('invoice_purchases')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table
                ->foreign('closing_store_id')
                ->references('id')
                ->on('closing_stores')
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
        Schema::dropIfExists('closing_store_invoice_purchase');
    }
};
