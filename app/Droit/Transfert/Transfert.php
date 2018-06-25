<?php namespace App\Droit\Transfert;

class Transfert
{
    public $site = null;
    public $newsletter = null;
    public $oldnewsletter = null;
    public $conversions = [
        'Categorie' => [
            'model'  => 'Categorie',
            'except' => ['id','pid','user_id','deleted','parent_id'],
            'relations' => [],
            'table'  => []
        ],
        'Author' => [
            'model'  => 'Author',
            'except' => ['id'],
            'relations' => [],
            'table'  => []
        ],
        'Analyse' => [
            'model'  => 'Analyse',
            'except' => ['id'],
            'relations' => ['authors','categories'],
            'table'  => []
        ],
        'Arret' => [
            'model'  => 'Arret',
            'except' => ['id'],
            'relations' => ['analyses','categories'],
            'table'  => []
        ]
    ];

    public function arrets()
    {
        $model = new \App\Droit\Transfert\Arret\Entities\Arret();

        $arrets = $model->setConnection('transfert')->with(['categories','analyses','analyses.authors'])->take(2)->get();

        return $arrets->toArray();
    }

    public function prepare()
    {
        foreach ($this->conversions as $type){
            $this->makeNewModels($type);
        }
    }

    public function makeSite($data)
    {
        $site = new \App\Droit\Transfert\Site\Entities\Site();
        $this->site = $site->setConnection('testing_transfert')->create($data);

        return $this;
    }

    public function makeNewsletter($newsletter)
    {
        $new = $this->makeNew('Newsletter');

        $data = array_only($newsletter->toArray(),['titre','from_name','from_email','return_email','unsuscribe','preview','list_id','color','logos','header','soutien']);
        $data['site_id'] = $this->site->id;

        $this->newsletter = $new->create($data);
        $this->oldnewsletter = $newsletter->id;

        return $this;
    }

    public function makeCampagne()
    {
        $old = $this->getOld('Newsletter_campagnes','Newsletter');

        // Get all old campagnes for newsletter
        $old_models = $old->with('content')->get();

        // loop over campagnes
        if(!$old_models->isEmpty()){
            // make content and convert arret_id, categorie_id and group_id
            foreach ($old_models as $model){

                $new = $this->makeNew('Newsletter_campagnes','Newsletter');
                $new->fill(array_only($model->toArray(),['sujet','auteurs','status','send_at','api_campagne_id', 'hidden','created_at']));
                $new->newsletter_id = $this->newsletter->id;
                $new->save();

                // content
                if(!$model->content->isEmpty()){
                    foreach ($model->content as $content){
                        $this->makeContent($content,$new);
                    }
                }
            }
        }
    }

    public function makeContent($content,$new)
    {
        $categories = $this->conversions['Categorie']['table'];
        $arrets = $this->conversions['Arret']['table'];

        $newcontent = $this->makeNew('Newsletter_contents','Newsletter');
        $newcontent->fill(array_only($content->toArray(),['type_id','titre','contenu','image','lien','rang']));
        $newcontent->newsletter_campagne_id = $new->id;

        if(isset($content->arret_id) && isset($arrets[$content->arret_id]) && $content->arret_id){
            $newcontent->arret_id = $arrets[$content->arret_id];
        }

        if(isset($content->categorie_id) && isset($categories[$content->categorie_id]) && $content->categorie_id){

            $newcontent->categorie_id = $categories[$content->categorie_id];

            if(isset($content->groupe_id) && $content->groupe_id){
                // make new group
                $newgroup = $this->makeGroupe($content);
                $newcontent->groupe_id = $newgroup->id;
            }
        }

        $newcontent->save();
    }

    public function makeGroupe($content)
    {
        $categories = $this->conversions['Categorie']['table'];
        $arrets = $this->conversions['Arret']['table'];

        // make new group
        $newgroup = $this->makeNew('Groupe', 'Arret');
        $newgroup->categorie_id = $categories[$content->categorie_id];
        $newgroup->save();

        $relations = $content->groupe->arrets->pluck('id')->all();
        // Convert to new ids with table
        $ids = array_intersect_key($arrets, array_flip($relations));

        // attach to new model
        $newgroup->arrets()->attach(array_values($ids));

        return $newgroup;
    }

    public function makeSubscriptions()
    {
        $old = $this->getOld('Newsletter_users','Newsletter');

        // Get all old users for newsletters
        $subscribers = $old->get();
        // loop over users
        if(!$subscribers->isEmpty()){
            foreach ($subscribers as $subscriber){

                $newuser = $this->makeNew('Newsletter_users','Newsletter');
                $newuser->fill(array_only($subscriber->toArray(),['email','activation_token','activated_at']));

                $ids = $subscriber->subscriptions->pluck('id')->all();
                // attach to new model
                if(!empty($ids)){
                    $newuser->subscriptions()->attach([$this->newsletter->id]);
                }

            }
        }
        // get and make new subscriptions

    }

    public function makeNewModels($type)
    {
        $old = $this->getOld($type['model']);

        // Get all
        $old_models = $old->setConnection('transfert')->with($type['relations'])->get();

        // Loop
        foreach ($old_models as $model){
            $new = $this->makeNew($type['model']);
            $new->fill(array_except($model->toArray(),$type['except']));

            // Set site_id and site slug if necessary
            if($type['model'] != 'Author'){
                $new->site_id = $this->site->id;
            }

            if($type['model'] == 'Categorie'){
                $new->image = $this->site->slug.'/'.$model->image;
            }

            $new->save();

            // complete conversion table
            $this->conversions[$type['model']]['table'][$model->id] = $new->id;

            if(!empty($type['relations'])){
                foreach($type['relations'] as $relation){

                    // get model name
                    $name  = ucfirst(substr($relation, 0, -1));
                    // get conversion table old id => new id
                    $table = $this->conversions[$name]['table'];
                    // Get old relation ids

                    $relations = $model->$relation->pluck('id')->all();
                    // Convert to new ids with table
                    $ids = array_intersect_key($table, array_flip($relations));

                    // attach to new model
                    $new->$relation()->attach(array_values($ids));
                }
            }
        }
    }

    public function getOld($model, $parent = null)
    {
        $old = $this->getModel($model,$parent);

        return $old->setConnection('transfert');
    }

    public function makeNew($model, $parent = null)
    {
        $new = $this->getModel($model,$parent);

        return $new->setConnection('testing_transfert');
    }

    public function getModel($name,$parent = null)
    {
        $parent = $parent ? $parent : $name;

        $model = '\App\Droit\Transfert\\'.$parent.'\Entities\\'.$name;
        return new $model();
    }
}