<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGroupMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('group_messages', function (Blueprint $table) {
            $table->id();
            $table->string('channel')->nullable();
            $table->integer('group_id');
            $table->integer('message_type');
            $table->text('message')->nullable();
            $table->dateTime('publish_at')->nullable();
            $table->integer('later')->default(0);
            $table->integer('contacts_count')->default(0);
            $table->integer('messages_count')->default(0);
            $table->integer('sent_count')->default(0);
            $table->integer('unsent_count')->default(0);
            $table->string('file_name')->nullable();
            $table->string('https_url')->nullable();
            $table->string('url_title')->nullable();
            $table->string('url_desc')->nullable();
            $table->string('url_image')->nullable();
            $table->string('whatsapp_no')->nullable();
            $table->string('mention')->nullable();
            $table->string('lat')->nullable();
            $table->string('lng')->nullable();
            $table->string('expiration_in_seconds')->nullable();
            $table->integer('bot_plus_id')->nullable();
            $table->integer('list_id')->nullable();
            $table->integer('poll_id')->nullable();
            $table->integer('template_id')->nullable();
            $table->integer('interval_in_sec')->nullable();
            $table->integer('status')->nullable();
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
        Schema::dropIfExists('group_messages');
    }
}
