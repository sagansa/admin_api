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
        Schema::create('fuel_service_payment_receipt', function (
            Blueprint $table
        ) {
            $table
                ->bigInteger('fuel_service_id')
                ->unsigned()
                ->index();
            $table
                ->bigInteger('payment_receipt_id')
                ->unsigned()
                ->nullable()
                ->index();

            $table
                ->foreign('fuel_service_id')
                ->references('id')
                ->on('fuel_services')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table
                ->foreign('payment_receipt_id')
                ->references('id')
                ->on('payment_receipts')
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
        Schema::dropIfExists('fuel_service_payment_receipt');
    }
};
