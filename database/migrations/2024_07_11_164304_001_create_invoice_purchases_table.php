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
        Schema::create('invoice_purchases', function (Blueprint $table) {
            $table->id();
            $table->string('image', 255)->nullable();
            $table
                ->bigInteger('payment_type_id')
                ->unsigned()
                ->index();
            $table
                ->bigInteger('store_id')
                ->unsigned()
                ->index();
            $table
                ->bigInteger('supplier_id')
                ->unsigned()
                ->index();
            $table->date('date');
            $table->bigInteger('taxes')->default(0);
            $table->bigInteger('discounts')->default(0);
            $table->bigInteger('total_price');
            $table->tinyInteger('payment_status')->default(1);
            $table->tinyInteger('order_status')->default(1);
            $table->text('notes')->nullable();
            $table
                ->bigInteger('created_by_id')
                ->unsigned()
                ->nullable();
            $table
                ->bigInteger('approved_by_id')
                ->unsigned()
                ->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();

            $table
                ->foreign('payment_type_id')
                ->references('id')
                ->on('payment_types')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table
                ->foreign('store_id')
                ->references('id')
                ->on('stores')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table
                ->foreign('supplier_id')
                ->references('id')
                ->on('suppliers')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table
                ->foreign('created_by_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table
                ->foreign('approved_by_id')
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
        Schema::dropIfExists('invoice_purchases');
    }
};
