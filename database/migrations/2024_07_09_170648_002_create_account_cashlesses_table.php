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
        Schema::create('account_cashlesses', function (Blueprint $table) {
            $table->id();
            $table
                ->bigInteger('cashless_provider_id')
                ->unsigned()
                ->index();
            $table
                ->bigInteger('store_id')
                ->unsigned()
                ->index();
            $table
                ->bigInteger('store_cashless_id')
                ->unsigned()
                ->index();
            $table->string('email', 255)->nullable();
            $table->string('username', 255)->nullable();
            $table->string('password', 255)->nullable();
            $table->string('no_telp', 255)->nullable();
            $table->tinyInteger('status');
            $table->text('notes')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();

            $table
                ->foreign('cashless_provider_id')
                ->references('id')
                ->on('cashless_providers')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table
                ->foreign('store_id')
                ->references('id')
                ->on('stores')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table
                ->foreign('store_cashless_id')
                ->references('id')
                ->on('store_cashlesses')
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
        Schema::dropIfExists('account_cashlesses');
    }
};
