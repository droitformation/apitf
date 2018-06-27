<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class JurisprudenceServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerAuthorService();
        $this->registerArretService();
        $this->registerAnalyseService();
        $this->registerCategorieService();
        $this->registerGroupeService();
    }

    /**
     * Author
     */
    protected function registerAuthorService(){

        $this->app->singleton('App\Droit\Transfert\Author\Repo\AuthorInterface', function()
        {
            return new \App\Droit\Transfert\Author\Repo\AuthorEloquent( new \App\Droit\Transfert\Author\Entities\Author );
        });
    }

    /**
     * Analyse
     */
    protected function registerAnalyseService(){

        $this->app->singleton('App\Droit\Transfert\Analyse\Repo\AnalyseInterface', function()
        {
            return new \App\Droit\Transfert\Analyse\Repo\AnalyseEloquent( new \App\Droit\Transfert\Analyse\Entities\Analyse );
        });
    }

    /**
     * Arret
     */
    protected function registerArretService(){

        $this->app->singleton('App\Droit\Transfert\Arret\Repo\ArretInterface', function()
        {
            return new \App\Droit\Transfert\Arret\Repo\ArretEloquent( new \App\Droit\Transfert\Arret\Entities\Arret );
        });
    }

    /**
     * Categorie
     */
    protected function registerCategorieService(){

        $this->app->singleton('App\Droit\Transfert\Categorie\Repo\CategorieInterface', function()
        {
            return new \App\Droit\Transfert\Categorie\Repo\CategorieEloquent( new \App\Droit\Transfert\Categorie\Entities\Categorie );
        });
    }


    /**
     * Groupe
     */
    protected function registerGroupeService(){

        $this->app->singleton('App\Droit\Transfert\Arret\Repo\GroupeInterface', function()
        {
            return new \App\Droit\Transfert\Arret\Repo\GroupeEloquent( new \App\Droit\Transfert\Arret\Entities\Groupe );
        });
    }
}
