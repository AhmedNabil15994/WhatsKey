<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->string('Name_ar');
            $table->string('Name_en');
            $table->string('CountryCode');
            $table->integer('Country_id')->index();
            $table->string('Date');
            $table->string('Updated_Date');
            $table->string('AdminAddID');
            $table->string('AdminUpdateID');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cities');
    }
}
