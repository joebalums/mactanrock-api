<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Requisition;
use App\Models\RequisitionDetail;

class RequisitionServices
{

    public function get()
    {
        return Requisition::query()
            ->with('requester')
            ->where('branch_id', request()->user()->branch_id)
            ->latest()
            ->paginate(is_integer(request('paginate',12)) ?request('paginate'):0);
    }

    public function show(int $id)
    {
        return Requisition::query()->with([
            'details' => [
                'product'
            ],
            'requester'
        ])->where('branch_id', request()->user()->branch_id)->findOrFail($id);
    }
    public function create()
    {
        $user = request()->user();
        $requisition = new Requisition();
        $requisition->project_name = request()->get('project_name');
        $requisition->branch_id = $user->branch_id;
        $requisition->user_id = $user->id;
        $requisition->save();

        $items = [];

        $products = Product::query()->whereIn('id', request('products'))->get();

        foreach (request('products') as $key => $product){
            $items[] = [
                'product_id' => $product,
                'quantity' => request()->get('quantity')[$key],
                'from_branch_id' => $products->firstWhere('id', $product)->branch_id,
                'requisition_id' => $requisition->id
            ];
        }

        RequisitionDetail::query()->insert($items);

        return $requisition;
    }
}