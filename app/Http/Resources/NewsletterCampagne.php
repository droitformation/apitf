<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class NewsletterCampagne extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $blocs = $this->content->map(function ($content, $key) {
            return (new Campagne($content));
        });

        return [
            'data' => [
                'blocs' => $blocs,
                'campagne' => [
                    'id' => $this->id,
                    'sujet' => $this->sujet,
                    'auteurs' => $this->auteurs,
                    'color' => $this->newsletter->color
                ]
            ],
        ];
    }
}
