<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Campagne extends JsonResource
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
            'id'      => $this->id,
            'sujet'   => $this->sujet,
            'auteurs' => $this->auteurs,
            //'arrets'  => Arret::collection($this->arrets),
        ];
    }
}
