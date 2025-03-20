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
        Schema::create('payment_receipts', function (Blueprint $table) {
            $table->id();
            $table->string('image', 255)->nullable();
            $table
                ->bigInteger('supplier_id')
                ->unsigned()
                ->nullable();
            $table
                ->bigInteger('user_id')
                ->unsigned()
                ->nullable();
            $table->bigInteger('total_amount');
            $table->bigInteger('transfer_amount');
            $table->tinyInteger('payment_for');
            $table->string('image_adjust', 255)->nullable();
            $table->text('notes')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();

            $table
                ->foreign('supplier_id')
                ->references('id')
                ->on('suppliers')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table
                ->foreign('user_id')
                ->references('id')
                ->on('users')
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
        Schema::dropIfExists('payment_receipts');
    }
};
