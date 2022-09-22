<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesReportLocationCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_report_location_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sales_report_id')->index();
            $table->foreignId('sales_report_location_id')->index();
            $table->string('category')->nullable();
            $table->decimal('net_sales', 9, 2)->default(0);
            $table->integer('quantity')->default(0);
        });
    }
}
