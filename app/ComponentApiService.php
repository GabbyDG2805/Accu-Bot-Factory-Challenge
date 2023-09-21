<?php

namespace app;

use Illuminate\Support\Facades\Http;
use App\Models\Component;

class ComponentApiService
{
    /**
     * Fetch component data from the API and handle pagination.
     *
     * @return array|false An array of component data fetched from the API or false if the request was not successful.
     */
    public function fetchComponentData()
    {
        // Initialize an empty array to store the fetched products.
        $products = [];

        // Define the URL of the API endpoint.
        $url = 'https://nt5gkznl19.execute-api.eu-west-1.amazonaws.com/prod/products';

        // Continue fetching data as long as there are more pages (pagination).
        while ($url) {
            // Make a GET request to the API endpoint.
            $response = Http::get($url);

            // Check if the request was successful (status code 200).
            if ($response->successful()) {
                // Parse the JSON response into an array.
                $data = $response->json();

                // Access the 'value' key in the response, which contains the product data.
                $currentProducts = $data['value'];

                // Append the products from this page to the array.
                $products = array_merge($products, $currentProducts);

                // Check if there are more pages of data.
                $url = $data['@odata.nextLink'] ?? null;
            } else {
                // If the API request was not successful, return false to indicate an error.
                return false;
            }
        }

        // Return the array of fetched products when all pages have been processed.
        return $products;
    }

    /**
     * Store component data retrieved from the API in the database.
     *
     * @param array $products An array of component data fetched from the API.
     */
    public function storeComponentData($products)
	{
	    foreach ($products as $product) {
            // Update or create a Component record based on the 'sku' value.
	        Component::updateOrCreate(
	            ['sku' => $product['sku']],
	            [
	                'description' => $product['product_name'],
	                'category' => $product['category'],
	                'weight' => $product['weight'],
	            ]
	        );
	    }
	}
}