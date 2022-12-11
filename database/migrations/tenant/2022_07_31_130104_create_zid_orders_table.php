<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateZidOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('zid_orders')) {
            Schema::create('zid_orders', function (Blueprint $table) {
                $table->id();
                $table->text('zid_id')->nullable();
                $table->text('code')->nullable();
                $table->text('store_id')->nullable();
                $table->text('order_url')->nullable();
                $table->text('store_name')->nullable();
                $table->text('shipping_method_code')->nullable();
                $table->text('store_url')->nullable();
                $table->text('order_status')->nullable();
                $table->text('currency_code')->nullable();
                $table->text('customer')->nullable();
                $table->text('has_different_consignee')->nullable();
                $table->text('order_total')->nullable();
                $table->text('order_total_string')->nullable();
                $table->text('products_sum_total_string')->nullable();
                $table->text('has_different_transaction_currency')->nullable();
                $table->text('transaction_reference')->nullable();
                $table->text('transaction_amount')->nullable();
                $table->text('transaction_amount_string')->nullable();
                $table->datetime('created_at')->nullable();
                $table->datetime('updated_at')->nullable();
                $table->text('requires_shipping')->nullable();
                $table->text('shipping')->nullable();
                $table->text('payment')->nullable();
                $table->text('customer_note')->nullable();
                $table->text('gift_message')->nullable();
                $table->text('weight')->nullable();
                $table->text('weight_cost_details')->nullable();
                $table->text('currency')->nullable();
                $table->text('coupon')->nullable();
                $table->text('products')->nullable();
                $table->text('products_count')->nullable();
                $table->text('language')->nullable();
                $table->text('histories')->nullable();
                $table->text('return_policy')->nullable();
                $table->text('consignee')->nullable();
                $table->text('payment_link')->nullable();
                $table->text('packages_count')->nullable();
                $table->text('tags')->nullable();
                $table->text('issue_date')->nullable();
                $table->text('payment_status')->nullable();
                $table->text('is_reactivated')->nullable();
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
        Schema::dropIfExists('zid_orders');
    }
}
