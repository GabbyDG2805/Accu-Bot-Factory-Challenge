<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Component extends Model
{
    protected $table = 'components';

    protected $fillable = [
        'sku',
        'description',
        'category',
        'weight'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
    ];
}
