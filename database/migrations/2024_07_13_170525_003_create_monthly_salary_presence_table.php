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
        Schema::create('monthly_salary_presence', function (Blueprint $table) {
            $table->bigInteger('presence_id')->unsigned();
            $table->bigInteger('monthly_salary_id')->unsigned();

            $table
                ->foreign('presence_id')
                ->references('id')
                ->on('presences')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table
                ->foreign('monthly_salary_id')
                ->references('id')
                ->on('monthly_salaries')
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
        Schema::dropIfExists('monthly_salary_presence');
    }
};
