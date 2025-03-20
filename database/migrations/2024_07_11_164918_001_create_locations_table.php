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
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table
                ->bigInteger('store_id')
                ->unsigned()
                ->index();
            $table->string('contact_person_name', 255);
            $table->string('contact_person_number', 255);
            $table->string('address', 255);
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
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();

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
                ->foreign('province_id')
                ->references('id')
                ->on('provinces')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table
                ->foreign('store_id')
                ->references('id')
                ->on('stores')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table
                ->foreign('subdistrict_id')
                ->references('id')
                ->on('subdistricts')
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
        Schema::dropIfExists('locations');
    }
};
