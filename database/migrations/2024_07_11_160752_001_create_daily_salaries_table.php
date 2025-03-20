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
        Schema::create('daily_salaries', function (Blueprint $table) {
            $table->id();
            $table
                ->bigInteger('store_id')
                ->unsigned()
                ->index();
            $table
                ->bigInteger('shift_store_id')
                ->unsigned()
                ->index();
            $table->date('date');
            $table->bigInteger('amount');
            $table
                ->bigInteger('payment_type_id')
                ->unsigned()
                ->index();
            $table->tinyInteger('status');
            $table
                ->bigInteger('created_by_id')
                ->unsigned()
                ->nullable()
                ->index();
            $table
                ->bigInteger('approved_by_id')
                ->unsigned()
                ->nullable()
                ->index();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();

            $table
                ->foreign('store_id')
                ->references('id')
                ->on('stores')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table
                ->foreign('shift_store_id')
                ->references('id')
                ->on('shift_stores')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table
                ->foreign('payment_type_id')
                ->references('id')
                ->on('payment_types')
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
        Schema::dropIfExists('daily_salaries');
    }
};
