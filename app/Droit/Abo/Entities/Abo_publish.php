<?php

namespace App\Droit\Abo\Entities;

use Illuminate\Database\Eloquent\Model;

class Abo_publish extends Model
{
    protected $table = 'abo_publish';
    public $timestamps = false;
    
    protected $fillable = [
        'user_id', 'categorie_id'
    ];
}
