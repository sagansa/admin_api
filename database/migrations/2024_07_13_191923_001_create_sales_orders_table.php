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
        Schema::create('sales_orders', function (Blueprint $table) {
            $table->id();
            $table->enum('for', ['1', '2', '3']);
            $table->date('delivery_date');
            $table
                ->bigInteger('online_shop_provider_id')
                ->unsigned()
                ->nullable()
                ->index();
            $table
                ->bigInteger('delivery_service_id')
                ->unsigned()
                ->nullable()
                ->index();
            $table
                ->bigInteger('delivery_address_id')
                ->unsigned()
                ->nullable()
                ->index();
            $table
                ->bigInteger('transfer_to_account_id')
                ->unsigned()
                ->nullable()
                ->index();
            $table->string('image_payment', 255)->nullable();
            $table->tinyInteger('payment_status');
            $table->tinyInteger('delivery_status');
            $table->bigInteger('shipping_cost')->nullable();
            $table
                ->bigInteger('store_id')
                ->unsigned()
                ->nullable()
                ->index();
            $table->string('receipt_no', 255)->nullable();
            $table->string('image_delivery', 255)->nullable();
            $table
                ->bigInteger('ordered_by_id')
                ->unsigned()
                ->nullable()
                ->index();
            $table
                ->bigInteger('assigned_by_id')
                ->unsigned()
                ->nullable()
                ->index();
            $table->text('notes')->nullable();
            $table->bigInteger('total_price');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();

            $table
                ->foreign('assigned_by_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table
                ->foreign('delivery_address_id')
                ->references('id')
                ->on('delivery_addresses')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table
                ->foreign('delivery_service_id')
                ->references('id')
                ->on('delivery_services')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table
                ->foreign('online_shop_provider_id')
                ->references('id')
                ->on('online_shop_providers')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table
                ->foreign('ordered_by_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table
                ->foreign('store_id')
                ->references('id')
                ->on('stores')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table
                ->foreign('transfer_to_account_id')
                ->references('id')
                ->on('transfer_to_accounts')
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
        Schema::dropIfExists('sales_orders');
    }
};
