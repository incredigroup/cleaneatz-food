<?php

use App\Models\StoreLocation;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('store_locations', function (Blueprint $table) {
            $table->after('address', function ($table) {
                $table->string('zip')->nullable();
                $table->string('phone')->nullable();
                $table->string('email')->nullable();
                $table->string('store_type')->default(StoreLocation::STORE_TYPE_CAFE);
                $table->string('status')->default(StoreLocation::STATUS_COMING_SOON);
                $table->text('hours_of_operation')->nullable();
                $table->boolean('has_sunday_pickup')->default(true);
            });
        });
    }
};
