<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RequisitionResource extends JsonResource
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
            'project_name' => $this->project_name,
            'requester' => UserResource::make($this->whenLoaded('requester')),
            'details' => RequisitionDetailsResource::collection($this->whenLoaded('details'))
        ];
    }
}
