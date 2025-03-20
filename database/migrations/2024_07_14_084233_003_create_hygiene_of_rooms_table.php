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
        Schema::create('hygiene_of_rooms', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('hygiene_id')->unsigned();
            $table->bigInteger('room_id')->unsigned();
            $table->string('image')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();

            $table
                ->foreign('hygiene_id')
                ->references('id')
                ->on('hygienes')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table
                ->foreign('room_id')
                ->references('id')
                ->on('rooms')
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
        Schema::dropIfExists('hygiene_of_rooms');
    }
};
