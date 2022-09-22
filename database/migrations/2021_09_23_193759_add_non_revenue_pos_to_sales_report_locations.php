<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNonRevenuePosToSalesReportLocations extends Migration
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
                ->decimal('non_revenue_pos', 9, 2)
                ->default(0)
                ->after('sales_tax_pos');
        });
    }
}
