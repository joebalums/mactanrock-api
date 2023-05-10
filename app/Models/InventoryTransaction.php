<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryTransaction extends Model
{
    use HasFactory;

    public function inventory(): BelongsTo
    {
        return $this->belongsTo(Inventory::class, 'inventory_id');
    }
    public function receive(): BelongsTo
    {
        return $this->belongsTo(Receive::class, 'receive_id');
    }
    public function request(): BelongsTo
    {
        return $this->belongsTo(Requisition::class, 'from_request_id');
    }
}
