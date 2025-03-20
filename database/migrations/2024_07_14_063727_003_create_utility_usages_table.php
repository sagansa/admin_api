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
        Schema::create('utility_usages', function (Blueprint $table) {
            $table->id();
            $table->string('image', 255)->nullable();
            $table
                ->bigInteger('utility_id')
                ->unsigned()
                ->index();
            $table->decimal('result');
            $table->tinyInteger('status');
            $table->text('notes')->nullable();
            $table
                ->bigInteger('created_by_id')
                ->unsigned()
                ->index();
            $table
                ->bigInteger('approved_by_id')
                ->unsigned()
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
                ->foreign('utility_id')
                ->references('id')
                ->on('utilities')
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
        Schema::dropIfExists('utility_usages');
    }
};
