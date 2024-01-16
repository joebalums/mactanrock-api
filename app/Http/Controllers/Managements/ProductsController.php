<?php

namespace App\Http\Controllers\Managements;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Imports\ImportProducts;
use App\Models\Product;
use App\Services\ProductServices;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ProductsController extends Controller
{
    public function index(ProductServices $services)
    {
        return ProductResource::collection($services->get());
    }

    public function store(ProductRequest $request, ProductServices $services)
    {
        return ProductResource::make($services->create($request));
    }



    public function import(Request $request)
    {
        $ImportClass =  new ImportProducts($request->get('category_id'));
        $result = Excel::import(
            $ImportClass,
            $request->file('file')
        );
        return response()->json([
            200,
        ]);
    }

    public function update(ProductRequest $request, ProductServices $services, int $id)
    {
        return ProductResource::make($services->update($request, $id));
    }

    public function show(int $id)
    {
        $product = Product::query()->with(['category'])->findOrFail($id);
        return ProductResource::make($product);
    }
}
