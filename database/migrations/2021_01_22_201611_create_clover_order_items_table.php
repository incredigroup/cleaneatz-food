<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCloverOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clover_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('clover_order_id')->index();
            $table->string('name');
            $table->string('category');
            $table->decimal('price', 9, 2);
            $table->integer('quantity');
            $table->decimal('discount_amount', 9, 2);
            $table->decimal('line_item_total_amount', 9, 2);
            $table->timestamps();
        });
    }
}
