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
        Schema::create('closing_stores', function (Blueprint $table) {
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
            $table->bigInteger('cash_from_yesterday');
            $table->bigInteger('cash_for_tomorrow');
            $table
                ->bigInteger('transfer_by_id')
                ->unsigned()
                ->nullable()
                ->index();
            $table->bigInteger('total_cash_transfer');
            $table->tinyInteger('status');
            $table->text('notes')->nullable();
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
                ->foreign('approved_by_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table
                ->foreign('created_by_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table
                ->foreign('shift_store_id')
                ->references('id')
                ->on('shift_stores')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table
                ->foreign('store_id')
                ->references('id')
                ->on('stores')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table
                ->foreign('transfer_by_id')
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
        Schema::dropIfExists('closing_stores');
    }
};
