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
        Schema::create('contract_employees', function (Blueprint $table) {
            $table->id();
            $table->string('file', 255)->nullable();
            $table->date('from_date');
            $table->date('until_date');
            $table->bigInteger('nominal_guarantee');
            $table->tinyInteger('guarantee');
            $table
                ->bigInteger('employee_id')
                ->unsigned()
                ->nullable();
            $table
                ->bigInteger('user_id')
                ->unsigned()
                ->index();
            $table->boolean('guaranteed_return');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();

            $table
                ->foreign('user_id')
                ->references('id')
                ->on('users')
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
        Schema::dropIfExists('contract_employees');
    }
};
