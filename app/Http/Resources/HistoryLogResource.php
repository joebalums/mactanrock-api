<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class HistoryLogResource extends JsonResource
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
            'message' => str_replace('InventoryLocation', 'Inventory', $this->message),
            'meta' => $this->meta ?  json_decode($this->meta) : null,
            'model_id' => $this->model_id,
            'model_type' => $this->model_type,
            'performed_at' => $this->performed_at,
            'user_id' => $this->user_id,
            'user' => UserResource::make($this->user),
            'user_type' => $this->user_type,
        ];
    }
}
