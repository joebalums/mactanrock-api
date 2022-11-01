<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'name' => $this->name,
            'code' => $this->code,
            'brand' => $this->brand ?? "",
            'description' =>  $this->description,
            'unit_value' => $this->unit_value,
            'unit_measurement' => $this->unit_measurement,
            'stock_low_level' => $this->stock_low_level ?: 0,
            'reorder_point' => $this->reorder_point ?: 0,
            $this->mergeWhen(!is_null($this->total_quantity), fn() => [
                'location' => BranchResource::make($this->whenLoaded('location')),
                'total_quantity' => $this->total_quantity,
                'quantity' => $this->quantity,
                'price' => $this->price,

            ])
        ];
    }
}
