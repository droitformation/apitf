<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Newsletter extends JsonResource
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
            'sujet' => $this->sujet,
            'auteurs' => $this->auteurs,
           // 'analyses' => Analyse::collection($this->analyses),
        ];
    }
}
