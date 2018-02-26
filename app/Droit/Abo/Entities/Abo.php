<?php

namespace App\Droit\Abo\Entities;

use Illuminate\Database\Eloquent\Model;

class Abo extends Model
{
    public $timestamps = false;
    
    protected $fillable = [
        'user_id', 'categorie_id', 'keywords'
    ];

    public function getKeywordsListAttribute()
    {
        $search = htmlspecialchars_decode($this->keywords);

        return collect(explode(',',$search))->groupBy(function ($item, $key) {
            return preg_match('/\"([^\"]*?)\"/', $item, $m) ? 'quotes' : 'noquotes';
        })->map(function ($items, $key) {
            return $items->map(function ($item, $key) {
                return trim(str_replace('"', '', $item));
            });
        })->flatten();
    }

}
