<?php

/**
 *
 * PHP version >= 7.0
 *
 * @category Console_Command
 * @package  App\Console\Commands
 */

namespace App\Console\Commands;

use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

/**
 * Class convertCsvToJsonCommand
 *
 * @category Console_Command
 * @package  App\Console\Commands
 */
class convertCsvToJsonCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = "convert:csvToJson {filePath}";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = "Convert CSV to JSON";


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            $filePath = $this->argument('filePath');
            $rows   = array_map('str_getcsv', file($filePath));
            $header = array_shift($rows);
            $data    = array();
            foreach ($rows as $row) {
                $data[] = array_combine($header, $row);
            }
            Storage::disk('local')->put('carousel-data.json', json_encode($data));
            $this->info("JSON file created");
        } catch (Exception $e) {
            $this->error($e->getMessage());
        }
    }
}
