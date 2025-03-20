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
        Schema::create('transfer_cards', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('image', 255)->nullable();
            $table->string('status', 255);
            $table
                ->bigInteger('from_store_id')
                ->unsigned()
                ->index();
            $table
                ->bigInteger('to_store_id')
                ->unsigned()
                ->index();
            $table
                ->bigInteger('sent_by_id')
                ->unsigned()
                ->index();
            $table
                ->bigInteger('received_by_id')
                ->unsigned()
                ->index();
            $table->enum('for', ['store', 'storage']);
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();

            $table
                ->foreign('from_store_id')
                ->references('id')
                ->on('stores')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table
                ->foreign('to_store_id')
                ->references('id')
                ->on('stores')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table
                ->foreign('sent_by_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table
                ->foreign('received_by_id')
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
        Schema::dropIfExists('transfer_cards');
    }
};
