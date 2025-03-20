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
        Schema::create('delivery_addresses', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('recipients_name', 255);
            $table->string('recipients_telp_no', 255)->nullable();
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
            $table
                ->bigInteger('postal_code_id')
                ->unsigned()
                ->index();
            $table->double('latitude')->nullable();
            $table->double('longitude')->nullable();
            $table
                ->bigInteger('user_id')
                ->unsigned()
                ->index();
            $table->tinyInteger('for');
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
        Schema::dropIfExists('delivery_addresses');
    }
};
