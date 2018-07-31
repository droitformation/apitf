<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerDecisionService();
        $this->registerFailedService();
        $this->registerCategorieService();
        $this->registerCategorieKeywordService();
        $this->registerCategorieWorkerService();
        $this->registerAboService();
        $this->registerUserService();
        $this->registerDecisionWorkerService();
        $this->registerAlertWorkerService();
        $this->registerAboWorkerService();
        $this->registerUserWorkerService();
        $this->registerSiteService();

        $this->registerPageService();
        $this->registerMenuService();

    }

    /**
     * Decision
     */
    protected function registerDecisionService(){

        $this->app->singleton('App\Droit\Decision\Repo\DecisionInterface', function()
        {
            return new \App\Droit\Decision\Repo\DecisionEloquent( new \App\Droit\Decision\Entities\Decision );
        });
    }

    /**
     * Failed
     */
    protected function registerFailedService(){

        $this->app->singleton('App\Droit\Decision\Repo\FailedInterface', function()
        {
            return new \App\Droit\Decision\Repo\FailedEloquent( new \App\Droit\Decision\Entities\Failed );
        });
    }

    /**
     * Abo
     */
    protected function registerAboService(){

        $this->app->singleton('App\Droit\Abo\Repo\AboInterface', function()
        {
            return new \App\Droit\Abo\Repo\AboEloquent(
                new \App\Droit\Abo\Entities\Abo(),
                new \App\Droit\Abo\Entities\Abo_publish()
            );
        });
    }

    /**
     * Abo
     */
    protected function registerUserService(){

        $this->app->singleton('App\Droit\User\Repo\UserInterface', function()
        {
            return new \App\Droit\User\Repo\UserEloquent( new \App\Droit\User\Entities\User );
        });
    }

    /**
     * Categorie
     */
    protected function registerCategorieService(){

        $this->app->singleton('App\Droit\Categorie\Repo\CategorieInterface', function()
        {
            return new \App\Droit\Categorie\Repo\CategorieEloquent(
                new \App\Droit\Categorie\Entities\Categorie(),
                new \App\Droit\Categorie\Entities\Parent_categorie()
            );
        });
    }

    /**
     * CategorieKeyword
     */
    protected function registerCategorieKeywordService(){

        $this->app->singleton('App\Droit\Categorie\Repo\CategorieKeywordInterface', function()
        {
            return new \App\Droit\Categorie\Repo\CategorieKeywordEloquent(
                new \App\Droit\Categorie\Entities\Categorie_keyword()
            );
        });
    }


    /**
     * Decision Worker
     */
    protected function registerDecisionWorkerService(){

        $this->app->singleton('App\Droit\Decision\Worker\DecisionWorkerInterface', function()
        {
            return new \App\Droit\Decision\Worker\DecisionWorker(
                \App::make('App\Droit\Decision\Repo\DecisionInterface'),
                \App::make('App\Droit\Decision\Repo\FailedInterface'),
                \App::make('App\Droit\Categorie\Worker\CategorieWorkerInterface'),
                new \App\Droit\Bger\Utility\Decision(),
                new \App\Droit\Bger\Utility\Liste()
            );
        });
    }

    /**
     * Categorie Worker
     */
    protected function registerCategorieWorkerService(){

        $this->app->singleton('App\Droit\Categorie\Worker\CategorieWorkerInterface', function()
        {
            return new \App\Droit\Categorie\Worker\CategorieWorker(
                \App::make('App\Droit\Categorie\Repo\CategorieKeywordInterface'),
                \App::make('App\Droit\Decision\Repo\DecisionInterface')
            );
        });
    }



    /**
     * Search Worker
     */
    protected function registerSearchWorkerService(){

        $this->app->singleton('App\Droit\Bger\Worker\SearchInterface', function()
        {
            return new \App\Droit\Bger\Worker\Search(
                \App::make('App\Droit\Decision\Repo\DecisionInterface'),
                new \App\Droit\Bger\Utility\Clean()
            );
        });
    }

    /**
     * Alert Worker
     */
    protected function registerAlertWorkerService(){

        $this->app->singleton('App\Droit\Bger\Worker\AlertInterface', function()
        {
            return new \App\Droit\Bger\Worker\Alert(
                \App::make('App\Droit\Decision\Repo\DecisionInterface'),
                \App::make('App\Droit\User\Repo\UserInterface')
            );
        });
    }

    /**
     * Abo Worker
     */
    protected function registerAboWorkerService(){

        $this->app->singleton('App\Droit\Abo\Worker\AboWorkerInterface', function()
        {
            return new \App\Droit\Abo\Worker\AboWorker(
                \App::make('App\Droit\Abo\Repo\AboInterface')
            );
        });
    }

    /**
     * User Worker
     */
    protected function registerUserWorkerService(){

        $this->app->singleton('App\Droit\User\Worker\UserWorkerInterface', function()
        {
            return new \App\Droit\User\Worker\UserWorker(
                \App::make('App\Droit\User\Repo\UserInterface')
            );
        });
    }

    /**
     * Site
     */
    protected function registerSiteService(){

        $this->app->singleton('App\Droit\Transfert\Site\Repo\SiteInterface', function()
        {
            return new \App\Droit\Transfert\Site\Repo\SiteEloquent(new \App\Droit\Transfert\Site\Entities\Site);
        });
    }

    /**
     * Page
     */
    protected function registerPageService(){

        $this->app->singleton('App\Droit\Transfert\Page\Repo\PageInterface', function()
        {
            return new \App\Droit\Transfert\Page\Repo\PageEloquent(new \App\Droit\Transfert\Page\Entities\Page);
        });
    }

    /**
     * Menu
     */
    protected function registerMenuService(){

        $this->app->singleton('App\Droit\Transfert\Menu\Repo\MenuInterface', function()
        {
            return new \App\Droit\Transfert\Menu\Repo\MenuEloquent(new \App\Droit\Transfert\Menu\Entities\Menu);
        });
    }
}
