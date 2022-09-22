<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSatelliteLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('satellite_locations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('store_location_id')->unsigned();
            $table->foreign('store_location_id')->references('id')->on('store_locations');
            $table->string('name');
            $table->decimal('fee', 9, 2)->default(0);
            $table->string('address');
            $table->string('city');
            $table->string('state');
            $table->string('zip');
            $table->boolean('is_approved')->default(false);
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
        Schema::dropIfExists('satellite_locations');
    }
}
