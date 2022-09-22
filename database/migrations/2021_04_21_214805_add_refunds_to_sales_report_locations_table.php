<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRefundsToSalesReportLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('sales_report_locations', function (Blueprint $table) {
            $table->after('sales_tax_pos', function (Blueprint $table) {
                $table->decimal('net_sales_refunds_pos', 9, 2)->default(0);
                $table->decimal('tips_refunds_pos', 9, 2)->default(0);
                $table->decimal('sales_tax_refunds_pos', 9, 2)->default(0);
            });
        });
    }

}
