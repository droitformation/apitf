<?php

namespace App\Droit\User\Entities;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\ResetPasswordNotification;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name','last_name', 'email', 'password','active_until','cadence',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $dates = ['active_until'];

    public function getAbonnementsAttribute()
    {
        return $this->abos->groupBy('categorie_id')->map(function($keywords,$categorie_id){
            $published    = $this->published->contains('categorie_id',$categorie_id);
            // Make sure we have en empty collection if no keywords, so the repo has the categorie for searching in new decisions
            $keyword_list = !$keywords->isEmpty() ? $keywords->pluck('keywords_list') : collect([]);
            return ['keywords' => $keyword_list, 'published' => $published];
        });
    }

    public function abos()
    {
        return $this->hasMany('App\Droit\Abo\Entities\Abo');
    }

    public function published()
    {
        return $this->hasMany('App\Droit\Abo\Entities\Abo_publish');
    }
}
