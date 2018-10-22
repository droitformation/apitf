<?php

namespace App\Droit\Colloque\Entities;

use Illuminate\Database\Eloquent\Model;

class Colloque_attestation extends Model
{
    protected $table = 'colloque_attestations';

    protected $fillable = ['telephone', 'lieu', 'organisateur', 'title', 'signature', 'comment', 'colloque_id'];

    public function colloque()
    {
        return $this->belongsTo('App\Droit\Colloque\Entities\Colloque');
    }
}
