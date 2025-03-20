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
        Schema::create('readinesses', function (Blueprint $table) {
            $table->id();
            $table->string('image_selfie', 255)->nullable();
            $table->string('left_hand', 255)->nullable();
            $table->string('right_hand', 255)->nullable();
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
        Schema::dropIfExists('readinesses');
    }
};
