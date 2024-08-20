<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'quantity',
        'price',
        'created_at',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}