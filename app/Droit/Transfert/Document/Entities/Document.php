<?php namespace App\Droit\Transfert\Document\Entities;

use Illuminate\Database\Eloquent\Model;

class Document extends Model{

    protected $table = 'colloque_documents';

    protected $fillable = ['colloque_id','display','type','path','titre'];

    public $timestamps = false;

    public function getColloquePathAttribute()
    {
        return 'files/colloques/document/'.$this->path;
    }
}