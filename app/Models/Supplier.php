<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Panoscape\History\HasHistories;

class Supplier extends Model
{
    use HasFactory;
    use HasHistories;

    public function getModelLabel()
    {
        return $this->display_name;
    }

    protected $guarded = [];


    public function banks()
    {
        return $this->hasMany(SupplierBank::class);
    }

    public function contacts()
    {
        return $this->hasMany(SupplierContact::class);
    }
}
