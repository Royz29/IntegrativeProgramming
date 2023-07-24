<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\InventoryRepository;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class InventoryController extends Controller
{
    protected $inventoryRepository;

    // Constructor method to inject an instance of InventoryRepository into the controller
    public function __construct(InventoryRepository $inventoryRepository)
    {
        $this->inventoryRepository = $inventoryRepository;
    }

    // Display a listing of the inventory
    public function index(Request $request)
    {
        $user = Auth::user();
        if (Auth::check() && $user->role == 'admin') {

            $keyword = $request->get('search');

            $inventory = $this->inventoryRepository->getAllInventory($keyword);

            return view('inventory.index', compact('inventory'));
        }

        Session::flush();

        Auth::logout();

        return redirect('login')->with('success', 'you are not allowed to access');
    }

    // Display the form for creating a new inventory
    public function displayCreatePage()
    {
        $user = Auth::user();
        if (Auth::check() && $user->role == 'admin') {

            return view('inventory.create');
        }

        Session::flush();

        Auth::logout();

        return redirect('login')->with('success', 'you are not allowed to access');
    }

    // Display the form for editing the specified inventory
    public function displayEditPage($id)
    {

        $user = Auth::user();
        if (Auth::check() && $user->role == 'admin') {

            try {
                $id = Crypt::decrypt($id);
            } catch (DecryptException $e) {
                abort(404);
            }

            $inventory = $this->inventoryRepository->findInventoryById($id);

            return view('inventory.edit', compact('inventory'));
        }

        Session::flush();

        Auth::logout();

        return redirect('login')->with('success', 'you are not allowed to access');
    }

    // Store a newly created inventory in storage
    public function storeInventory(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:100', Rule::unique('inventories')],
            'description' => ['required', 'string', 'min:1', 'max:100'],
            'image' => ['required', 'image', 'mimes:jpg,png,jpeg,gif,svg', 'max:2028'],
            'quantity' => ['required', 'integer', 'gte:0'],
            'price' => ['required', 'numeric', 'gt:0']
        ]);

        $inventoryData = [
            'name' => $request->name,
            'description' => $request->description,
            'image' => $request->file('image')->getClientOriginalName(),
            'category' => $request->category,
            'quantity' => $request->quantity,
            'price' => $request->price,
        ];

        $this->inventoryRepository->createInventory($inventoryData);

        return redirect()->route('inventory.index')->with('success', 'Inventory Added Successfully');
    }

    // Update the specified inventory in storage
    public function updateInventory(Request $request, $id)
    {
        $request->validate([
            'name' => ['required', 'string', 'min:3', 'max:100', Rule::unique('inventories')->ignore($request->id)],
            'description' => ['required', 'string', 'min:1', 'max:100'],
            'image' => ['nullable', 'image', 'mimes:jpg,png,jpeg,gif,svg', 'max:2028'],
            'quantity' => ['required', 'integer', 'gte:0'],
            'price' => ['required', 'numeric', 'gt:0']
        ]);

        $file_name = $request->hidden_inventory_image;

        // Update the image file name if a new image has been uploaded
        if ($request->hasFile('image')) {
            $file_name = time() . '.' . request()->image->getClientOriginalExtension();
            request()->image->move(public_path('images'), $file_name);
        }

        $inventoryData = [
            'name' => $request->name,
            'description' => $request->description,
            'image' => $file_name,
            'category' => $request->category,
            'quantity' => $request->quantity,
            'price' => $request->price,
        ];

        // Find the inventory to be updated
        $inventory = $this->inventoryRepository->findInventoryById($id);

        //Check if the inventory data has changed
        $hasChanged = false;

        foreach ($inventoryData as $key => $value) {
            if ($inventory->$key != $value) {
                $hasChanged = true;
                break;
            }
        }

        if (!$hasChanged) {
            return redirect()->back()->withErrors(['error' => 'No changes made to the inventory.'])->withInput();
        }

        $this->inventoryRepository->editInventory($inventoryData, $id);

        return redirect()->route('inventory.index')->with('success', 'Inventory Edited Successfully');
    }

    // Delete the specified inventory from storage.
    public function deleteInventory($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            abort(404);
        }

        $successMessage = $this->inventoryRepository->deleteInventory($id);

        return redirect('inventory')->with('success', $successMessage);
    }
}
