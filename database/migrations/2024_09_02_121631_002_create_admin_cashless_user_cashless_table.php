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
        Schema::create('admin_cashless_user_cashless', function (
            Blueprint $table
        ) {
            $table->bigInteger('admin_cashless_id')->unsigned();
            $table->bigInteger('user_cashless_id')->unsigned();

            $table
                ->foreign('admin_cashless_id')
                ->references('id')
                ->on('admin_cashlesses')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table
                ->foreign('user_cashless_id')
                ->references('id')
                ->on('user_cashlesses')
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
        Schema::dropIfExists('admin_cashless_user_cashless');
    }
};
