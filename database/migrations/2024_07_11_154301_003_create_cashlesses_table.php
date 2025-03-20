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
        Schema::create('cashlesses', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('account_cashless_id')->unsigned();
            $table->string('image')->nullable();
            $table->bigInteger('bruto_apl');
            $table->bigInteger('netto_apl')->nullable();
            $table->bigInteger('bruto_real')->nullable();
            $table->bigInteger('netto_real')->nullable();
            $table->string('image_canceled')->nullable();
            $table->integer('canceled');
            $table->bigInteger('closing_store_id')->unsigned();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();

            $table
                ->foreign('account_cashless_id')
                ->references('id')
                ->on('account_cashlesses')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table
                ->foreign('closing_store_id')
                ->references('id')
                ->on('closing_stores')
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
        Schema::dropIfExists('cashlesses');
    }
};
