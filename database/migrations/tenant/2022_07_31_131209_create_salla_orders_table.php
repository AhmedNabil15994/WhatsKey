<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSallaOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('salla_orders')) {
            Schema::create('salla_orders', function (Blueprint $table) {
                $table->id();
                $table->text('salla_id')->nullable();
                $table->text('reference_id')->nullable();
                $table->text('total')->nullable();
                $table->datetime('date')->nullable();
                $table->text('status')->nullable();
                $table->text('can_cancel')->nullable();
                $table->text('items')->nullable();
                $table->text('urls')->nullable();
                $table->text('source')->nullable();
                $table->text('source_device')->nullable();
                $table->text('first_complete_at')->nullable();
                $table->text('payment_method')->nullable();
                $table->text('currency')->nullable();
                $table->text('amounts')->nullable();
                $table->text('shipping')->nullable();
                $table->text('can_reorder')->nullable();
                $table->text('is_pending_payment')->nullable();
                $table->text('pending_payment_ends_at')->nullable();
                $table->text('shipment_branch')->nullable();
                $table->text('customer')->nullable();
                $table->text('bank')->nullable();
                $table->text('tags')->nullable();
                $table->text('source_details')->nullable();
                $table->text('show_weight')->nullable();
                $table->text('total_weight')->nullable();
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
        Schema::dropIfExists('salla_orders');
    }
}
