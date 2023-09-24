<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Order;
use App\Models\Component;
use App\Models\ComponentOrder;

class OrderTest extends TestCase
{
    /**
     * Test the calculateTotalWeight method of the Order model.
     */
    public function testCalculateTotalWeight()
    {
        // Create a new order with a customer name
        $order = Order::create([
            'customer_name' => 'Test Customer',
        ]);

        // Create two components with SKUs and weights
        $component1 = Component::create([
            'sku' => 'Test-001',
            'weight' => 5,
        ]);

        $component2 = Component::create([
            'sku' => 'Test-002',
            'weight' => 3,
        ]);

        // Associate components with the order and specify quantities
        $componentOrder1 = ComponentOrder::create([
            'order_id' => $order->id,
            'component_id' => $component1->id,
            'quantity' => 2
        ]);

        $componentOrder2 = ComponentOrder::create([
            'order_id' => $order->id,
            'component_id' => $component2->id,
            'quantity' => 3
        ]);

        // Calculate the total weight
        $totalWeight = $order->calculateTotalWeight();

        // Assert that the total weight is calculated correctly
        $this->assertEquals(5 * 2 + 3 * 3, $totalWeight);

        // Clean up by deleting the created records
        Order::destroy($order->id);
        Component::destroy($component1->id);
        Component::destroy($component2->id);
    }

    /**
     * Test the generateRobotName method of the Order model dependent on the calculateMostPrevalentCategory.
     */
    public function testGenerateRobotName()
    {
        // Create a new order with a customer name
        $order = Order::create([
            'customer_name' => 'Test Customer',
        ]);

        // Create two components with SKUs, weights and categories
        $component1 = Component::create([
            'sku' => 'Test-001',
            'weight' => 5,
            'category' => 'Electronics'
        ]);

        $component2 = Component::create([
            'sku' => 'Test-002',
            'weight' => 3,
            'category' => 'Fasteners'
        ]);

        // Associate components with the order and specify quantities
        $componentOrder1 = ComponentOrder::create([
            'order_id' => $order->id,
            'component_id' => $component1->id,
            'quantity' => 2
        ]);

        $componentOrder2 = ComponentOrder::create([
            'order_id' => $order->id,
            'component_id' => $component2->id,
            'quantity' => 3
        ]);

        // Calculate the most prevalent category within the order
        $mostPrevalentCategory = $order->calculateMostPrevalentCategory($order);

        // Assert that the most prevalent category matches 'Electronics'
        $this->assertEquals('Electronics', $mostPrevalentCategory);

        // Generate a robot name
        $robotName = $order->generateRobotName($order);

        // An array of possible prefixes for the robot name
        $possiblePrefixes = ['Electro', 'Circuit', 'Wired', 'Diode', 'Fuse'];

        // Initialize a flag to indicate if a match is found
        $matchFound = false;

         // Loop through the array of possible prefixes to check if any of them are part of the robot name
        foreach ($possiblePrefixes as $prefix) {
            // Check if the robot name contains the current prefix
            if (strpos($robotName, $prefix) !== false) {
                $matchFound = true;
                break; // Exit the loop early if a match is found
            }
        }

        // Assert that a match was found in the robot name
        $this->assertTrue($matchFound);

        // Clean up by deleting the created records
        Order::destroy($order->id);
        Component::destroy($component1->id);
        Component::destroy($component2->id);
    }
}
