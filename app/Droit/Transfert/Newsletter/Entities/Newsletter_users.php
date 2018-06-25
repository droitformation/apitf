<?php namespace App\Droit\Transfert\Newsletter\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Newsletter_users extends Model {

    use SoftDeletes;

    protected $dates    = ['activated_at','deleted_at'];
	protected $fillable = ['email','activation_token','activated_at'];
    protected $connection = 'transfert';

    public function subscriptions()
    {
        $database = $this->getConnection()->getDatabaseName();
        return $this->belongsToMany('App\Droit\Transfert\Newsletter\Entities\Newsletter', $database.'.newsletter_subscriptions', 'user_id', 'newsletter_id');
    }
}