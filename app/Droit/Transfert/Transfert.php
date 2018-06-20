<?php namespace App\Droit\Transfert;

class Transfert
{
    public $site = null;
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
            $this->conversions[$type['model']]['table'][$model->id ] = $new->id;

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

    public function makeCategories()
    {
        $old = $this->getOld('Categorie');

        // Get all
        $old_models = $old->get();
        // Loop
        // Set site_id and site slug
        foreach ($old_models as $model){
            $new = $this->makeNew('Categorie');
            $new->fill(array_except($model->toArray(),['id','pid','user_id','deleted','parent_id']));
            $new->site_id = $this->site->id;
            $new->image   = $this->site->slug.'/'.$model->image;
            $new->old_id  = $model->id;
            echo '<pre>';
            print_r($new->toArray());
            echo '</pre>';
            $new->save();
        }
        // copy old make new
        // put in conversion table

        //return $this;
    }

    public function getOld($model)
    {
        $old = $this->getModel($model);

        return $old->setConnection('transfert');
    }

    public function makeNew($model)
    {
        $new = $this->getModel($model);

        return $new->setConnection('testing_transfert');
    }

    public function getModel($name)
    {
        $model = '\App\Droit\Transfert\\'.$name.'\Entities\\'.$name;
        return new $model();
    }
}