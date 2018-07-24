<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Arret extends JsonResource
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
            'id'         => $this->id,
            'reference'  => $this->reference,
            'pub_date'   => $this->pub_date,
            'abstract'   => $this->abstract,
            'file'       => asset('files/arrets/'.$this->file),
            'dumois'     => $this->dumois,
            //'categories' => return (new Categorie($categorie)),
            //'analyses'   => ,
        ];
    }
}
