<?php namespace App\Droit\Transfert\Menu\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Menu extends Model{

    protected $table = 'menus';

    protected $fillable = ['title','position','site_id'];

    /**
     * Set timestamps off
     */
    public $timestamps = false;

    public function scopeSites($query,$site)
    {
        if ($site) $query->where('site_id','=',$site);
    }

    public function pages()
    {
        return $this->hasMany('App\Droit\Transfert\Page\Entities\Page')->orderBy('pages.rang');
    }

    public function site()
    {
        return $this->belongsTo('App\Droit\Transfert\Site\Entities\Site');
    }
}