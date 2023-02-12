<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Resources\ProductResource;
use App\Services\InventoryServices;
use Illuminate\Validation\Rule;

class InventoryController
{

    public function index(InventoryServices $services)
    {
        request()->validate([
            'column' => ['nullable', Rule::in(['name','description','quantity','code','brand'])],
            'direction' => ['nullable', Rule::in(['asc','desc'])]
        ]);
        return ProductResource::collection($services->getList());
    }

    public function status(InventoryServices $services)
    { 
        return [
            'low'=> ProductResource::collection($services->getLowStock()),
            'empty' => ProductResource::collection($services->getEmptyStock())
        ];
    }
}