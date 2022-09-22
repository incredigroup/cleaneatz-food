<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePromoCodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('promo_codes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('store_location_id')->unsigned()->nullable();
            $table->foreign('store_location_id')->references('id')->on('store_locations');
            $table->string('name');
            $table->string('code');
            $table->decimal('discount_amount', 9, 2)->default(0);
            $table->integer('discount_percent')->unsigned()->nullable();
            $table->date('start_on')->nullable();
            $table->date('end_on')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('promo_codes');
    }
}
