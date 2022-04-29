<?php

namespace App\Http\Resources;

use App\Models\Category;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductApiResource extends JsonResource
{
    public static $wrap = 'product';
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
            'price' => $this->price,
            'published' => $this->published,
            'deleted' => $this->deleted_at !== null,
            'categories' => CategoryApiResource::collection($this->categories),
        ];
    }
}
