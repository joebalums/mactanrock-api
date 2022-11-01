<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Resources\ProductResource;
use App\Services\InventoryServices;

class InventoryController
{

    public function index(InventoryServices $services)
    {
        return ProductResource::collection($services->getList());
    }
}