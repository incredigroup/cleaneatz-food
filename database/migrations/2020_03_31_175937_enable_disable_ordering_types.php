<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EnableDisableOrderingTypes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('store_locations', function (Blueprint $table) {
            $table->boolean('is_meal_plan_ordering_enabled')->default(true)->after('is_enabled');
            $table->boolean('is_cafe_ordering_enabled')->default(true)->after('is_enabled');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('store_locations', function (Blueprint $table) {
            $table->dropColumn('is_meal_plan_ordering_enabled');
            $table->dropColumn('is_cafe_ordering_enabled');
        });
    }
}
