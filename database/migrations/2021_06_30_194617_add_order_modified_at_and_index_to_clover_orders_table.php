<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrderModifiedAtAndIndexToCloverOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clover_orders', function (Blueprint $table) {
            $table->timestamp('order_modified_at')->nullable()->after('order_created_at');
            $table->unique('transaction_id');
        });
    }

}
