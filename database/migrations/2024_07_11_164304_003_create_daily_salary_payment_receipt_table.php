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
        Schema::create('daily_salary_payment_receipt', function (
            Blueprint $table
        ) {
            $table->bigInteger('daily_salary_id')->unsigned();
            $table->bigInteger('payment_receipt_id')->unsigned();

            $table
                ->foreign('daily_salary_id')
                ->references('id')
                ->on('daily_salaries')
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
        Schema::dropIfExists('daily_salary_payment_receipt');
    }
};
