<?php

namespace App\Http\Resources;

use App\Models\Category;
use Illuminate\Http\Resources\Json\ResourceCollection;

class CategoryApiCollection extends ResourceCollection
{
    public $collects = CategoryApiResource::class;
    public static $wrap = 'category';
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return $this->collection;
    }
}
