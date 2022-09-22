<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentTokensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_tokens', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('store_location_id')->unsigned();
            $table->foreign('store_location_id')->references('id')->on('store_locations');
            $table->string('client_token');
            $table->string('nonce');
            $table->string('card_name')->nullable();
            $table->string('card_last_4')->nullable();
            $table->string('card_exp_month')->nullable();
            $table->string('card_exp_year')->nullable();
            $table->string('card_brand')->nullable();
            $table->string('card_token')->nullable();
            $table->string('error')->nullable();
            $table->timestamps();

            $table->index('client_token');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_tokens');
    }
}
