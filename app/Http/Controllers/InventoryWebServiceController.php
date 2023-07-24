<?php

namespace App\Http\Controllers;

use App\Models\Inventory;

class InventoryWebServiceController extends Controller
{
    // Get all the inventory
    public function getAllInventory(){
        // Set the content type of the response to JSON
        header("Content-Type:application/json");

        $inventory = Inventory::all();

        if($inventory->count() > 0){
            return response()->json([
                'status' => 200,
                'status_message' => 'Inventory Found',
                'inventory' => $inventory
            ], 200, [], JSON_PRETTY_PRINT);
        }
        else{
            return response()->json([
                'status' => 404,
                'status_message' => 'No Inventory Record',
                'inventory' => null
            ], 404, [], JSON_PRETTY_PRINT);
        }
    }

    // Searches the inventory by name
    public function searchByName($name){
        // Set the content type of the response to JSON
        header("Content-Type:application/json");

        $inventory = Inventory::where('name', 'LIKE', "%$name%")->get();

        if(!$inventory->isEmpty()){
            return response()->json([
                'status' => 200,
                'status_message' => 'Inventory Found',
                'inventory' => $inventory
            ], 200, [], JSON_PRETTY_PRINT);
        }else{
            return response()->json([
                'status' => 404,
                'status_message' => 'Inventory Not Found',
                'inventory' => null
            ], 404, [], JSON_PRETTY_PRINT);
        }
    }

    // Searches the inventory by category
    public function searchByCategory($category){
        // Set the content type of the response to JSON
        header("Content-Type:application/json");

        $inventory = Inventory::where('category', 'LIKE', "%$category%")->get();

        if(!$inventory->isEmpty()){
            return response()->json([
                'status' => 200,
                'status_message' => 'Inventory Found',
                'inventory' => $inventory
            ], 200, [], JSON_PRETTY_PRINT);
        }else{
            return response()->json([
                'status' => 404,
                'status_message' => 'Inventory Not Found',
                'inventory' => null
            ], 404, [], JSON_PRETTY_PRINT);
        }
    }
}
