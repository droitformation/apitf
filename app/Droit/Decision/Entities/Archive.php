<?php

namespace App\Droit\Decision\Entities;

use Illuminate\Database\Eloquent\Model;
use App\Droit\Decision\Traits\FullTextSearch;

class Archive extends Model
{
    protected $table = 'archives';
    protected $dates = ['datep_nouveaute','dated_nouveaute'];
    protected $primaryKey = 'id_nouveaute';
    protected $connection = 'sqlite';

    public $timestamps  = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_nouveaute', 'numero_nouveaute', 'datep_nouveaute', 'dated_nouveaute', 'categorie_nouveaute',
        'remarque_nouveaute', 'link_nouveaute', 'texte_nouveaute', 'langue_nouveaute', 'publication_nouveaute','updated'
    ];
}
