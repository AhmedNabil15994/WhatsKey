<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class CreateDialogsTable extends Migration
{
    public function up()
    {
        Schema::create('dialogs', function (Blueprint $table) {
            $table->string('id')->unique();
            $table->string('name')->nullable();
            $table->text('image')->nullable();
            $table->text('metadata')->nullable();
            $table->string('pinned')->nullable();
            $table->string('archived')->nullable();
            $table->string('unreadCount')->nullable();
            $table->string('unreadMentionCount')->nullable();
            $table->string('notSpam')->nullable();
            $table->string('readOnly')->nullable();
            $table->text('modsArr')->nullable();
            $table->string('last_time')->nullable();
            $table->integer('blocked')->default(0);
            $table->integer('muted')->default(0);
            $table->string('muted_until')->nullable();
            $table->string('labels')->nullable();
            $table->string('creation')->nullable();
            $table->string('owner')->nullable();
            $table->integer('announce')->default(0);
            $table->integer('group_restrict')->default(0);
            $table->text('participants')->nullable();
            $table->integer('disable_read')->default(1);
            $table->text('background')->nullable();
            $table->text('group_description')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('dialogs');
    }
}
