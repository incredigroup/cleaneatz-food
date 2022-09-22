<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAudienceIdToNewsletterSignups extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('newsletter_signups', function (Blueprint $table) {
            $table->string('audience_id')->nullable()->after('source');
        });

        \DB::table('newsletter_signups')->update(['audience_id' => env('MAILCHIMP_LIST_ID')]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
