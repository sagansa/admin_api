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
        Schema::create('production_main_froms', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('production_id')->unsigned();
            $table->bigInteger('detail_invoice_id')->unsigned();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();

            $table
                ->foreign('production_id')
                ->references('id')
                ->on('productions')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table
                ->foreign('detail_invoice_id')
                ->references('id')
                ->on('detail_invoices')
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
        Schema::dropIfExists('production_main_froms');
    }
};
