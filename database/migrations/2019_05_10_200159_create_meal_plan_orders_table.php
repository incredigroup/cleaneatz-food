<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMealPlanOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meal_plan_orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('meal_plan_cart_id')->unsigned();
            $table->foreign('meal_plan_cart_id')->references('id')->on('meal_plan_carts');
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('users');
            $table->bigInteger('satellite_location_id')->unsigned()->nullable();
            $table->foreign('satellite_location_id')->references('id')->on('satellite_locations');
            $table->bigInteger('store_location_id')->unsigned()->nullable();
            $table->foreign('store_location_id')->references('id')->on('store_locations');
            $table->decimal('subtotal', 9, 2)->default(0);
            $table->decimal('tax', 9, 2)->default(0);
            $table->decimal('total', 9, 2)->default(0);
            $table->decimal('tip_amount', 9, 2)->default(0);
            $table->decimal('delivery_fee', 9, 2)->default(0);
            $table->string('first_name');
            $table->string('last_name');
            $table->string('phone')->nullable();
            $table->string('address')->nullable();
            $table->string('address2')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('zip')->nullable();
            $table->string('email')->nullable();
            $table->string('promo_code')->nullable();
            $table->decimal('promo_amount', 9, 2)->default(0);
            $table->string('order_type');
            $table->string('payment_type');
            $table->string('transaction_id')->nullable();
            $table->string('transaction_status')->default('completed');
            $table->string('card_last_4')->nullable();
            $table->string('card_exp_month')->nullable();
            $table->string('card_exp_year')->nullable();
            $table->string('card_brand')->nullable();
            $table->dateTime('cleared_at')->nullable();
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
        Schema::dropIfExists('meal_plan_orders');
    }
}
