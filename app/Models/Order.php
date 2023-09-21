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

    public function generateRobotName($order)
    {
        // Get the most prevalent category
        $mostPrevalentCategory = $this->calculateMostPrevalentCategory($order);

        // Define category-specific words
        $categoryPrefixes = [
            'Cutting Tools' => ['Cut', 'Blade', 'Sharp', 'Cutter', 'Cutto'],
            'Electronics' => ['Electro', 'Circuit', 'Wired', 'Diode', 'Fuse'],
            'Fasteners' => ['Bolt', 'Screw', 'Nut', 'Clip', 'Fasten'],
            'Materials' => ['Matter', 'Substance', 'Solid', 'Materio'],
            'Mechanical Components' => ['Mechano', 'Mecha', 'Gear'],
            'Pneumatics' => ['Pneuma', 'Pneumo', 'Air', 'Pressure'],
            'Structural Support' => ['Structo', 'Builder', 'Support', 'Buildo']
        ];

        $suffixes = ['Tron', 'Bot', 'Prime', '-1000', 'Naut', 'Rover', 'Xplorer', 'Boy', 'Man', 'Chip', 'Amigo', 'Cybo', 'Droid', 'X', 'Tech', 'Tronics', 'Gizmo', 'Machina', 'Botix', 'Techtron'];

        // Select words based on the most prevalent category
        $prefixes = $categoryPrefixes[$mostPrevalentCategory] ?? ['Bot', 'Mech', 'Robo'];

        // Randomly choose a category-specific prefix and a suffix
        $prefix = $prefixes[array_rand($prefixes)];
        $suffix = $suffixes[array_rand($suffixes)];

        // Combine the words to create the robot name
        $robotName = ucfirst($prefix) . ucfirst($suffix);

        return $robotName;
    }
}
