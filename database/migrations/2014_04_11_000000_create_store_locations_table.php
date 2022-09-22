<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStoreLocationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('store_locations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('code')->unsigned();
            $table->string('state');
            $table->string('city');
            $table->string('location')->nullable();
            $table->string('address')->nullable();
            $table->boolean('delivers')->default(false);
            $table->boolean('is_enabled')->default(true);
            $table->decimal('lat', 10, 8)->nullable();
            $table->decimal('lng', 11, 8)->nullable();
            $table->string('slug')->nullable();
            $table->string('cafe_order_url')->nullable();
            $table->decimal('tax_rate', 10, 8);
            $table->string('gateway_merchant_token')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('store_locations');
    }
}
