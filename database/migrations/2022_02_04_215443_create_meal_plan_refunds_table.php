<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMealPlanRefundsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meal_plan_refunds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable();
            $table->foreignId('meal_plan_order_id');
            $table->decimal('total_refund', 9, 2)->default(0);
            $table->decimal('net_refund', 9, 2)->default(0);
            $table->decimal('tax', 9, 2)->default(0);
            $table->decimal('tip_amount', 9, 2)->default(0);
            $table->decimal('satellite_fee', 9, 2)->default(0);
            $table->text('notes')->nullable();
            $table->string('transaction_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('meal_plan_refunds');
    }
}
