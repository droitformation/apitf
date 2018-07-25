<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class Analyse extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        setlocale(LC_ALL, 'fr_FR.UTF-8');

        return [
            'id'        => $this->id,
            'author'    => $this->author,
            'title'     => $this->title,
            'reference' => $this->arrets->first()->reference,
            'filter'    => $this->filter,
            'pub_date'  => $this->pub_date->formatLocalized('%d %B %Y'),
            'year'      => $this->pub_date->year,
            'abstract'  => $this->abstract,
            'authors'   => $this->authors->implode('name', ', '),
            'arrets'    => $this->arrets->pluck('the_title','reference'),
            'file'      => $this->file ? asset('files/analyses/'.$this->site->slug.'/'.$this->file) : null,
        ];
    }
}
