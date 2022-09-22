<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPayeezyToStoreLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('store_locations', function (Blueprint $table) {
            $table->string('gateway_merchant_js_key')->nullable()->after('gateway_merchant_token');
            $table->string('gateway_merchant_id')->nullable()->after('gateway_merchant_js_key');
            $table->string('gateway_merchant_name')->nullable()->after('gateway_merchant_id');
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
            //
        });
    }
}
