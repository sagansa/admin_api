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
        Schema::table('stock_monitorings', function (Blueprint $table) {
            $table
                ->bigInteger('unit_id')
                ->unsigned()
                ->after('category');
            $table
                ->foreign('unit_id')
                ->references('id')
                ->on('units')
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
        Schema::table('stock_monitorings', function (Blueprint $table) {
            $table->dropColumn('unit_id');
            $table->dropForeign('stock_monitorings_unit_id_foreign');
        });
    }
};
