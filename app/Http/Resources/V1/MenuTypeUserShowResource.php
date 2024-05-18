<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MenuTypeUserShowResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $title = json_decode($this->title , true);
        return [
            'id' => $this->id,
            'title' => [
                'uz' => $title['uz'],
                'ru' => $title['ru']
            ],
            'time_from' => $this->time_from,
            'time_to' => $this->time_to,
        ];
    }
}
