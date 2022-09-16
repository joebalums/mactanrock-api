<?php

namespace App\Services;

use App\Enums\InventoryActionType;
use App\Models\Product;

class ProductServices
{

    public function create()
    {
        $request = request();
        $product = new Product();
        $product->name = $request->get('name');
        $product->code = $request->get('code');
        $product->description = $request->get('description');
        $product->category_id = $request->get('category_id');
        $product->unit_measurement = $request->get('unit_measurement');
        $product->unit_value = $request->get('unit_value');
        $product->price = $request->get('price');
        $product->branch_id = $request->get('branch_id');
        $product->save();

        return $product;
    }
}