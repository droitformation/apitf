<?php namespace App\Droit\Bloc\Entities;

use Illuminate\Database\Eloquent\Model;

class Bloc_page extends Model {

	protected $fillable = ['bloc_id','page_id'];

    protected $table = 'bloc_pages';
}