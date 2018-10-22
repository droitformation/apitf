<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Author extends JsonResource
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
            'id' => $this->id,
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'name' =>  $this->first_name.' '.$this->last_name,
            'occupation' => $this->occupation,
            'bio' => $this->bio,
            'photo' => asset('files/authors/'.$this->photo, env('SECURE_ASSET')),
            'analyses' => Analyse::collection($this->analyses),
            'count' => $this->analyses->count()
        ];
    }
}
