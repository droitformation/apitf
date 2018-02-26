<?php

namespace App\Droit\Bger\Entities;

use Illuminate\Database\Eloquent\Model;

class Alert_sent extends Model
{
    protected $table = 'alert_sent';
    protected $dates = ['publication_at'];
    protected $fillable = ['user_id', 'publication_at'];
}
