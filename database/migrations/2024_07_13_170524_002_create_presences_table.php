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
        Schema::create('presences', function (Blueprint $table) {
            $table->id();
            $table
                ->bigInteger('store_id')
                ->unsigned()
                ->index();
            $table
                ->bigInteger('shift_store_id')
                ->unsigned()
                ->index();
            $table->tinyInteger('status');
            $table->string('image_in', 255)->nullable();
            $table->dateTime('start_date_time');
            $table->double('latitude_in');
            $table->double('longitude_in');
            $table->string('image_out', 255)->nullable();
            $table->dateTime('end_date_time')->nullable();
            $table->double('latitude_out')->nullable();
            $table->double('longitude_out')->nullable();
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
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('presences');
    }
};
