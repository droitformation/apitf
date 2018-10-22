<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\ResourceCollection;

class NewsletterCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $this->collection->sortByDesc('send_at')->transform(function ($newsletter) {
            return (new Newsletter($newsletter));
        });

        return parent::toArray($request);
    }
}
