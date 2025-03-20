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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table
                ->bigInteger('unit_id')
                ->unsigned()
                ->index();
            $table->string('slug', 255);
            $table->string('sku', 255)->nullable();
            $table->string('barcode', 255)->nullable();
            $table->text('description')->nullable();
            $table->string('image', 255)->nullable();
            $table->tinyInteger('request');
            $table->tinyInteger('remaining');
            $table
                ->bigInteger('payment_type_id')
                ->unsigned()
                ->index();
            $table
                ->bigInteger('material_group_id')
                ->unsigned()
                ->index();
            $table
                ->bigInteger('online_category_id')
                ->unsigned()
                ->index();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table
                ->bigInteger('user_id')
                ->unsigned()
                ->index();
            $table->timestamp('deleted_at')->nullable();

            $table
                ->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table
                ->foreign('unit_id')
                ->references('id')
                ->on('units')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table
                ->foreign('payment_type_id')
                ->references('id')
                ->on('payment_types')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table
                ->foreign('material_group_id')
                ->references('id')
                ->on('material_groups')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table
                ->foreign('online_category_id')
                ->references('id')
                ->on('online_categories')
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
        Schema::dropIfExists('products');
    }
};
