<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\ComponentOrder;
use App\Models\Order;
use App\Models\Component;

class ComponentOrderTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Create an order
        $this->order = Order::create([
            'customer_name' => 'Test Customer',
        ]);

        // Create a component
        $this->component = Component::create([
            'sku' => 'Test-001',
            'weight' => 5,
        ]);

        // Create a ComponentOrder associated with the order and component
        $this->componentOrder = ComponentOrder::create([
            'order_id' => $this->order->id,
            'component_id' => $this->component->id,
            'quantity' => 2,
        ]);
    }

    public function testComponentOrderBelongsToOrder()
    {
        // Retrieve the order through the ComponentOrder relationship
        $retrievedOrder = $this->componentOrder->order;

        // Assert that the retrieved order is the same as the one we created
        $this->assertInstanceOf(Order::class, $retrievedOrder);
        $this->assertEquals($this->order->id, $retrievedOrder->id);
    }

    public function testComponentOrderBelongsToComponent()
    {
        // Retrieve the component through the ComponentOrder relationship
        $retrievedComponent = $this->componentOrder->component;

        // Assert that the retrieved component is the same as the one we created
        $this->assertInstanceOf(Component::class, $retrievedComponent);
        $this->assertEquals($this->component->id, $retrievedComponent->id);
    }

    protected function tearDown(): void
    {
        // Clean up by deleting the created records
        Order::destroy($this->order->id);
        Component::destroy($this->component->id);
    }
}
