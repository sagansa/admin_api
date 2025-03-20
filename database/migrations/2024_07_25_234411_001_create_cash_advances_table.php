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
        Schema::create('cash_advances', function (Blueprint $table) {
            $table->id();
            $table->string('image', 255)->nullable();
            $table->date('date');
            $table->bigInteger('transfer');
            $table
                ->bigInteger('user_id')
                ->unsigned()
                ->index();
            $table->bigInteger('before');
            $table->bigInteger('purchase');
            $table->bigInteger('remains');
            $table->tinyInteger('status');
            $table->text('notes')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();

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
        Schema::dropIfExists('cash_advances');
    }
};
