<?php

use Illuminate\Database\Seeder;
use App\Models\Inventory;

class InventoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $inventories = [
            [
                'name' => 'iPhone 14 Pro Max',
                'description' => 'iPhone 14 Pro Max',
                'category' => 'Smartphone',
                'quantity' => 30,
                'price' => 9000,
                'image' => '1682875039.jpeg',
            ],
            [
                'name' => 'Xiaomi Redmi Note 12',
                'description' => 'Xiaomi Redmi Note 12',
                'category' => 'Smartphone',
                'quantity' => 40,
                'price' => 1400,
                'image' => '1682875082.jpg',
            ],
            [
                'name' => 'MacBook Pro 16"',
                'description' => 'MacBook Pro 16"',
                'category' => 'Laptop',
                'quantity' => 20,
                'price' => 9000,
                'image' => '1682875128.jpeg',
            ],
            [
                'name' => 'Razer Blade 15',
                'description' => 'Razer Blade 15',
                'category' => 'Laptop',
                'quantity' => 25,
                'price' => 4550,
                'image' => '1682875163.jpeg',
            ],
            [
                'name' => 'Xiaomi Mi TV P1 55 Inch Smart Android Television',
                'description' => 'Xiaomi Mi TV P1 55 Inch Smart Android Television',
                'category' => 'Smart TV',
                'quantity' => 45,
                'price' => 1700,
                'image' => '1682875226.jpeg',
            ],
            [
                'name' => 'Samsung 43" Smart FHD T6000',
                'description' => 'Samsung 43" Smart FHD T6000',
                'category' => 'Smart TV',
                'quantity' => 50,
                'price' => 1600,
                'image' => '1682875263.jpeg',
            ],
        ];

        foreach ($inventories as $inventory) {
            Inventory::create($inventory);
        }
    }
}
