<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Categorie extends JsonResource
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
            'id'    => $this->id,
            'title' => $this->title,
            'image' => asset('files/pictos/'.$this->image, env('SECURE_ASSET')),
            'parent_id' => $this->parent_id,
            'parent' => isset($this->parent) ? $this->parent : null,
        ];
    }
}
