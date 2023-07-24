<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Models\Product;
use App\Models\Cart;
use App\Observers\ProductObserver;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    private $product;
    
    public function __construct(){
        $this->product = new Product();
        $this->productObserver = new ProductObserver();
        $this->product->attach($this->productObserver);
    }

    
    public function index(Request $request){
        $products = Product::query();

        $category = $request->input('category', 'All');
        if ($category != 'All') {
            $products->where('category', $category);
        }

        $name = $request->input('name');
        if (!empty($name)) {
            $products->where('name', 'LIKE', '%' . $name . '%');
        }

        $products = $products->get();

        return view('products.product', ['products' => $products]);
    }
    
    
    public function addToCart(Request $request) {
        $user = Auth::user();
        $productId = $request->input('product_id');
        $product = Product::find($productId);
    
        if (!$product) {
            abort(404);
        }
    
        $quantity = $request->input('quantity', 1);
    
        if ($product->quantity <= 0 || $quantity >  $product->quantity || is_float($quantity) ) {
            return $this->productObserver->addToCartNotify($product, $quantity);
        }
    
        // Find an existing cart item with the same product ID and the current user's ID
        $cartItem = Cart::where('product_id', $productId)->where('userId', $user->id)->first();
    
        if ($cartItem) {
            // If the cart item already exists, update its quantity and save the changes
            $cartItem->quantity += $quantity;
            $cartItem->save();
        } else {
            // If the cart item doesn't exist, create a new one and save it
            $cartItem = new Cart();
            $cartItem->userId = $user->id;
            $cartItem->name = $product->name;
            $cartItem->quantity = $quantity;
            $cartItem->price = $product->price;
            $cartItem->image = $product->image;
            $cartItem->product_id = $product->id;
            $cartItem->save();
        }
    
        // Update the product quantity and return the response
        $product->quantity -= $quantity;
        $product->save();
        return $this->productObserver->addToCartNotify($product, $quantity);
    }
    
    
    
}
