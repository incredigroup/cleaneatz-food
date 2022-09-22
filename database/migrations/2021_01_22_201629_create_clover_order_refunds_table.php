<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCloverOrderRefundsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clover_order_refunds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clover_order_id')->index();
            $table->timestamp('refunded_at')->nullable();
            $table->string('transaction_id')->index();
            $table->decimal('refund_amount', 9, 2);
            $table->decimal('tax_amount', 9, 2);
            $table->decimal('tip_amount', 9, 2);
            $table->timestamps();
        });
    }

}
