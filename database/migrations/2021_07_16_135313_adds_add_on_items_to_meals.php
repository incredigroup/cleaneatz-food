<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddsAddOnItemsToMeals extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('meals', function (Blueprint $table) {
            $table->boolean('is_add_on_item')->default(false)->after('is_breakfast');
            $table->string('group_name')->default('meal_plan')->after('is_add_on_item');
            $table->float('price_override')->default(0)->after('group_name');
            $table->integer('sort_order')->default(10)->after('price_override');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('meals', function (Blueprint $table) {
            //
        });
    }
}
