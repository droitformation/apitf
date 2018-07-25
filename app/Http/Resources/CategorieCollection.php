<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CategorieCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $collection = $this->collection;

        $parents = $collection->filter(function ($value, $key) {
            return isset($value->parent) ? $value : false;
        })->map(function ($value, $key) {
            return (new Categorie($value->parent));
        })->unique('id');

        $collection = $collection->sortBy('parent_id')->groupBy('parent_id')->map(function ($values, $key) {
            return $values->transform(function ($categorie) {
                return (new Categorie($categorie));
            });
        });

        return [
            'data' => [
                'categories' => $collection,
                'parents' => $parents
            ],
        ];
    }
}
