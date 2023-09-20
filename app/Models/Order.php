<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
}