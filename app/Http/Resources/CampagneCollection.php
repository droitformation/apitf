<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class CampagneCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $campagne = $this->collection->first()->campagne;

        $this->collection->transform(function ($content) {
            return (new Campagne($content));
        });

        return [
            'data' => [
                'blocs' => $this->collection,
                'campagne' => (new Newsletter($campagne))
            ],
        ];
    }
}
