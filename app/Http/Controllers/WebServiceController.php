<?php

namespace App\Http\Controllers;

use App\Repositories\CartRepository;
use App\Models\Product;
use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;  
use Illuminate\Validation\ValidationException;

class WebServiceController extends Controller
{
    private $cartRepository;

    public function __construct(CartRepository $cartRepository){
        $this->cartRepository = $cartRepository;
    }

    public function getAllCarts(){
        $carts = $this->cartRepository->all();
        return response()->json($carts, 200, ['Content-Type' => 'application/json']);
    }

    public function getCartsByName($name){
        $carts = Cart::where('name', $name)->get();
        if ($carts->isEmpty()) {
            abort(404);
        }

        return response()->json($carts, 200, ['Content-Type' => 'application/json']);
    }
    
    public function getCartByUserId($userId){
        $carts = Cart::where('userId', $userId)->get();
    
        if ($carts->isEmpty()) {
            abort(404);
        }

        return response()->json($carts, 200, ['Content-Type' => 'application/json']);
    }

    public function getCartById($Id){
        $carts = Cart::where('id', $Id)->get();
        if ($carts->isEmpty()) {
            abort(404);
        }

        return response()->json($carts, 200, ['Content-Type' => 'application/json']);
    }
    
    public function getCartByDate($date){
        $carts = Cart::where('created_at', $date)->get();
        if ($carts->isEmpty()) {
            abort(404);
        }

        return response()->json($carts, 200, ['Content-Type' => 'application/json']);
    }

    public function getAllProducts(Request $request){
        $products = Product::query();        

        $products = $products->get();

        return response()->json($products, 200,['Content-Type' => 'application/json']);        
    }

    public function getProductByName($name){
        $products = Product::where('name', 'LIKE', '%' . $name . '%')->get();
    
        if ($products->isEmpty()) {
            abort(404);
        }
        return response()->json($products, 200,['Content-Type' => 'application/json']);        
    }
    
    public function getProductByCategory($category){
        $products = Product::query();
    
        if ($category != 'All') {
            $products->where('category', $category);
        }
    
        $products = $products->get();
    
        if ($products->isEmpty()) {
            abort(404);
        }
        return response()->json($products, 200,['Content-Type' => 'application/json']);  
    }

    public function showAllCartWeb(){
    // Retrieve data from the Cart Web Service
        $allCarts = $this->getAllCarts()->getContent();
        $cartsByName = $this->getCartsByName('asdasd')->getContent();
        $cartsByUserId = $this->getCartByUserId(3)->getContent();
        $cartsById = $this->getCartById(6)->getContent();
        $cartsByDate = $this->getCartByDate('2023-05-17 12:26:22')->getContent();

        // Retrieve data from the Product Web Service
        $allProducts = $this->getAllProducts(new Request())->getContent();
        $productsByName = $this->getProductByName('asdasd')->getContent();
        $productByCategory = $this->getProductByCategory('All')->getContent();

        return view('webapi.showAllCartWeb', compact('allCarts', 'cartsByName', 'cartsByUserId', 'cartsById', 'cartsByDate', 'allProducts', 'productsByName', 'productByCategory'));
}

}