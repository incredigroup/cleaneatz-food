<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsRevenueToCloverOrderItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clover_order_items', function (Blueprint $table) {
            $table
                ->boolean('is_revenue')
                ->default(true)
                ->after('line_item_total_amount');
        });
    }
}
