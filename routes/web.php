<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::match(['get', 'post'],'/','FrontendController@index');
Route::get('transfert','FrontendController@transfert');
Route::post('dotransfert','FrontendController@dotransfert');

//Route::post('api','ContentController@index');

Route::post('api/arrets','ContentController@arrets');
Route::post('api/analyses','ContentController@analyses');
Route::post('api/categories','ContentController@categories');
Route::post('api/years','ContentController@years');
Route::post('api/authors','ContentController@authors');
Route::post('api/campagne','ContentController@campagne');
Route::post('api/archives','ContentController@archives');
Route::post('api/homepage','ContentController@homepage');
Route::post('api/menu','ContentController@menu');
Route::post('api/page','ContentController@page');

Route::group(['prefix' => 'praticien'], function () {

    Route::get('newsletter/{date?}','Praticien\NewsletterController@index');
    Route::match(['get', 'post'], 'letter','Praticien\NewsletterController@letter');
    Route::get('send','Praticien\NewsletterController@send');

    Route::post('date/update','Praticien\DateController@update');
    Route::post('date/delete','Praticien\DateController@delete');

    Route::post('search','Praticien\SearchController@search');

    Route::get('/','Praticien\ArchiveController@index');
    Route::get('/archive','Praticien\ArchiveController@archive');
    Route::get('archives/{year}/{date}/{id?}','Praticien\ArchiveController@archives');

    Route::post('transfert','Praticien\ArchiveController@transfert');
    Route::match(['get', 'post'], 'testing','Praticien\ArchiveController@testing');
    Route::match(['get', 'post'], 'abos','Praticien\UserController@index');

    Route::get('decisions/{date}/{id?}','Praticien\DecisionController@index');
    Route::post('decision/update','Praticien\DecisionController@update');
});


Route::group(['prefix' => 'api'], function () {
    Route::post('/search','Api\MainController@search');
    Route::get('/categories','Api\MainController@categories');
    Route::get('/categorie/{id}','Api\MainController@categorie');
    Route::get('/decisions','Api\MainController@decisions');
    Route::get('/decision/{id}/{year}','Api\MainController@decision');

    Route::post('/user','Api\UserController@show');
    Route::post('/abo/make','Api\AboController@make');
    Route::post('/abo/remove','Api\AboController@remove');
    Route::post('/abo/cadence','Api\AboController@cadence');
});

Route::get('alert', function () {

    $repo  = \App::make('App\Droit\User\Repo\UserInterface');
    $alert = \App::make('App\Droit\Bger\Worker\AlertInterface');
    $user  = $repo->find(1422);

    $alert->setCadence('weekly')->setDate(weekRange('2018-05-31')->toArray());
    $abos = $alert->getUserAbos($user);

    return new \App\Mail\AlerteDecision($user, weekRange('2018-05-31')->toArray(), $abos);
});


Route::get('transfertapi', function () {
    $site_data = [
        'rca' => [
            'nom'    => 'RC Assurances',
            'url'    => 'http://rcassurances.ch',
            'logo'   => 'rcassurances.svg',
            'slug'   => 'rcassurances',
            'prefix' => 'rcassurances'
        ],
        'ddt' => [
            'nom'    => 'Droit du travail',
            'url'    => 'http://droitdutravail.ch',
            'logo'   => 'droitdutravail.svg',
            'slug'   => 'droitdutravail',
            'prefix' => 'droitdutravail'
        ]
    ];

    $newsletter = new \App\Droit\Transfert\Newsletter\Entities\Newsletter();
    $model = $newsletter->get();

    $transfert = new App\Droit\Transfert\Transfert();
    $transfert->connection = 'testing_transfert';

    $model = $model->first();

    $transfert->makeSite($site_data[env('DB_DATABASE_TRANSFERT')])->prepare();
    $transfert->makeNewsletter($model)->makeCampagne();
    $transfert->makeSubscriptions();
});

Route::get('arret', function () {

    $faker = \Faker\Factory::create();

    $jurisprudence = new App\Droit\Api\Jurisprudence();
    $transfert = new App\Droit\Transfert\Transfert();

    $exist = $transfert->existAuthor('François','Bohnet');

    echo '<pre>';
    print_r($exist);
    echo '</pre>';exit();

/*    $sites = $jurisprudence->getModel('Site')->setConnection('testing_transfert');
    $site = $sites->first();

    $arrets     = $jurisprudence->setConnection('testing_transfert')->setSite($site->id)->arrets();
    $analyses   = $jurisprudence->setConnection('testing_transfert')->setSite($site->id)->analyses();
    $authors    = $jurisprudence->setConnection('testing_transfert')->setSite($site->id)->authors();

    echo '<pre>';
    print_r($arrets);
    echo '</pre>';exit();*/


    $contents = $jurisprudence->getModel('Newsletter_contents','Newsletter')->setConnection('testing_transfert');
    $bloc = $contents->find(515);

    // old => new
    $conversion = [
         1279 => 12,
         1280 => 13,
         1281 => 14,
         1284 => 15,
    ];

    $sorted = $bloc->groupe->arrets->mapWithKeys(function ($item, $key) use ($conversion) {
        return isset($conversion[$item->id]) ? [$conversion[$item->id] => ['sorting' => $item->pivot->sorting]] : [];
    });

    echo '<pre>';
    print_r($sorted);
    echo '</pre>';exit();
    echo '<pre>';
    print_r($bloc->groupe->arrets->pluck('id','pivot.sorting'));
    echo '</pre>';exit();
    /*    $model = $transfert->getOld('Analyse');

        $relations = $model->first()->categories->pluck('id')->all();
        $ids = array_intersect_key([63 => 12, 13 => 24], array_flip($relations));

        echo '<pre>';
        print_r($ids);*/
    echo '</pre>';exit();

/*    $ipverify = new \App\Droit\Uptime\IP();
    $result = $ipverify->logs();
    echo '<pre>';
    print_r($result);
    echo '</pre>';exit();*/

   // $archive  = new \App\Droit\Decision\Entities\Archive();
   // $archives = $archive->count();

    //echo '<pre>';
    //print_r($archives);
    //echo '</pre>';exit();
   # $tables = ['archives_2013','archives_2014','archives_2015','archives_2016','archives_2017'];

   // foreach ($tables as $table){
        // set table
        //$archive = $archive->setTable($table);

       // \DB::table($table)->delete();
        /* $archive->chunk(200, function ($decisions) {

            $newtable = new \App\Droit\Decision\Entities\Old();

            foreach ($decisions as $data) {
               $newtable->create(array(
                    'id_nouveaute'          => $data['id_nouveaute'],
                    'numero_nouveaute'      => $data['numero_nouveaute'],
                    'datep_nouveaute'       => $data['datep_nouveaute'],
                    'dated_nouveaute'       => $data['dated_nouveaute'],
                    'categorie_nouveaute'   => $data['categorie_nouveaute'],
                    'remarque_nouveaute'    => $data['remarque_nouveaute'],
                    'link_nouveaute'        => $data['link_nouveaute'],
                    'texte_nouveaute'       => $data['texte_nouveaute'],
                    'langue_nouveaute'      => $data['langue_nouveaute'],
                    'publication_nouveaute' => $data['publication_nouveaute'],
                    'updated'               => $data['updated'],
                ));
            }
        });*/

      //  print_r($table);
      //  echo '<br>';
   // }

});

//Route::get('decision', 'ContentController@index');

/*Route::get('archive', function () {

    $old = new \App\Droit\Categorie\Entities\OldCategorie();
    //$all = $model->take(5)->get();

    $old->chunk(200, function ($decisions) {
        $archive = new \App\Droit\Categorie\Entities\ArchiveCategorie();
        foreach ($decisions as $data) {
            $archive->create(array(
                'term_id'       => $data['term_id'],
                'name'          => $data['name'],
                'refCategorie'          => $data['refCategorie'],
                'refNouveaute'          => $data['refNouveaute'],
            ));
        }
    });

    echo '<pre>';
    print_r('finished');
    echo '</pre>';exit();
});*/

Route::get('testing', function () {

    $transfert = new App\Droit\Transfert\Transfert();
    $transfert->connection = 'testing_transfert';

    return $transfert->exist('cindy.leschaud@gmail.com');exit;

/*    $archive = new \App\Droit\Categorie\Entities\ArchiveCategorie();
    $all = $archive->take(50)->get();

    echo '<pre>';
    print_r($all->pluck('name'));
    echo '</pre>';exit();*/

/*    $repo = App::make('App\Droit\Decision\Repo\DecisionInterface');
    $all = $repo->setConnection('mysql')->countByYear();

    echo '<pre>';
    print_r($all);
    echo '</pre>';exit();*/

    $data = ['numero' => '2C_1071/2014', 'decision_at' => '2015-05-28','categorie' => 175,'publication_at' => '2015-07-06'];
    $grab = new \App\Droit\Bger\Utility\Decision();
    $decision = $grab->setDecision($data)->getArret();

    echo '<pre>';
    print_r($decision);
    echo '</pre>';exit();

    /*****************
    $archive->create(array(
    'id_nouveaute'          => $data['id_nouveaute'],
    'numero_nouveaute'      => $data['numero_nouveaute'],
    'datep_nouveaute'       => $data['datep_nouveaute'],
    'dated_nouveaute'       =>  $data['dated_nouveaute'],
    'categorie_nouveaute'   => $data['categorie_nouveaute'],
    'remarque_nouveaute'    => $data['remarque_nouveaute'],
    'link_nouveaute'        => $data['link_nouveaute'],
    'texte_nouveaute'       => $data['texte_nouveaute'],
    'langue_nouveaute'      => $data['langue_nouveaute'],
    'publication_nouveaute' => $data['publication_nouveaute'],
    'updated'               => $data['updated'],
    ));

    $archive->create(array(
    'term_id'       => $data['term_id'],
    'name'          => $data['name'],
    'name_de'       => $data['name_de'],
    'name_it'       => $data['name_it'],
    'terme_parent'  => $data['terme_parent'],
    'rang'          => $data['rang'],
    'general'       => $data['general'],
    ));
    $archive->create(array(
    'term_id'       => $data['term_id'],
    'nom'          => $data['nom'],
    ));
    ****************/
});