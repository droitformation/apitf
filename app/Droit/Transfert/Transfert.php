<?php namespace App\Droit\Transfert;

class Transfert
{
    public function arrets()
    {
        $model = new \App\Droit\Transfert\Arret\Entities\Arret();

        $arrets = $model->with(['categories','analyses','analyses.authors'])->take(2)->get();

        return $arrets->toArray();
    }
}