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
    $user  = $repo->find(2744);

    $repo = App::make('App\Droit\Decision\Repo\DecisionInterface');
    $decisions = $repo->search(['terms' => null, 'categorie' => 226, 'published' => 1, 'publication_at' => '2019-05-10']);

    $alert->setCadence('daily')->setDate(weekRange('2019-05-16')->toArray());
    $abos = $alert->getUserAbos($user);

    return new \App\Mail\AlerteDecision($user, weekRange('2019-05-16')->toArray(), $abos);
});

Route::get('handlealert', function () {

    $alert = new \App\Jobs\SendEmailAlert(weekRange('2019-05-10')->toArray(), 'weekly');
    $abos  = $alert->handle();

    foreach ($abos as $abo){
        echo (new \App\Mail\AlerteDecision($abo['user'], weekRange('2019-05-10')->toArray(), $abo['abos']))->render();
    }

    return view('test');

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

    $string = 'LPD; protection des données';

    $repo = App::make('App\Droit\Decision\Repo\DecisionInterface');

    $decisions = $repo->search(['terms' => [$string], 'categorie' => null, 'published' => null, 'publication_at' => '2019-04-09']);

    echo '<pre>';
    print_r($decisions);
    echo '</pre>';
    exit();
    //$transfert = new App\Droit\Transfert\Transfert();
    //$transfert->connection = 'testing_transfert';

    //return $transfert->exist('cindy.leschaud@gmail.com');exit;

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