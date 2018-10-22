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
        $image = null;
        if(isset($this->image)){
            $image = ($this->type_id != 10) ? asset('files/uploads/'.$this->image) : asset('files/pictos/'.$this->image);
        }

        return [
            'type_id'      => $this->type_id,
            'partial'      => $this->type->partial,
            'titre'        => $this->titre,
            'contenu'      => $this->contenu,
            'image'        => $image,
            'lien'         => $this->lien,
            'arret_id'     => $this->arret_id,
            'arret'        => isset($this->arret) ? (new Arret($this->arret)) : null,
            'groupe'       => isset($this->groupe) ? Arret::collection($this->groupe->arrets) : null,
            'categorie'    => isset($this->categorie) ? (new Categorie($this->categorie)) : null,
            'product'      => isset($this->product) ? (new Product($this->product)) : null,
            'colloque'     => isset($this->colloque) ? (new Colloque($this->colloque)) : null,
            'categorie_id' => $this->categorie_id,
            'rang'         => $this->rang,
            'groupe_id'    => $this->groupe_id,
        ];
    }
}
