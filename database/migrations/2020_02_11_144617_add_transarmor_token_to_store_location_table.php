<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTransarmorTokenToStoreLocationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('store_locations', function (Blueprint $table) {
            $table->renameColumn('gateway_merchant_js_key', 'gateway_merchant_transarmor_token');
        });
    }
}
