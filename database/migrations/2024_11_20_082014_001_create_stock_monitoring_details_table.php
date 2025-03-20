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
        Schema::create('stock_monitoring_details', function (Blueprint $table) {
            $table->id();
            $table
                ->bigInteger('product_id')
                ->unsigned()
                ->index();
            $table
                ->bigInteger('stock_monitoring_id')
                ->unsigned()
                ->index();
            $table->integer('coefisien');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();

            $table
                ->foreign('product_id')
                ->references('id')
                ->on('products')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table
                ->foreign('stock_monitoring_id')
                ->references('id')
                ->on('stock_monitorings')
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
        Schema::dropIfExists('stock_monitoring_details');
    }
};
