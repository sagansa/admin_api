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
        Schema::create('advance_purchases', function (Blueprint $table) {
            $table->id();
            $table->string('image', 255)->nullable();
            $table
                ->bigInteger('cash_advance_id')
                ->unsigned()
                ->nullable()
                ->index();
            $table
                ->bigInteger('supplier_id')
                ->unsigned()
                ->index();
            $table
                ->bigInteger('store_id')
                ->unsigned()
                ->index();
            $table->date('date');
            $table->bigInteger('subtotal_price');
            $table->bigInteger('discount_price');
            $table->bigInteger('total_price');
            $table
                ->bigInteger('user_id')
                ->unsigned()
                ->index();
            $table->tinyInteger('status')->default(1);
            $table->text('notes')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();

            $table
                ->foreign('store_id')
                ->references('id')
                ->on('stores')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table
                ->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table
                ->foreign('supplier_id')
                ->references('id')
                ->on('suppliers')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table
                ->foreign('cash_advance_id')
                ->references('id')
                ->on('cash_advances')
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
        Schema::dropIfExists('advance_purchases');
    }
};
