<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMinMealsAndMatchTypeToPromoCodes extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('promo_codes', function (Blueprint $table) {
            $table->string('match_type')->default('equals')->after('code');
            $table->integer('min_meals_required')->unsigned()->default(0)->after('end_on');
        });

        \DB::table('promo_codes')->whereIn('code', ['LGH10', 'LGH15', 'PSH10', 'COM10'])
            ->update(['match_type' => 'starts_with']);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('promo_codes', function (Blueprint $table) {
            $table->dropColumn('match_type');
            $table->dropColumn('min_meals_required');
        });
    }
}
