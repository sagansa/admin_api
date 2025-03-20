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
        Schema::create('vehicle_taxes', function (Blueprint $table) {
            $table->id();
            $table->string('image')->nullable();
            $table->bigInteger('amount_tax');
            $table->bigInteger('vehicle_id')->unsigned();
            $table->date('expired_date');
            $table->text('notes')->nullable();
            $table
                ->bigInteger('user_id')
                ->unsigned()
                ->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();

            $table
                ->foreign('vehicle_id')
                ->references('id')
                ->on('vehicles')
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
        Schema::dropIfExists('vehicle_taxes');
    }
};
