<?php namespace App\Droit\Transfert\Newsletter\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Newsletter_campagnes extends Model {

	protected $fillable = ['sujet','auteurs','newsletter_id','status','send_at','api_campagne_id', 'hidden','created_at'];
    protected $connection = 'transfert';
    use SoftDeletes;

    protected $dates = ['deleted_at','send_at'];

    public function scopeYear($query,$year)
    {
        if ($year) $query->whereYear('created_at', $year);
    }

    public function newsletter(){

        return $this->belongsTo('App\Droit\Transfert\Newsletter\Entities\Newsletter', 'newsletter_id', 'id');
    }

    public function content(){

        return $this->hasMany('App\Droit\Transfert\Newsletter\Entities\Newsletter_contents', 'newsletter_campagne_id');
    }
}