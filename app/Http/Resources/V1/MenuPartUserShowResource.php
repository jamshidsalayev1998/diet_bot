<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MenuPartUserShowResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'menu_type_id' => $this->menu_type_id,
            'menu_type' => new MenuTypeUserShowResource($this->menu_type),
            'menu_size' => new MenuSizeUserShowResource($this->menu_size),
            'calories' => $this->calories,
            'menu_part_products' => MenuPartProductsUserShowResource::collection($this->menu_part_products)

        ];
    }
}
