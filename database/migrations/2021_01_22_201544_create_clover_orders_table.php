<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCloverOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clover_orders', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_id');
            $table->string('device_id')->nullable();
            $table->foreignId('store_location_id')->index();
            $table->timestamp('order_created_at')->nullable();
            $table->timestamp('payment_at')->nullable();
            $table->decimal('payment_amount', 9, 2);
            $table->decimal('tax_amount', 9, 2);
            $table->decimal('tip_amount', 9, 2);
            $table->decimal('discount_amount', 9, 2);
            $table->decimal('net_sales_amount', 9, 2);
            $table->timestamps();
        });
    }
}
