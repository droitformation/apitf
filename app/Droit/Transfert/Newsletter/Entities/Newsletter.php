<?php namespace App\Droit\Transfert\Newsletter\Entities;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Newsletter extends Model {

	protected $fillable = ['titre','from_name','from_email','return_email','unsuscribe','preview','site_id','list_id','color','logos','header','soutien'];
    protected $connection = 'transfert';
    use SoftDeletes;

    protected $dates = ['deleted_at'];
    
    public function site()
    {
        return $this->belongsTo('App\Droit\Transfert\Site\Entities\Site');
    }

    public function subscriptions()
    {
        $database = $this->getConnection()->getDatabaseName();
        return $this->belongsToMany('App\Droit\Transfert\Newsletter\Entities\Newsletter_users', $database.'.newsletter_subscriptions', 'newsletter_id','user_id');
    }
}