<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Order;
use App\Models\Component;
use App\Models\ComponentOrder;

class OrderTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Create a new order with a customer name
        $this->order = Order::create([
            'customer_name' => 'Test Customer',
        ]);

        // Create two components with SKUs, weights, and categories
        $this->component1 = Component::create([
            'sku' => 'Test-001',
            'weight' => 5,
            'category' => 'Electronics'
        ]);

        $this->component2 = Component::create([
            'sku' => 'Test-002',
            'weight' => 3,
            'category' => 'Fasteners'
        ]);

        // Associate components with the order and specify quantities
        $this->componentOrder1 = ComponentOrder::create([
            'order_id' => $this->order->id,
            'component_id' => $this->component1->id,
            'quantity' => 2
        ]);

        $this->componentOrder2 = ComponentOrder::create([
            'order_id' => $this->order->id,
            'component_id' => $this->component2->id,
            'quantity' => 3
        ]);
    }

    /**
     * Test the calculateTotalWeight method of the Order model.
     */
    public function testCalculateTotalWeight()
    {
        // Calculate the total weight
        $totalWeight = $this->order->calculateTotalWeight();

        // Assert that the total weight is calculated correctly
        $this->assertEquals(5 * 2 + 3 * 3, $totalWeight);
    }

    /**
     * Test the generateRobotName method of the Order model dependent on the calculateMostPrevalentCategory.
     */
    public function testGenerateRobotName()
    {
        // Calculate the most prevalent category within the order
        $mostPrevalentCategory = $this->order->calculateMostPrevalentCategory($this->order);

        // Assert that the most prevalent category matches 'Electronics'
        $this->assertEquals('Electronics', $mostPrevalentCategory);

        // Generate a robot name
        $robotName = $this->order->generateRobotName($this->order);

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
    }

    protected function tearDown(): void
    {
        // Clean up by deleting the created records
        Order::destroy($this->order->id);
        Component::destroy($this->component1->id);
        Component::destroy($this->component2->id);
    }
}
