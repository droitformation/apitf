<?php namespace App\Droit\Transfert\Location\Entities;

use Illuminate\Database\Eloquent\Model;

class Location extends Model{

    protected $table = 'locations';

    protected $fillable = ['name','adresse','url','map'];

    public $timestamps = false;

}