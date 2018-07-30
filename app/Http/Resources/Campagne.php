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
            'type_id'      => $this->type_id,
            'partial'      => $this->type->partial,
            'titre'        => $this->titre,
            'contenu'      => $this->contenu,
            'image'        => isset($this->image) ? secure_asset(config('newsletter.path.upload').$this->image) : null,
            'lien'         => $this->lien,
            'arret_id'     => $this->arret_id,
            'arret'        => isset($this->arret) ? (new Arret($this->arret)) : null,
            'groupe'       => isset($this->groupe) ? Arret::collection($this->groupe->arrets) : null,
            'categorie'    => isset($this->categorie) ? (new Categorie($this->categorie)) : null,
            'categorie_id' => $this->categorie_id,
            'rang'         => $this->rang,
            'groupe_id'    => $this->groupe_id,
        ];
    }
}
