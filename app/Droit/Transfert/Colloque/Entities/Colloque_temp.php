<?php

namespace App\Droit\Colloque\Entities;

use Illuminate\Database\Eloquent\Model;

/*
 * ONLY TEMPORARY MODEL
 * REMOVE AFTER CONVERSION
 **/

class Colloque_temp extends Model
{
    protected $table = 'colloques_temp';

    protected $dates = ['start_at','end_at','registration_at','registration_at','active_at'];

    protected $fillable = [
        'titre', 'soustitre', 'sujet', 'remarques', 'start_at', 'end_at', 'registration_at', 'active_at', 'organisateur_id',
        'location_id', 'compte_id', 'visible', 'bon', 'facture', 'created_at', 'updated_at'
    ];

}
