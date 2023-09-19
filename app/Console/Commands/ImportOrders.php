<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ImportOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:orders {file : Path to the CSV file}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import order data from a CSV file';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $filePath = $this->argument('file');

        if (!Storage::exists($filePath)) {
            $this->error('The specified file does not exist.');
            return;
        }

        $csvData = [];
        $handle = fopen(storage_path('app/' . $filePath), 'r');

        if ($handle !== false) {
            while (($data = fgetcsv($handle)) !== false) {
                $csvData[] = $data;
            }
            fclose($handle);
        } else {
            $this->error('Unable to open the CSV file.');
            return;
        }

        $headers = array_shift($csvData);

            foreach($csvData as $value){
                $value = array_combine($headers, $value);
                DB::table('orders')->insert([
                    'order_id' => $value['Order ID'],
                    'customer_name' => $value['Customer Name'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            //need to revise database desgin and make it so that it handles duplicates/existing entries

        $this->info('Order data imported successfully.');
    }
}
