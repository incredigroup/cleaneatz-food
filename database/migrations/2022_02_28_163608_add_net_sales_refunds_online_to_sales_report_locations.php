<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNetSalesRefundsOnlineToSalesReportLocations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sales_report_locations', function (Blueprint $table) {
            $table
                ->decimal('net_sales_refunds_online', 9, 2)
                ->default(0)
                ->after('sales_tax_online');
        });
    }
}
