<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateZidProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('zid_products')) {
            Schema::create('zid_products', function (Blueprint $table) {
                $table->id();
                $table->text('zid_id')->nullable();
                $table->text('sku')->nullable();
                $table->text('parent_id')->nullable();
                $table->text('name')->nullable();
                $table->text('slug')->nullable();
                $table->text('price')->nullable();
                $table->text('sale_price')->nullable();
                $table->text('formatted_price')->nullable();
                $table->text('formatted_sale_price')->nullable();
                $table->text('currency')->nullable();
                $table->text('currency_symbol')->nullable();
                $table->text('attributes')->nullable();
                $table->text('categories')->nullable();
                $table->text('display_order')->nullable();
                $table->text('has_options')->nullable();
                $table->text('has_fields')->nullable();
                $table->text('images')->nullable();
                $table->text('is_draft')->nullable();
                $table->text('quantity')->nullable();
                $table->text('is_infinite')->nullable();
                $table->text('html_url')->nullable();
                $table->text('weight')->nullable();
                $table->text('keywords')->nullable();
                $table->text('requires_shipping')->nullable();
                $table->text('is_taxable')->nullable();
                $table->text('structure')->nullable();
                $table->text('seo')->nullable();
                $table->text('rating')->nullable();
                $table->text('store_id')->nullable();
                $table->text('sold_products_count')->nullable();
                $table->datetime('created_at')->nullable();
                $table->datetime('updated_at')->nullable();
                $table->text('cost')->nullable();
                $table->text('is_published')->nullable();
                $table->text('product_class')->nullable();
                $table->text('purchase_restrictions')->nullable();
                $table->text('meta')->nullable();
                $table->text('description')->nullable();
                $table->text('variants')->nullable();
                $table->text('custom_user_input_fields')->nullable();
                $table->text('custom_option_fields')->nullable();
                $table->text('options')->nullable();
                $table->text('related_products')->nullable();
                $table->text('event_extra_data')->nullable();
                $table->text('short_description')->nullable();
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
        Schema::dropIfExists('zid_products');
    }
}
