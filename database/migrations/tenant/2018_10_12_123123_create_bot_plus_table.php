<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBotPlusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bot_plus', function (Blueprint $table) {
            $table->id();
            $table->string('channel')->nullable();
            $table->integer('message_type');
            $table->text('message');
            $table->string('title')->nullable();
            $table->text('body');
            $table->string('footer');
            $table->integer('buttons');
            $table->text('buttonsData');
            $table->integer('status')->nullable();
            $table->integer('category_id')->nullable();
            $table->integer('moderator_id')->nullable();
            $table->text('image')->nullable();
            $table->integer('sort')->nullable();
            $table->integer('created_by')->nullable();
            $table->dateTime('created_at')->nullable();
            $table->integer('updated_by')->nullable();
            $table->dateTime('updated_at')->nullable();
            $table->integer('deleted_by')->nullable();
            $table->dateTime('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bot_plus');
    }
}
