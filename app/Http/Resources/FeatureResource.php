<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FeatureResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $this->load('Category');
        return [
            'id' => $this->id,
            'title' => $this->title,
            'image' => $this->image,
            'category_id' => $this->category_id,
            'Category' => $this->Category
        ];
    }
}
