<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Models\Order;
use App\Models\Component;
use App\Models\ComponentOrder;
use App\ComponentApiService;

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
        // Retrieve the file path argument provided when running the command
        $filePath = $this->argument('file');

        // Check if the specified file exists in storage
        if (!Storage::exists($filePath)) {
            $this->error('The specified file does not exist.');
            return;
        }

        // Initialize an array to store CSV data
        $csvData = [];

        // Open and read the CSV file
        $handle = fopen(storage_path('app/' . $filePath), 'r');

        // Check if the file was opened successfully
        if ($handle !== false) {
            // Read each line of the CSV file and store it in the $csvData array
            while (($data = fgetcsv($handle)) !== false) {
                $csvData[] = $data;
            }
            fclose($handle);
        } else {
            $this->error('Unable to open the CSV file.');
            return;
        }

        // Create and start a progress bar to display the import progress
        $progressBar = $this->output->createProgressBar(count($csvData));
        $progressBar->start();

        // Extract headers (first row) from CSV data
        $headers = array_shift($csvData);

        foreach ($csvData as $data){
            // Combine headers with current data row to create an associative array
            $data = array_combine($headers, $data);

            // Find or create an order record based on 'Order ID'
            $order = Order::firstOrCreate(
                ['id' => $data['Order ID']],
                [
                    'customer_name' => $data['Customer Name']
                ]
            );

            // Find or create a component record based on 'SKU'
            $component = Component::firstOrCreate(
                ['sku' => $data['SKU']]
            );

            // Find or create a componentOrder record based on 'order_id' and 'component_id'
            ComponentOrder::firstOrCreate(
                ['order_id' => $order->id, 'component_id' => $component->id],
                ['quantity' => $data['Quantity']]
            );

            // Advance the progress bar
            $progressBar->advance();
        }
        // This could be made more efficient but moving on due to consciousness of time.

        // Create a new instance of the ComponentApiService class and fetch component data from the API using the service
        $componentApiService = new ComponentApiService();
        $products = $componentApiService->fetchComponentData();

        // Check if component data was successfully retrieved from the API
        if ($products !== false) {
            // Store the fetched component data in the database using the service.
            $componentApiService->storeComponentData($products);

            // Loop through existing orders, check if the total weight & robot name has been calculated yet and if not, calculate them and save them to the database
            foreach (Order::all() as $order) {
                if (is_null($order->total_weight)) {
                    $totalWeight = $order->calculateTotalWeight();
                    $order->total_weight = $totalWeight;
                    $order->save();
                }

                if (is_null($order->robot_name)) {
                    $robotName = $order->generateRobotName($order);
                    $order->robot_name = $robotName;
                    $order->save();
                }
            }

            // Complete and display the progress bar and a success message
            $progressBar->finish();
            $this->output->newLine();
            $this->info('Order data imported successfully.');
        } else {
            // Display an error message in the case that API data retrieval was unsuccessful.
            $this->output->newLine();
            $this->error('Unable to get the components API data.');
            return;
        }
    }
}
