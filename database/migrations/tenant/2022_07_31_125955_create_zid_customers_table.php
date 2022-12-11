<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateZidCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('zid_customers')) {
            Schema::create('zid_customers', function (Blueprint $table) {
                $table->id();
                $table->text('zid_id')->nullable();
                $table->text('name')->nullable();
                $table->text('email')->nullable();
                $table->text('mobile')->nullable();
                $table->text('verified')->nullable();
                $table->text('city')->nullable();
                $table->text('nickname')->nullable();
                $table->text('pivotEmail')->nullable();
                $table->text('pivotMobile')->nullable();
                $table->text('order_counts')->nullable();
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
        Schema::dropIfExists('zid_customers');
    }
}
