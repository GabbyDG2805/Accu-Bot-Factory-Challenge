<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Models\Order;

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

            foreach($csvData as $data){
                $data = array_combine($headers, $data);

                Order::updateOrInsert(
                    ['id' => $data['Order ID']],
                    [
                        'customer_name' => $data['Customer Name'],
                        'updated_at' => Carbon::now()
                    ]
                );
            }

        //need to make more efficient by cleansing array of duplicates first and to upload other info to other tables.

        $this->info('Order data imported successfully.');
    }
}
