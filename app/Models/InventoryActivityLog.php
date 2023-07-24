<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryActivityLog extends Model
{
    use HasFactory;

    protected $table = 'inventory_activity_logs'; // set the table name

    protected $fillable = [
        'inventory_name', // the inventory name
        'action', // e.g. created, updated, deleted
        'old_values', // serialized array of the old attribute values before the action was taken
        'new_values', // serialized array of the new attribute values after the action was taken
        'created_at', // automatically set by Laravel
    ];

    public function inventory()
    {
        return $this->belongsTo(Inventory::class);
    }
}
