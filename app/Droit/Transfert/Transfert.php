<?php namespace App\Droit\Transfert;

class Transfert
{
    public $site = null;

    public function arrets()
    {
        $model = new \App\Droit\Transfert\Arret\Entities\Arret();

        $arrets = $model->with(['categories','analyses','analyses.authors'])->take(2)->get();

        return $arrets->toArray();
    }

    public function prepare()
    {
        // Make site
        // Get id  => conversion table
        // Make Categories => conversion table
        // Make Authors => conversion table
        // Get arrets
    }

    public function makeSite($data)
    {
        $this->site = \App\Droit\Transfert\Site\Entities\Site::create($data);
        return $this;
    }

    public function makeCategories()
    {
        $old = new \App\Droit\Transfert\Categorie\Entities\Categorie();
        $new = new \App\Droit\Transfert\Categorie\Entities\Categorie();

        return $this;
    }

    public function getOld($model)
    {
        $model = "\App\Droit\Transfert\'.$model.'\Entities\'.$model.";
        $old   = new $model();

        return $old->take(4)->get();
    }

    public function makeNew($model,$table)
    {
        $model = '\App\Droit\Transfert\\'.$model.'\Entities\\'.$model;
        $old   = new $model();

        return $old->setConnection('');
    }
}