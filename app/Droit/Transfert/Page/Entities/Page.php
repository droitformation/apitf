<?php

namespace App\Droit\Transfert\Page\Entities;

use Baum\Node;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
* Page
*/
class Page extends Node {

    use SoftDeletes;

    /**
    * Table name.
    *
    * @var string
    */
    protected $table = 'pages';

    protected $dates    = ['deleted_at'];
    protected $fillable = ['title','menu_title','content','excerpt','rang','menu_id','template','slug','parent_id','lft','rgt','depth','hidden','site_id','url','isExternal'];

    protected $orderColumn = 'rang';
    
    public function scopeSites($query,$site)
    {
        if ($site) $query->where('site_id','=',$site);
    }

    public function menu()
    {
        return $this->belongsTo('App\Droit\Transfert\Menu\Entities\Menu');
    }

    public function site()
    {
        return $this->belongsTo('\App\Droit\Transfert\Site\Entities\Site');
    }

    public function contents()
    {
        return $this->hasMany('App\Droit\Transfert\Content\Entities\Content')->orderBy('rang','ASC');
    }

    public function blocs()
    {
        return $this->belongsToMany('App\Droit\Transfert\Bloc\Entities\Bloc','bloc_pages','page_id','bloc_id')->orderBy('blocs.rang','ASC');
    }
}
