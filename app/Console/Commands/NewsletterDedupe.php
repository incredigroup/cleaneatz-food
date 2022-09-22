<?php

namespace App\Console\Commands;

use App\Models\NewsletterSignup;
use Illuminate\Console\Command;

class NewsletterDedupe extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'newsletter:dedupe';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $dupes = NewsletterSignup::select('email', 'audience_id')
            ->groupBy('email', 'audience_id')
            ->havingRaw('count(email) > 1')
            ->get();

        $dupes->each(function($dupe) {
           $signups = NewsletterSignup::where('email', '=', $dupe->email)
               ->where('audience_id', '=', $dupe->audience_id)
               ->get();

           $signups->shift();
           $signups->each->delete();
        });

        $this->info('NewsletterSignups De-duped');
    }
}
