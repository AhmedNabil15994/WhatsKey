<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSallaAbandonedCartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('salla_abandonedCarts')) {
            Schema::create('salla_abandonedCarts', function (Blueprint $table) {
                $table->id();
                $table->text('total')->nullable();
                $table->text('subtotal')->nullable();
                $table->text('total_discount')->nullable();
                $table->text('checkout_url')->nullable();
                $table->text('age_in_minutes')->nullable();
                $table->datetime('created_at')->nullable();
                $table->datetime('updated_at')->nullable();
                $table->text('customer')->nullable();
                $table->text('coupon')->nullable();
                $table->text('items')->nullable();
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
        Schema::dropIfExists('salla_abandonedCarts');
    }
}
