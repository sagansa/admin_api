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
        Schema::create('closing_store_daily_salary', function (
            Blueprint $table
        ) {
            $table->bigInteger('closing_store_id')->unsigned();
            $table->bigInteger('daily_salary_id')->unsigned();

            $table
                ->foreign('closing_store_id')
                ->references('id')
                ->on('closing_stores')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table
                ->foreign('daily_salary_id')
                ->references('id')
                ->on('daily_salaries')
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
        Schema::dropIfExists('closing_store_daily_salary');
    }
};
