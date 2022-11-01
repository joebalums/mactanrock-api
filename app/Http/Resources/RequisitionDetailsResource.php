<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RequisitionDetailsResource extends JsonResource
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
            'product' => ProductResource::make($this->whenLoaded('product')),
            'location' => BranchResource::make($this->whenLoaded('branch')),
            'quantity' => $this->quantity,
            'status' => $this->status,
            'price' => $this->price,
            'price_formatted' => number_format($this->price, 2, '.', ','),
            $this->mergeWhen($this->relationLoaded('product'), fn() => [
                'stock' => $this->quantity ? ($this->quantity <= $this->product->reorder_point ? "reorder" : ($this->quantity <= $this->product->stock_low_level ? "low" : "")):"out",
            ])
        ];
    }
}
