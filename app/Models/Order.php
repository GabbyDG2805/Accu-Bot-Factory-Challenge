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
}
