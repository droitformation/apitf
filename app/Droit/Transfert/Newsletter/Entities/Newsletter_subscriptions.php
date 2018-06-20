<?php namespace App\Droit\Transfert\Newsletter\Entities;

use Illuminate\Database\Eloquent\Model;

class Newsletter_subscriptions extends Model {

    public $timestamps = false;

	protected $fillable = ['user_id','newsletter_id'];

    public function newsletter()
    {
        return $this->hasOne('App\Droit\Newsletter\Entities\Newsletter');
    }
}