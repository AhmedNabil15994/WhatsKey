<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateZidAbandonedcartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('zid_abandonedCarts')) {
            Schema::create('zid_abandonedCarts', function (Blueprint $table) {
                $table->id();
                $table->text('zid_id')->nullable();
                $table->text('store_id')->nullable();
                $table->text('session_id')->nullable();
                $table->text('cart_id')->nullable();
                $table->text('order_id')->nullable();
                $table->text('phase')->nullable();
                $table->text('customer_id')->nullable();
                $table->text('customer_name')->nullable();
                $table->text('customer_email')->nullable();
                $table->text('customer_mobile')->nullable();
                $table->text('city_id')->nullable();
                $table->text('products_count')->nullable();
                $table->text('reminders_count')->nullable();
                $table->text('cart_total')->nullable();
                $table->text('cart_total_string')->nullable();
                $table->datetime('created_at')->nullable();
                $table->datetime('updated_at')->nullable();
                $table->text('whatsapp_message')->nullable();
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
        Schema::dropIfExists('zid_abandonedCarts');
    }
}
