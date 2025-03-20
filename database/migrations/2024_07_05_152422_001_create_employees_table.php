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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('identity_no', 16);
            $table->string('nickname', 20);
            $table->string('no_telp', 15);
            $table->string('birth_place', 25);
            $table->date('birth_date');
            $table->string('fathers_name', 100);
            $table->string('mothers_name', 100);
            $table->text('address');
            $table
                ->bigInteger('province_id')
                ->unsigned()
                ->index();
            $table
                ->bigInteger('city_id')
                ->unsigned()
                ->index();
            $table
                ->bigInteger('district_id')
                ->unsigned()
                ->index();
            $table
                ->bigInteger('subdistrict_id')
                ->unsigned()
                ->index();
            $table->bigInteger('postal_code_id')->unsigned();
            $table->string('parents_no_telp', 15);
            $table->string('siblings_name', 100);
            $table->string('siblings_no_telp', 15);
            $table->boolean('bpjs');
            $table->string('bank_account_no', 255)->nullable();
            $table->date('acceptance_date')->nullable();
            $table->string('signs', 255)->nullable();
            $table->text('notes');
            $table->string('image_identity_id', 255)->nullable();
            $table->string('image_selfie', 255)->nullable();
            $table
                ->bigInteger('user_id')
                ->unsigned()
                ->index();
            $table
                ->bigInteger('bank_id')
                ->unsigned()
                ->index();
            $table
                ->bigInteger('employee_status_id')
                ->unsigned()
                ->index();
            $table->tinyInteger('gender');
            $table->tinyInteger('religion');
            $table->tinyInteger('driving_license');
            $table->tinyInteger('marital_status');
            $table->tinyInteger('level_of_education');
            $table->string('major', 10)->nullable();
            $table->double('latitude');
            $table->double('longitude');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();

            $table
                ->foreign('bank_id')
                ->references('id')
                ->on('banks')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table
                ->foreign('city_id')
                ->references('id')
                ->on('cities')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table
                ->foreign('district_id')
                ->references('id')
                ->on('districts')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table
                ->foreign('employee_status_id')
                ->references('id')
                ->on('employee_statuses')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table
                ->foreign('province_id')
                ->references('id')
                ->on('provinces')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table
                ->foreign('subdistrict_id')
                ->references('id')
                ->on('subdistricts')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table
                ->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table
                ->foreign('postal_code_id')
                ->references('id')
                ->on('postal_codes')
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
        Schema::dropIfExists('employees');
    }
};
