<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductLocationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'product_id' => $this->product_id,
            'branch_id' => $this->branch_id,
            'price' => $this->price,
            'total_quantity' => $this->total_quantity,
            'quantity' => $this->quantity,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'business_unit' => $this->business_unit,
            'stock_low_level' => $this->stock_low_level,
            'reorder_point' => $this->reorder_point,
            'branch' => BranchResource::make($this->whenLoaded('branch')),
            'inventory' => InventoryResource::make($this->whenLoaded('inventory')),
            'product' => ProductResource::make($this->whenLoaded('product')),
            'location' => BranchResource::make($this->whenLoaded('branch')),
        ];
    }
}
