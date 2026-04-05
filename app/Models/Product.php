<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'image_url',
        'featured',
        'category',
        'stock',
    ];

    protected $casts = [
        'featured' => 'boolean',
        'price' => 'decimal:2',
        'stock' => 'integer',
    ];
}

