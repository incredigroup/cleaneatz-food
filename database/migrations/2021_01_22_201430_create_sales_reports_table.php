<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSalesReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_reports', function (Blueprint $table) {
            $table->id();
            $table->integer('period_num');
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->decimal('net_sales_pos', 9, 2)->default(0);
            $table->decimal('net_sales_online', 9, 2)->default(0);
            $table->decimal('royalties', 9, 2)->default(0);
            $table->json('royalty_meta')->nullable();
            $table->integer('num_locations')->default(0);

            $table->timestamps();
        });
    }
}
