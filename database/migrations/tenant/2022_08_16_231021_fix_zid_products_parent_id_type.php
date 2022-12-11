<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Schema;

class FixZidProductsParentIdType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // if (Schema::hasTable('zid_products')) {
        //     Schema::table('zid_products', function ($table) {
        //         $table->text('parent_id')->nullable()->change();
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
