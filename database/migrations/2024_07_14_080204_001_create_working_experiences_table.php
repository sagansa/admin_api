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
        Schema::create('working_experiences', function (Blueprint $table) {
            $table->id();
            $table
                ->bigInteger('employee_id')
                ->unsigned()
                ->index();
            $table->string('place');
            $table->string('position');
            $table->bigInteger('salary_per_month');
            $table->date('from_date');
            $table->date('until_date');
            $table->text('reason')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();

            $table
                ->foreign('employee_id')
                ->references('id')
                ->on('employees')
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
        Schema::dropIfExists('working_experiences');
    }
};
