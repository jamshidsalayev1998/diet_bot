<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MenuPartProductsUserShowResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            // 'id' => $this->id,
            // 'title' => json_decode($this->title),
            // 'image' => $this->image ? config('app.url').$this->image:null,
            // 'measure_type' => new MeasureTypeUserShowResource($this->measure_type),
            // 'calories' => $this->calories,
            // 'permission_description' => json_decode($this->permission_description),
            // 'measure_cup' => new MeasureCupUserShowResource($this->measure_cup),
            // 'measure_cup_value' => $this->measure_cup_value

            'id' => $this->id,
            'measure_cup_count' => $this->measure_cup_count,
            'calories' => $this->calories,
            'measure_type_count' => $this->measure_type_count,
            'product' => new ProductUserShowResource($this->product)
        ];
    }
}
