<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTransactionResponseToOrders extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('meal_plan_orders', function (Blueprint $table) {
            $table->json('transaction_details')->nullable()->after('transaction_status');
            $table->bigInteger('meal_plan_cart_id')->unsigned()->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('meal_plan_orders', function (Blueprint $table) {
            //
        });
    }
}
