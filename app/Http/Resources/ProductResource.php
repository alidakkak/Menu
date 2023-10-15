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
        $this->load('SubCategory');
        return [
            'id' => $this->id,
            'name' => $this->name,
            'ar_name' => $this->ar_name,
            'image' => asset($this->image),
            'description' => $this->description,
            'description_arabic' => $this->description_arabic,
            'sub_category_id' => $this->sub_category_id,
            'price' => $this->price,
            'calories' => $this->calories,
            'visibility' => $this->visibility,
            'position' => $this->position,
            'SubCategory' => $this->SubCategory

        ];
    }
}
