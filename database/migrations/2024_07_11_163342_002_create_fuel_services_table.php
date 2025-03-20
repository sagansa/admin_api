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
        Schema::create('fuel_services', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->tinyInteger('fuel_service');
            $table
                ->bigInteger('supplier_id')
                ->unsigned()
                ->index();
            $table
                ->bigInteger('vehicle_id')
                ->unsigned()
                ->index();
            $table
                ->bigInteger('payment_type_id')
                ->unsigned()
                ->index();
            $table->string('image', 255)->nullable();
            $table->bigInteger('km');
            $table->decimal('liter')->nullable();
            $table->bigInteger('amount');
            $table->tinyInteger('status');
            $table->text('notes')->nullable();
            $table
                ->bigInteger('created_by_id')
                ->unsigned()
                ->nullable()
                ->index();
            $table
                ->bigInteger('approved_by_id')
                ->unsigned()
                ->nullable()
                ->index();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();

            $table
                ->foreign('approved_by_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table
                ->foreign('created_by_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table
                ->foreign('payment_type_id')
                ->references('id')
                ->on('payment_types')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table
                ->foreign('supplier_id')
                ->references('id')
                ->on('suppliers')
                ->onDelete('cascade')
                ->onUpdate('cascade');
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
        Schema::dropIfExists('fuel_services');
    }
};
