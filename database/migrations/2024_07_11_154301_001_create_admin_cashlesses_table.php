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
        Schema::create('admin_cashlesses', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('cashless_provider_id')->unsigned();
            $table->string('username')->nullable();
            $table->string('email')->nullable();
            $table->string('no_telp')->nullable();
            $table->string('password')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();

            $table
                ->foreign('cashless_provider_id')
                ->references('id')
                ->on('cashless_providers')
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
        Schema::dropIfExists('admin_cashlesses');
    }
};
