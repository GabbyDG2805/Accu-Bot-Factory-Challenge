<?php

namespace Tests\Feature;

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

        // Calculate the total weight of components and update the order's total weight
        $totalWeight = $this->order->calculateTotalWeight();
        $this->order->total_weight = $totalWeight;
        $this->order->save();

        // Generate a robot name based on component categories and update the order's robot name
        $robotName = $this->order->generateRobotName($this->order);
        $this->order->robot_name = $robotName;
        $this->order->save();
    }

    /**
     * Test the index method to display orders.
     *
     * @return void
     */
    public function testIndex()
    {
        // Send a GET request to the index route
        $response = $this->get(route('orders.index'));

        // Assert that the response status is OK (200)
        $response->assertStatus(200);

        // Assert that the index view is being used
        $response->assertViewIs('orders.index');

        // Assert that the response contains the orders' data
        $response->assertViewHas('orders');

        // Assert that the orders are being displayed in the response
        $response->assertSee($this->order->id);
        $response->assertSee($this->order->customer_name);
        $response->assertSee($this->order->total_weight);
        $response->assertSee($this->order->robot_name);
    }

    /**
     * Test the show method to display the specified order.
     *
     * @return void
     */
    public function testShow()
    {
        // Send a GET request to the show route with the order ID
        $response = $this->get(route('orders.show', ['order' => $this->order->id]));

        // Assert that the response status is OK (200)
        $response->assertStatus(200);

        // Assert that the show view is being used
        $response->assertViewIs('orders.show');

        // Assert that the response contains the order's data
        $response->assertViewHas('order', $this->order);

        // Assert that the order details are being displayed in the response
        $response->assertSee($this->order->id);
        $response->assertSee($this->order->customer_name);
        $response->assertSee($this->order->total_weight);
        $response->assertSee($this->order->robot_name);

        $response->assertSee($this->component1->id);
        $response->assertSee($this->component1->sku);
        $response->assertSee($this->component1->weight);
        $response->assertSee($this->component1->category);
        $response->assertSee($this->componentOrder1->quantity);

        $response->assertSee($this->component2->id);
        $response->assertSee($this->component2->sku);
        $response->assertSee($this->component2->weight);
        $response->assertSee($this->component2->category);
        $response->assertSee($this->componentOrder2->quantity);
    }

    /**
     * Test the edit method to show the form for editing the robot name.
     *
     * @return void
     */
    public function testEdit()
    {
        // Send a GET request to the edit route with the order ID
        $response = $this->get(route('orders.edit', ['order' => $this->order->id]));

        // Assert that the response status is OK (200)
        $response->assertStatus(200);

        // Assert that the edit view was returned
        $response->assertViewIs('orders.edit');

        // Assert that the order data is passed to the view
        $response->assertViewHas('order', $this->order);

        // Assert that the robot name of the order is shown
        $response->assertSee($this->order->robot_name);
    }

    /**
     * Test the update method to update the robot name in storage.
     *
     * @return void
     */
    public function testUpdate()
    {
        $newRobotName = 'TestBot';

        // Send a PUT request to the update route with the updated data
        $response = $this->put(route('orders.update', ['order' => $this->order->id]), [
            'robot_name' => $newRobotName,
        ]);

        // Assert that the response redirects to the show route for the updated order
        $response->assertRedirect(route('orders.show', ['order' => $this->order->id]));

        // Reload the order from the database to get the latest data
        $updatedOrder = $this->order->fresh();

        // Assert that the robot name of the order has been updated
        $this->assertEquals($newRobotName, $updatedOrder->robot_name);
    }

    /**
     * Test the search method to search for orders based on the provided query.
     *
     * @return void
     */
    public function testSearch()
    {
        // Create a new order with a different customer name
        $newOrder = Order::create([
            'customer_name' => 'New Customer',
        ]);

        // Send a GET request to the search route with a query parameter
        $query = 'New Customer'; // Change this to the query you want to test
        $response = $this->get(route('orders.search', ['query' => $query]));

        // Assert that the response status is OK (200)
        $response->assertStatus(200);

        $oldOrder = $this->order;

        // Assert that the response view contains the orders matching the query
        $response->assertViewHas('orders', function ($orders) use ($oldOrder, $newOrder) {
            return $orders->contains($newOrder) && !$orders->contains($oldOrder);
        });
    }

    protected function tearDown(): void
    {
        // Clean up by deleting the created records
        Order::destroy($this->order->id);
        Component::destroy($this->component1->id);
        Component::destroy($this->component2->id);
    }
}