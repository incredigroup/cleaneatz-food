<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRoyaltyTierToStoreLocations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('store_locations', function (Blueprint $table) {
            $table->integer('royalty_tier')->after('is_online_payment_enabled')->default(2);
        });
    }
}
