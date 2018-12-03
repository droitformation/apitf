<?php namespace App\Droit\Transfert;

class Transfert
{
    public $connection = 'transfert';
    public $site = null;
    public $newsletter = null;
    public $oldnewsletter = null;
    public $conversions = [
        'Categorie' => [
            'model'  => 'Categorie',
            'except' => ['id','pid','user_id','deleted'],
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

        $data = array_only($newsletter->toArray(),[
            'titre','from_name','from_email','return_email','unsuscribe','preview','list_id','color','logos','header','soutien',
            'pdf','classe','comment','comment_title','display','hide_title','second_color'
        ]);

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

        // $relations = $content->groupe->arrets->pluck('id')->all();
        // Convert to new ids with table
        //$ids = array_intersect_key($arrets, array_flip($relations));

        $ids = $content->groupe->arrets->mapWithKeys(function ($item, $key) use ($arrets) {
            return isset($arrets[$item->id]) ? [$arrets[$item->id] => ['sorting' => $item->pivot->sorting]] : [];
        })->map(function ($item, $key) use ($newgroup) {
            // attach to new model
            $newgroup->arrets()->attach($key,$item);
        });

        return $newgroup;
    }

    public function makeSubscriptions()
    {
        $old = $this->getOld('Newsletter_users','Newsletter');

        // Get all old users for newsletters
        $subscribers = $old->setConnection('transfert')->get();

        // loop over users
        if(!$subscribers->isEmpty()){
            foreach ($subscribers as $subscriber){

                $user = $this->exist($subscriber);
                $ids  = $subscriber->subscriptions->pluck('id')->all();

                // attach to new model
                if(!empty($ids)){
                    $user->subscriptions()->attach($this->newsletter->id);
                }
            }
        }
    }

    public function exist($subscriber)
    {
        $user = $this->makeNew('Newsletter_users','Newsletter');
        $user = $user->setConnection('testing_transfert');

        $exist = $user->where('email','=',$subscriber->email)->first();

        if(!$exist){
            $user->email            = $subscriber->email;
            $user->activation_token = $subscriber->activation_token;
            $user->activated_at     = $subscriber->activated_at && $this->valid($subscriber->activated_at->toDateTimeString()) ? $subscriber->activated_at : \Carbon\Carbon::today()->toDateTimeString();

            $user->save();
            $user = $user->fresh();

            return $user;
        }

        return $exist;
    }

    public function valid($date)
    {
        if(!$date){
            return false;
        }

        if($date == '0000-00-00 00:00:00') {
            return false;
        }

        return true;
    }

    public function makeNewModels($type)
    {
        $old = $this->getOld($type['model']);

        // Get all
        $old_models = $old->setConnection($this->connection)->with($type['relations'])->get();

        // Loop
        foreach ($old_models as $model){

            // exist already for authors
            if($type['model'] == 'Author'){
                $exist = $this->existAuthor($model->first_name,$model->last_name);

                if($exist){
                    $new = $exist;
                    $new->sites()->attach($this->site->id);
                }
                else{
                    $new = $this->makeNew($type['model']);
                    $new->fill(array_except($model->toArray(),$type['except']));
                }
            }
            else{
                $new = $this->makeNew($type['model']);
                $new->fill(array_except($model->toArray(),$type['except']));
            }

            // Set site_id and site slug if necessary
            if($type['model'] == 'Categorie'){
                $new->image = $this->site->slug.'/'.$model->image;
                $new->parent_id = $model->parent_id;
            }

            if($type['model'] == 'Author'){
                $new->save();
                $new->sites()->attach($this->site->id);
            }
            else{
                $new->site_id = $this->site->id;
                $new->save();
            }

            // complete conversion table
            $this->conversions[$type['model']]['table'][$model->id] = $new->id;

            if(!empty($type['relations'])){
                foreach($type['relations'] as $relation){

                    // get model name
                    $name  = ucfirst(substr($relation, 0, -1));
                    // get conversion table old id => new id
                    $table = $this->conversions[$name]['table'];
                    // Get old relation ids

                    if($relation == 'categories'){
                        $model->$relation->mapWithKeys(function ($item, $key) use ($table) {
                            $sorting = $item->pivot->sorting ? $item->pivot->sorting : 0;
                            return isset($table[$item->id]) ? [$table[$item->id] => ['sorting' => $sorting]] : [];
                        })->map(function ($item, $key) use ($new,$relation) {
                            // attach to new model
                            $new->$relation()->attach($key,$item);
                        });
                    }
                    else{
                        $relations = $model->$relation->pluck('id')->all();
                        // Convert to new ids with table
                        $ids = array_intersect_key($table, array_flip($relations));
                        // attach to new model
                        $new->$relation()->attach(array_values($ids));
                    }
                }
            }
        }
    }

    public function getOld($model, $parent = null)
    {
        $old = $this->getModel($model,$parent);

        return $old->setConnection($this->connection);
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

    public function existAuthor($first_name,$last_name)
    {
        $author = $this->makeNew('Author');
        $author = $author->setConnection('testing_transfert');

        $exist = $author->where('first_name','=',$first_name)->where('last_name','=',$last_name)->get();

        return !$exist->isEmpty() ? $exist->first() : null;
    }
}