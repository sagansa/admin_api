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
        Schema::create('vehicle_certificates', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('vehicle_id')->unsigned();
            $table->tinyInteger('bpkb');
            $table->tinyInteger('stnk');
            $table->string('name');
            $table->string('brand');
            $table->string('type');
            $table->string('category');
            $table->string('model');
            $table->year('manufacture_year');
            $table->string('cylinder_capacity');
            $table->string('vehicle_identity_no');
            $table->string('engine_no');
            $table->string('color', 9);
            $table->string('type_fuel');
            $table->string('lisence_plate_color');
            $table->string('registration_year');
            $table->string('bpkb_no');
            $table->string('location_code');
            $table->string('registration_queue_no');
            $table->text('notes')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();

            $table
                ->foreign('vehicle_id')
                ->references('id')
                ->on('vehicles')
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
        Schema::dropIfExists('vehicle_certificates');
    }
};
