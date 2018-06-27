<?php namespace App\Droit\Api;

class Jurisprudence
{
    public $connection;
    public $site;

    protected $arret;
    protected $analyse;
    protected $categorie;
    protected $author;

    public function __construct()
    {
        $this->arret     = \App::make('App\Droit\Transfert\Arret\Repo\ArretInterface');
        $this->analyse   = \App::make('App\Droit\Transfert\Analyse\Repo\AnalyseInterface');
        $this->categorie = \App::make('App\Droit\Transfert\Categorie\Repo\CategorieInterface');
        $this->author    = \App::make('App\Droit\Transfert\Author\Repo\AuthorInterface');
    }

    public function setConnection($connection)
    {
        $this->connection = $connection;

        return $this;
    }

    // Site id
    public function setSite($site)
    {
        $this->site = $site;

        return $this;
    }

    public function authors()
    {
        $model = $this->getModel('Author');

        return $model->where('site_id','=',$this->site)->where('hideOnSite', '=', 0)->with(['analyses'])->orderBy('title', 'ASC')->get();
    }

    public function categories()
    {
        $model = $this->getModel('Categorie');

        return $model->where('site_id','=',$this->site)->where('hideOnSite', '=', 0)->with(['parent'])->orderBy('title', 'ASC')->get();
    }

    public function arrets($options = [])
    {
        $model   = $this->getModel('Arret');
        $exclude = $this->exclude();

        $model = $model->where('site_id','=',$this->site)->whereNotIn('id', $exclude);

        if(isset($options['categories'])){
            $model = $model->categories($options['categories']);
        }

        if(isset($options['years'])){
            $model = $model->years($options['years']);
        }

        return $model->with(['categories','analyses'])->orderBy('pub_date', 'DESC')->get();
    }

    public function analyses($options = [])
    {
        $model   = $this->getModel('Analyse');
        $exclude = $this->exclude();

        $model = $model->where('site_id','=',$this->site)->whereHas('arrets', function ($query) use ($exclude) {
            $query->whereNotIn('arrets.id', $exclude);
        });

        if(isset($options['years'])){
            $model = $model->years($options['years']);
        }

        return $model->with(['authors','categories','arrets'])->orderBy('pub_date', 'DESC')->get();
    }

    public function annees()
    {
        $arrets = $this->arrets();

        return $arrets->groupBy(function ($archive, $key) {
            return $archive->pub_date->year;
        })->keys();
    }

    public function exclude()
    {
        $model     = $this->getModel('Newsletter_campagnes','Newsletter');
        $campagnes = $model->setConnection($this->connection)->where('status','='.'brouillon')->get();

        return $campagnes->flatMap(function ($campagne) {
            return $campagne->content;
        })->map(function ($content, $key) {

            if($content->arret_id)
                return $content->arret_id ;

            if($content->groupe_id > 0)
                return $content->groupe->arrets->pluck('id')->all();

        })->filter(function ($value, $key) {
            return !empty($value);
        })->flatten()->toArray();
    }

    public function getModel($name,$parent = null)
    {
        $parent = $parent ? $parent : $name;

        $path  = '\App\Droit\Transfert\\'.$parent.'\Entities\\'.$name;
        $model = new $path();
        $model = $model->setConnection($this->connection);

        return $model;
    }

    /*
        $years      = $this->arret->annees();
        $exclude    = $this->newsworker->arretsToHide([3]);
        $arrets     = $this->arret->getAllActives($exclude);

        $analyses   = $this->analyse->getAll($exclude);
        $categories = $this->categorie->getAll();
     */

}