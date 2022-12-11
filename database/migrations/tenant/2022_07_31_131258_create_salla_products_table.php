<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSallaProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('salla_products')) {
            Schema::create('salla_products', function (Blueprint $table) {
                $table->id();
                $table->text('salla_id')->nullable();
                $table->text('promotion')->nullable();
                $table->text('sku')->nullable();
                $table->text('type')->nullable();
                $table->text('name')->nullable();
                $table->text('short_link_code')->nullable();
                $table->text('urls')->nullable();
                $table->text('price')->nullable();
                $table->text('taxed_price')->nullable();
                $table->text('pre_tax_price')->nullable();
                $table->text('tax')->nullable();
                $table->text('description')->nullable();
                $table->text('quantity')->nullable();
                $table->text('status')->nullable();
                $table->text('is_available')->nullable();
                $table->text('views')->nullable();
                $table->text('sale_price')->nullable();
                $table->text('sale_end')->nullable();
                $table->text('require_shipping')->nullable();
                $table->text('cost_price')->nullable();
                $table->text('weight')->nullable();
                $table->text('with_tax')->nullable();
                $table->text('url')->nullable();
                $table->text('images')->nullable();
                $table->text('sold_quantity')->nullable();
                $table->text('rating')->nullable();
                $table->text('regular_price')->nullable();
                $table->text('max_items_per_user')->nullable();
                $table->text('show_in_app')->nullable();
                $table->text('notify_quantity')->nullable();
                $table->text('unlimited_quantity')->nullable();
                $table->text('managed_by_branches')->nullable();
                $table->text('allow_attachments')->nullable();
                $table->text('is_pinned')->nullable();
                $table->dateTime('pinned_date')->nullable();
                $table->text('enable_upload_image')->nullable();
                $table->text('options')->nullable();
                $table->text('skus')->nullable();
                $table->text('categories')->nullable();
                $table->text('brand')->nullable();
                $table->dateTime('updated_at')->nullable();
                $table->text('tags')->nullable();
                $table->text('mpn')->nullable();
                $table->text('gtin')->nullable();
                $table->text('main_image')->nullable();
                $table->text('hide_quantity')->nullable();
                $table->text('sort')->nullable();
                $table->text('digital_download_limit')->nullable();
                $table->text('digital_download_expiry')->nullable();
                $table->text('services_blocks')->nullable();
                $table->text('weight_type')->nullable();
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
        Schema::dropIfExists('salla_products');
    }
}
