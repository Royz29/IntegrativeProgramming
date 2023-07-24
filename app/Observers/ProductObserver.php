<?php

namespace App\Observers;

use SplObserver;
use SplSubject;
use App\Models\Product;

class ProductObserver implements SplObserver
{
    public function update(SplSubject $subject)
    {
        if ($subject instanceof Product) {
            // Notify Observers that a change has occurred
            $subject->notify();
        }
    }

    public function addToCartNotify(Product $product, $quantity)
    {
        if ($product->quantity == 0 && $quantity > $product->quantity) {
            $message = 'Product out of stock!';
        } else if($quantity >  $product->quantity){
            $message = 'Not enough Product!';
        } else if(is_float($quantity)){
            $message = 'Cannot have decimal!';
        }else{
            $message = $quantity . ' ' . $product->name . ' added to cart.';
        }
        
        return redirect()->back()->with('success', $message);
    }

}

