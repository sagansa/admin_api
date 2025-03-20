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
        Schema::create('account_cashless_admin_cashless', function (
            Blueprint $table
        ) {
            $table->bigInteger('account_cashless_id')->unsigned();
            $table->bigInteger('admin_cashless_id')->unsigned();

            $table
                ->foreign('account_cashless_id')
                ->references('id')
                ->on('account_cashlesses')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table
                ->foreign('admin_cashless_id')
                ->references('id')
                ->on('admin_cashlesses')
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
        Schema::dropIfExists('account_cashless_admin_cashless');
    }
};
