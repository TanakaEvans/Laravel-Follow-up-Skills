<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'quantity',
        'price',
        'submitted_at'
    ];

    protected $casts = [
        'submitted_at' => 'datetime',
        'price' => 'decimal:2',
        'quantity' => 'integer',
    ];

    /**
     * Calculate the total value of the product
     *
     * @return float
     */
    public function getTotalValueAttribute()
    {
        return $this->quantity * $this->price;
    }
}
