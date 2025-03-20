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
        Schema::create('utility_bills', function (Blueprint $table) {
            $table->id();
            $table
                ->bigInteger('utility_id')
                ->unsigned()
                ->index();
            $table->string('image')->nullable();
            $table->date('date');
            $table->bigInteger('amount');
            $table->bigInteger('initial_indicator');
            $table->bigInteger('last_indicator');
            $table
                ->bigInteger('user_id')
                ->unsigned()
                ->index();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();

            $table
                ->foreign('utility_id')
                ->references('id')
                ->on('utilities')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table
                ->foreign('user_id')
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
        Schema::dropIfExists('utility_bills');
    }
};
