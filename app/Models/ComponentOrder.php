<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Order;
use App\Models\Component;

class ComponentOrder extends Model
{
    protected $table = 'component_orders';

    protected $fillable = [
        'order_id',
        'component_id',
        'quantity'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function component()
    {
        return $this->belongsTo(Component::class);
    }
}