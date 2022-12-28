<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_id');
            $table->text('token')->nullable();
            $table->string('title')->nullable();
            $table->string('sellerJid')->nullable();
            $table->integer('itemCount')->nullable();
            $table->string('price')->nullable();
            $table->string('currency')->nullable();
            $table->string('time')->nullable();
            $table->string('chatId')->nullable();
            $table->text('products')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
