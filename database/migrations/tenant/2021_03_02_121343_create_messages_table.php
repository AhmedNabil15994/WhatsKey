<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateMessagesTable extends Migration
{
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->string('id')->unique();
            $table->text('body')->nullable();
            $table->integer('fromMe')->nullable();
            $table->integer('isForwarded')->nullable()->default(0);
            $table->string('author')->nullable();
            $table->string('time')->nullable();
            $table->string('chatId')->nullable();
            $table->string('type')->nullable();
            $table->string('status')->nullable();
            $table->string('senderName')->nullable();
            $table->string('chatName')->nullable();
            $table->text('caption')->nullable();
            $table->integer('sending_status')->default(2);
            $table->text('metadata')->nullable();
            $table->string('module_id')->nullable();
            $table->string('module_status')->nullable();
            $table->string('module_order_id')->nullable();
            $table->integer('deleted_by')->nullable();
            $table->dateTime('deleted_at')->nullable();
            $table->index('chatId');
        });
    }

    public function down()
    {
        Schema::dropIfExists('messages');
    }
}
