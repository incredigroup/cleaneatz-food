<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddsAddOnItemsToMealPlanCartItems extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('meal_plan_cart_items', function (Blueprint $table) {
            $table->boolean('is_add_on_item')->default(false)->after('meal_name');
            $table->string('group_name')->default('meal_plan')->after('is_add_on_item');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('meal_plan_cart_items', function (Blueprint $table) {
            //
        });
    }
}
