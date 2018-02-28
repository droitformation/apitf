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

Route::get('/','FrontendController@index');
Route::match(['get', 'post'], '/current','FrontendController@current');
Route::get('/archive','FrontendController@archive');

Route::get('/api/categories','ApiController@categories');
Route::get('/api/categorie/{id}','ApiController@categorie');
Route::get('/api/decisions','ApiController@decisions');
Route::post('/api/search','ApiController@search');
Route::get('/api/decision/{id}/{year}','ApiController@decision');


Route::post('date/update','DateController@update');
Route::post('date/delete','DateController@delete');

Route::post('search','SearchController@search');

Route::post('decision/update','DecisionController@update');

Route::post('archive/transfert','ArchiveController@transfert');
Route::post('archive/update','ArchiveController@update');

Route::get('/decisions/{date}/{id?}','DecisionController@index');
Route::get('/archives/{year}/{date}/{id?}','ArchiveController@index');



Route::get('arret', function () {

    $archive  = new \App\Droit\Decision\Entities\Archive();
    $archives = $archive->count();

    echo '<pre>';
    print_r($archives);
    echo '</pre>';exit();
   # $tables = ['archives_2013','archives_2014','archives_2015','archives_2016','archives_2017'];

    foreach ($tables as $table){
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

        print_r($table);
        echo '<br>';
    }

});

Route::get('decision', 'ArticleController@index');

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