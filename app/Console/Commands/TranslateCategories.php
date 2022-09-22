<?php

namespace App\Console\Commands;

use App\Models\SalesReportLocationCategory;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use League\Csv\Reader;


class TranslateCategories extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'translate:categories';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Translates categories from one name to another';


    private $csvPath = 'category-map.csv';

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
        if (Storage::missing($this->csvPath)) {
            $this->error("storage/{$this->csvPath} missing");
            die();
        }

        DB::statement("update sales_report_location_categories set category = 'OTHER' where category = ''");

        $reader = Reader::createFromPath(storage_path("app/{$this->csvPath}"), 'r');
        $reader->setHeaderOffset(0); //set the CSV header offset

        $records = $reader->getRecords();
        foreach ($records as $offset => $record) {
            $newName = $record['New Category Name'];
            $currentName = $record['Current Category Name'];

            if (!$newName || !$currentName) {
                continue;
            }

            if ($newName === $currentName) {
                continue;
            }

            SalesReportLocationCategory::whereRaw('LOWER(category) = ?', [strtolower($currentName)])
                ->get()
                ->each(function ($reportRecord) use ($newName, $currentName) {
                    $rollupRecord = SalesReportLocationCategory::whereRaw('LOWER(category) = ?', [strtolower($newName)])
                        ->where('sales_report_location_id', '=', $reportRecord->sales_report_location_id)
                        ->first();

                    // if there is no record to roll up to or the only record found was
                    // the one that needs to be updated, just change the name
                    if ($rollupRecord === null || $rollupRecord->id === $reportRecord->id) {
                        $reportRecord->category = $newName;
                        $reportRecord->save();
                        return;
                    }

                    $rollupRecord->net_sales += $reportRecord->net_sales;
                    $rollupRecord->quantity += $reportRecord->quantity;
                    $rollupRecord->save();
                    $reportRecord->delete();
                });
        }

        $this->info('done');
    }
}
