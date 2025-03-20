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
        Schema::create('utilities', function (Blueprint $table) {
            $table->id();
            $table->string('number')->unique();
            $table->string('name');
            $table->bigInteger('store_id')->unsigned();
            $table->tinyInteger('category');
            $table->bigInteger('unit_id')->unsigned();
            $table->bigInteger('utility_provider_id')->unsigned();
            $table->tinyInteger('pre_post');
            $table->tinyInteger('status');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();

            $table
                ->foreign('store_id')
                ->references('id')
                ->on('stores')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table
                ->foreign('unit_id')
                ->references('id')
                ->on('units')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table
                ->foreign('utility_provider_id')
                ->references('id')
                ->on('utility_providers')
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
        Schema::dropIfExists('utilities');
    }
};
