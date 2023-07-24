<?php

namespace App\Observers;

use App\Models\Inventory;
use App\Models\InventoryActivityLog;

class InventoryActivityLogObserver
{
    // Handle the Inventory "created" event.
    public function created(Inventory $inventory): void{
        InventoryActivityLog::create([
            'inventory_name' => $inventory->name,
            'action' => 'be created',
            'old_values' => null,
            'new_values' => $inventory->toJson()
        ]);
    }

    // Handle the Inventory "updated" event.
    public function updated(Inventory $inventory): void{
        $changes = $inventory->getDirty();
        $oldValues = json_encode($inventory->getOriginal());
        $newValues = json_encode($changes);

        InventoryActivityLog::create([
            'inventory_name' => $inventory->name,
            'action' => 'be updated',
            'old_values' => $oldValues,
            'new_values' => $newValues
        ]);
    }

    // Handle the Inventory "deleted" event.
    public function deleted(Inventory $inventory): void{
        InventoryActivityLog::create([
            'inventory_name' => $inventory->name,
            'action' => 'be deleted',
            'old_values' => null,
            'new_values' => null,
        ]);
    }
}
