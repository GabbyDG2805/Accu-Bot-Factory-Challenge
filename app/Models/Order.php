<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Component;

class Order extends Model
{
    protected $table = 'orders';

    protected $fillable = [
        'customer_name',
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    // Define a many-to-many relationship with the Component model, using the 'component_orders' pivot table
    public function components()
    {
        return $this->belongsToMany(Component::class, 'component_orders')->withPivot('quantity');
    }

    // Calculate the total weight of all components associated with this order
    public function calculateTotalWeight()
    {
        return $this->components->sum(function ($component) {
            // Calculate the weight of each component in this order by multiplying weight by quantity
            return $component->weight * $component->pivot->quantity;
        });
    }

    //Calculate the most prevalent category of components within an order
    public function calculateMostPrevalentCategory($order)
    {
        // Get the components associated with the order
        $components = $order->components;

        $categoryCounts = [];

        // Iterate through the components and count occurrences of each category
        foreach ($components as $component) {
            $category = $component->category;

            // Increment the count for this category or initialize it to 1 if it doesn't exist
            $categoryCounts[$category] = ($categoryCounts[$category] ?? 0) + 1;
        }

        // Find the category with the highest count
        $mostPrevalentCategory = array_search(max($categoryCounts), $categoryCounts);

        return $mostPrevalentCategory;
    }
}
