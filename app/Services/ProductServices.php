<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ProductServices
{

    public function get()
    {

        return Product::query()
            ->with(['category'])
            ->when( request('keyword'),
                function(Builder $q){
                    $keyword = request('keyword');
                    return $q->whereRaw("CONCAT_WS(' ',name,code,brand) like '%{$keyword}%' ");
                })
            ->latest()
            ->paginate( is_integer(request('paginate',12)) ? request('paginate'):0 );
    }
    public function create(Request $request)
    {
        $product = new Product();
        $this->itemInformation($request, $product);
        $product->save();
        $product->load('category');

        return $product;
    }

    public function update(Request $request , int $id)
    {
        $product = Product::query()->findOrFail($id);
        $this->itemInformation($request, $product);
        $product->save();
        $product->load('category');

        return $product;
    }

    /**
     * @param Request $request
     * @param \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Builder|array|null $product
     * @return void
     */
    public function itemInformation(Request $request, \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Builder|array|null $product): void
    {
        $product->name = $request->get('name');
        $product->code = $request->get('code');
        $product->brand = $request->get('brand',"");
        $product->description = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', "", $request->get('description', ''));
        $product->category_id = $request->get('category_id');
        $product->unit_measurement = $request->get('unit_measurement');
        $product->unit_value = $request->get('unit_value');

    }
}