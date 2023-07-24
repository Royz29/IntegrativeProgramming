<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'name', 'quantity', 'image', 'price'];

    protected $appends = ['total_quantity', 'total_price'];

    public function getTotalQuantityAttribute()
    {
        return $this->quantity;
    }

    public function getTotalPriceAttribute()
    {
        return $this->quantity * $this->price;
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }


}
