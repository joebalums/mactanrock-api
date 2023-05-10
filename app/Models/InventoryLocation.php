<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryLocation extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function location()
    {
        return $this->belongsTo(Branch::class,'branch_id');
    } 
    public function inventory()
    {
        return $this->hasOne(Inventory::class, 'inventory_location_id');
    } 
}
