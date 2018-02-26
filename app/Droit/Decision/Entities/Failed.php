<?php

namespace App\Droit\Decision\Entities;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Failed extends Model
{
    protected $table = 'failed_update';

    protected $dates = ['publication_at'];

    protected $fillable = ['publication_at', 'numero'];
}
