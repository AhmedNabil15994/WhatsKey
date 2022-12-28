<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('product_id');
            $table->string('name');
            $table->string('currency');
            $table->string('price');
            $table->string('collection_id')->nullable();
            $table->text('description')->nullable();
            $table->string('availability')->nullable();
            $table->string('review_status')->nullable();
            $table->integer('is_hidden')->nullable();
            $table->text('images');
        });
    }

    public function down()
    {
        Schema::dropIfExists('products');
    }
}
