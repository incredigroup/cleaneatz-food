<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class GenerateRewrites extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:rewrites';

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
     * @return mixed
     */
    public function handle()
    {
        $paths = collect([
            'mealplanmenu',
            'login',
            'logout',
            'register',
            'password/reset',
            'order-online',
            'profile',
            'vendors',
            'auth/status',
            'api/store-locations',
            'order',
            'custom',
        ]);

        $file = 'nginx_rewrites.conf';
        $siteUrl = env('APP_URL');

        Storage::put($file, '');

        $paths->each(function($path) use ($file, $siteUrl) {
            $rule = "location /$path {\n" .
            "   proxy_hide_header X-XSS-Protection;\n" .
            "   proxy_pass $siteUrl;\n" .
            "}\n\n";

            Storage::append($file, $rule);
        });
    }
}
