<?php

namespace App\Repositories;

use App\Models\Cart;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class CartRepository{

    protected $model;

    public function __construct(Cart $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return Cart::all();
    }

    public function getById($id)
    {
        return Cart::findOrFail($id);
    }

    public function updateQuantity($id, $quantity)
    {
        $cart = Cart::findOrFail($id);
        $product = Product::findOrFail($cart->product_id);
        $cart_quantity = $product->quantity + $cart->quantity;

        if ($quantity > $cart_quantity) {
            return false;
        }

        $product->quantity -= ($quantity - $cart->quantity);
        $product->save();
        $cart->quantity = $quantity;
        $cart->save();

        return true;
    }

    public function delete($id)
    {
        $cart = Cart::findOrFail($id);
        $product = Product::findOrFail($cart->product_id);
        $product->quantity += $cart->quantity;
        $product->save();
        $cart->delete();
    }

    public function getAll()
    {        
        $userId = Auth::user()->id;
    
        return Cart::where('userId', $userId)->get();
    }
    

    public function deleteAll()
    {        
        return $this->model->truncate();
    }

    public function deleteByUserId($userId)
    {
        return Cart::where('userId', $userId)->delete();
    }

}
