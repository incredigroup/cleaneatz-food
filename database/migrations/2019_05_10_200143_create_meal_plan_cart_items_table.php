<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMealPlanCartItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('meal_plan_cart_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('meal_plan_cart_id')->unsigned();
            $table->foreign('meal_plan_cart_id')->references('id')->on('meal_plan_carts');
            $table->bigInteger('meal_id')->unsigned();
            $table->foreign('meal_id')->references('id')->on('meals');
            $table->string('meal_name')->nullable();
            $table->smallInteger('quantity')->unsigned();
            $table->decimal('cost', 9, 2)->default(0);
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
        Schema::dropIfExists('meal_plan_cart_items');
    }
}
