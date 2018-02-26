<?php namespace tests\factories;

class ObjectFactory
{
    protected $faker;
    protected $categories;

    public function __construct()
    {
        $categorie = \App::make('App\Droit\Categorie\Repo\CategorieInterface');

        $this->categories = $categorie->getAll()->pluck('name','id');
        $this->faker      = \Faker\Factory::create();
    }

    public function makeUser($data = [])
    {
        $name    = isset($data['name']) ? $data['name'] : $this->faker->name;
        $email   = isset($data['email']) ? $data['email'] : $this->faker->email;
        $cadence = isset($data['cadence']) ? $data['cadence'] : 'daily';

        return factory(\App\Droit\User\Entities\User::class)->create([
            'name'    => $name,
            'email'   => $email,
            'cadence' => $cadence,
            'active_until' => \Carbon\Carbon::tomorrow()->startOfDay()->toDateTimeString()
        ]);
    }

    public function makeAbo($user = null, $categorie = null, $keywords = null)
    {
        if(!$user){
            $user = $this->makeUser();
        }

        if(!$categorie){
            $categorie = $this->categories->random();
            $categorie = $categorie->id;
        }

        return factory(\App\Droit\Abo\Entities\Abo::class)->create([
            'user_id'       => $user->id,
            'categorie_id'  => $categorie,
            'keywords'      => $keywords,
        ]);
    }

    public function multipleAbos($user, $data)
    {
        foreach($data as $new){
            $abos[] = $this->makeAbo($user, $new['categorie'], $new['keywords']);
        }

        return $abos;
    }

    public function makeDecisions($publication_at, $data)
    {
        foreach($data as $new){
            $decisisons[] = factory(\App\Droit\Decision\Entities\Decision::class)->create([
                'publication_at' => $publication_at,
                'decision_at'    => $this->faker->dateTime,
                'categorie_id'   => $new['categorie_id'],
                'texte'          => $new['texte'],
                'remarque'       => $this->faker->word,
                'numero'         => $this->faker->numberBetween(11,34).'A_/2017',
                'link'           => $this->faker->url,
            ]);
        }

        return $decisisons;
    }
}