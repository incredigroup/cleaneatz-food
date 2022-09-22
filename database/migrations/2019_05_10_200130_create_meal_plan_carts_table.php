<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMealPlanCartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meal_plan_carts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('cookie_id');
            $table->bigInteger('meal_plan_id')->unsigned();
            $table->foreign('meal_plan_id')->references('id')->on('meal_plans');
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->foreign('user_id')->references('id')->on('users');
            $table->bigInteger('store_location_id')->unsigned();
            $table->foreign('store_location_id')->references('id')->on('store_locations');
            $table->json('special_requests')->nullable();
            $table->boolean('delivery')->default(false);
            $table->bigInteger('satellite_location_id')->unsigned()->nullable();
            $table->foreign('satellite_location_id')->references('id')->on('satellite_locations');
            $table->bigInteger('promo_code_id')->unsigned()->nullable();
            $table->foreign('promo_code_id')->references('id')->on('promo_codes');
            $table->date('order_placed_on')->nullable();
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
        Schema::dropIfExists('meal_plan_carts');
    }
}
