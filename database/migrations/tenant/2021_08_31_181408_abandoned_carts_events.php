<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AbandonedCartsEvents extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('abandoned_carts_events')) {
            Schema::create('abandoned_carts_events', function (Blueprint $table) {
                $table->id();
                $table->integer('type');
                $table->integer('message_type');
                $table->text('message')->nullable();
                $table->integer('time')->nullable();
                $table->string('file_name')->nullable();
                $table->text('caption')->nullable();
                $table->integer('bot_plus_id')->nullable();
                $table->integer('status')->nullable();
                $table->integer('created_by')->nullable();
                $table->dateTime('created_at')->nullable();
                $table->integer('updated_by')->nullable();
                $table->dateTime('updated_at')->nullable();
                $table->integer('deleted_by')->nullable();
                $table->dateTime('deleted_at')->nullable();
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
        Schema::dropIfExists('abandoned_carts_events');
    }
}
