<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesReportLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_report_locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sales_report_id')->index();
            $table->foreignId('store_location_id');
            $table->decimal('net_sales_pos', 9, 2)->default(0);
            $table->decimal('tips_pos', 9, 2)->default(0);
            $table->decimal('discounts_pos', 9, 2)->default(0);
            $table->decimal('sales_tax_pos', 9, 2)->default(0);
            $table->decimal('net_sales_online', 9, 2)->default(0);
            $table->decimal('tips_online', 9, 2)->default(0);
            $table->decimal('discounts_online', 9, 2)->default(0);
            $table->decimal('sales_tax_online', 9, 2)->default(0);
            $table->integer('royalty_tier')->default(0);
            $table->decimal('royalties_1', 9, 2)->default(0);
            $table->decimal('royalties_2', 9, 2)->default(0);
            $table->decimal('royalties_3', 9, 2)->default(0);
        });
    }
}
