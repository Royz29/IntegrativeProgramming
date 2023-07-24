<?php

namespace App\Repositories;

use App\Models\Inventory;

class InventoryRepository{
    // Retrieve all inventory items, optionally filtered by keyword.
    public function getAllInventory($keyword = null){
        $inventory = Inventory::latest();

        if (!empty($keyword)) {
            $inventory = $inventory->where('name', 'LIKE', "%$keyword%")
                                    ->orWhere('category', 'LIKE', "%$keyword%");
        }

        return $inventory->get();
    }

    // Find inventory item by ID.
    public function findInventoryById($id){
        return Inventory::findOrFail($id);
    }

    // Create a new inventory item and save to database.
    public function createInventory($data){
        $file_name= time() . '.' . request()->image->getClientOriginalExtension();

        request()->image->move(public_path('images'), $file_name);

        $inventory = Inventory::create([
            'name' => $data['name'],
            'description' => $data['description'],
            'image' => $file_name,
            'category' => $data['category'],
            'quantity' => $data['quantity'],
            'price' => $data['price'],
        ]);

        return $inventory;
    }

    // Update an existing inventory item and save to database.
    public function editInventory($data, $id){
        $inventory = Inventory::find($id);

        $inventory->name = $data['name'];
        $inventory->description = $data['description'];
        $inventory->image = $data['image'];
        $inventory->category = $data['category'];
        $inventory->quantity = $data['quantity'];
        $inventory->price = $data['price'];

        $inventory->save();

        return $inventory;
    }

    // Delete an existing inventory item and save to database.
    public function deleteInventory($id){
        $inventory = Inventory::findOrFail($id);
        $image_path = public_path()."/images/";
        $image = $image_path. $inventory->image;

        if(file_exists($image)){
            @unlink($image);
        }

        $inventory->delete();

        return "Inventory item deleted successfully";
    }
}
