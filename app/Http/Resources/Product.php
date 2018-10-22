<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Product extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'     => $this->id,
            'title'  => $this->title,
            'teaser' => $this->teaser,
            'image'  => asset('files/products/'.$this->image, env('SECURE_ASSET')),
            'description' => $this->description,
        ];
    }
}
