<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class FixZidOrderShippingMethodCodeType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // if (Schema::hasTable('zid_orders')) {
        //     Schema::table('zid_orders', function ($table) {
        //         $table->text('shipping_method_code')->nullable()->change();
        //     });
        // }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
