<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Colloque extends JsonResource
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
            'date' => $this->event_date,
            'location' =>  $this->location ? $this->location->name : '',
            'image' => $this->frontend_illustration,
        ];
    }
}
