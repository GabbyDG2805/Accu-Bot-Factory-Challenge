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

    public function components()
    {
        return $this->belongsToMany(Component::class)->withPivot('quantity');
    }
}
