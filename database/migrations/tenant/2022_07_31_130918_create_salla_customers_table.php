<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSallaCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('salla_customers')) {
            Schema::create('salla_customers', function (Blueprint $table) {
                $table->id();
                $table->text('salla_id')->nullable();
                $table->text('first_name')->nullable();
                $table->text('last_name')->nullable();
                $table->text('mobile')->nullable();
                $table->text('mobile_code')->nullable();
                $table->text('email')->nullable();
                $table->text('urls')->nullable();
                $table->text('avatar')->nullable();
                $table->text('gender')->nullable();
                $table->text('birthday')->nullable();
                $table->text('city')->nullable();
                $table->text('country')->nullable();
                $table->text('country_code')->nullable();
                $table->text('currency')->nullable();
                $table->text('location')->nullable();
                $table->datetime('created_at')->nullable();
                $table->datetime('updated_at')->nullable();
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
        Schema::dropIfExists('salla_customers');
    }
}
