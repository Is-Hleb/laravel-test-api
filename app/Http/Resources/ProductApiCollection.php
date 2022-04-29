<?php

namespace App\Http\Resources;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Psy\Util\Json;

class ProductApiCollection extends ResourceCollection
{
    public $collects = ProductApiResource::class;
    public static $wrap = 'product';

    public static function apiFilter(array $settings)
    {
        $expression = [];
        $categoryExpression = [];
        foreach ($settings['filter'] as $filter) {
            if ($filter['exp'] === 'like') {
                $filter['value'] = '%' . $filter['value'] . '%';
            }

            if (str_contains($filter['attr'], 'category')) {
                $filter['attr'] = substr($filter['attr'], strpos($filter['attr'], '.') + 1);
                $categoryExpression[] = [
                    $filter['attr'], $filter['exp'], $filter['value']
                ];
                continue;
            }
            $expression[] = [
                $filter['attr'], $filter['exp'], $filter['value']
            ];
        }

        if (isset($settings['withDeleted']) && $settings['withDeleted'] == true) {
            $builder = Product::withTrashed()->where($expression);
        } else {
            $builder = Product::where($expression);
        }

        if (!empty($categoryExpression)) {
            $builder->whereHas('categories', function (Builder $query) use ($categoryExpression) {

                foreach ($categoryExpression as $exp) {
                    if($exp[0] === 'id') {
                        $query->whereIn('categories.id',[$exp[2]]);
                    } else {
                        $query->where(...$exp);
                    }
                }
                return $query;
            });
        }
        return new self($builder->get());
    }

    /**
     * Transform the resource collection into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return $this->collection;
    }
}
