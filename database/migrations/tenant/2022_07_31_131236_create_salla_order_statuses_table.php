<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSallaOrderStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('salla_order_statuses')) {
            Schema::create('salla_order_statuses', function (Blueprint $table) {
                $table->id();
                $table->text('salla_id')->nullable();
                $table->text('name')->nullable();
            });
        }
        if (!Schema::hasTable('salla_order_status')) {
            Schema::create('salla_order_status', function (Blueprint $table) {
                $table->id();
                $table->text('salla_id')->nullable();
                $table->text('name')->nullable();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('salla_order_statuses');
        Schema::dropIfExists('salla_order_status');
    }
}
