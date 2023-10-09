<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $this->load('category');
        return [
            'id' => $this->id,
            'name' => $this->name,
            'image' => asset($this->image),
            'description' => $this->description,
            'category_id' => $this->category_id,
            'price' => $this->price,
            'calories' => $this->calories,
            'visibility' => $this->visibility,
            'position' => $this->position,
            'category' => $this->category

        ];
    }
}
