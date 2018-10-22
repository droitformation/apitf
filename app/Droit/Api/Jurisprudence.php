<?php namespace App\Droit\Api;

class Jurisprudence
{
    public $connection;
    public $site;

    protected $arret;
    protected $analyse;
    protected $categorie;
    protected $author;
    protected $page;
    protected $menu;

    public function __construct()
    {
        $this->arret     = \App::make('App\Droit\Transfert\Arret\Repo\ArretInterface');
        $this->analyse   = \App::make('App\Droit\Transfert\Analyse\Repo\AnalyseInterface');
        $this->categorie = \App::make('App\Droit\Transfert\Categorie\Repo\CategorieInterface');
        $this->author    = \App::make('App\Droit\Transfert\Author\Repo\AuthorInterface');
        $this->page      = \App::make('App\Droit\Transfert\Page\Repo\PageInterface');
        $this->menu      = \App::make('App\Droit\Transfert\Menu\Repo\MenuInterface');
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

    // Site id
    public function getNewsletter()
    {
        $sites  = \App::make('App\Droit\Transfert\Site\Repo\SiteInterface');
    }

    public function authors()
    {
        $model = $this->getModel('Author');

        return $model->where('site_id','=',$this->site)->with(['analyses'])->orderBy('last_name', 'ASC')->get();
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
        $model   = $model->where('site_id','=',$this->site)->whereNotIn('id', $exclude);

        if(isset($options['categories'])){
            $model = $model->categories($options['categories']);
        }

        if(isset($options['years'])){
            $model = $model->years($options['years']);
        }

        $model = $model->with(['categories','analyses'])->orderBy('pub_date', 'DESC');

        if(isset($options['limit']) && $options['limit'] > 0){
            $model = $model->take($options['limit']);
        }

        return $model->get();
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

    public function years()
    {
        $arrets = $this->arrets();

        return $arrets->groupBy(function ($archive, $key) {
            return $archive->pub_date->year;
        })->keys();
    }

    public function exclude()
    {
        $model     = $this->getModel('Newsletter_campagnes','Newsletter');
        $campagnes = $model->where('status','=','brouillon')->whereHas('newsletter', function ($query) {
                        $query->where('site_id', '=', $this->site);
                    })->get();

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

    public function newsletter($id = null,$year = null)
    {
        $model = $this->getModel('Newsletter_campagnes','Newsletter');

        if($id){
            return $model->with(['content.arret','content.product','content.colloque','content.categorie'])->find($id);
        }

        return $model->where('status','=','envoyé')
            ->with(['newsletter'])
            ->year($year)
            ->whereHas('newsletter', function ($query) {
            $query->where('site_id', '=', $this->site);
        })->orderBy('send_at','DESC')->get();
    }

    public function last()
    {
        $model = $this->getModel('Newsletter_campagnes','Newsletter');

        return $model->where('status','=','envoyé')->with(['newsletter','content.arret','content.product','content.colloque','content.categorie'])->whereHas('newsletter', function ($query) {
            $query->where('site_id', '=', $this->site);
        })->orderBy('send_at','DESC')->first();
    }

    public function homepage()
    {
        $model = $this->getModel('Page');

        return $model->where('site_id','=',$this->site)->where('template','=','index')->with(['contents'])->first();
    }

    public function menu($position)
    {
        $model = $this->getModel('Menu');

        return $model->where('site_id','=',$this->site)->where('position','=',$position)->first();
    }

    public function page($id)
    {
        $model = $this->getModel('Page');

        return $model->find($id);
    }
}