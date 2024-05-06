<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Panoscape\History\HasHistories;

class RequisitionDetail extends Model
{
    use HasFactory;
    use HasHistories;

    public function getModelLabel()
    {
        return $this->display_name;
    }

    protected $guarded = [];


    public function items(): \Illuminate\Database\Eloquent\Relations\hasMany
    {
        return $this->hasMany(RequisitionItem::class);
    }

    public function location()
    {
        return $this->belongsTo(Branch::class, 'location_id');
    }

    public function requisition()
    {
        return $this->belongsTo(Requisition::class);
    }
}
