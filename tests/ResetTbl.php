<?php namespace Tests;

trait ResetTbl
{
    function reset_all(){

        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        \DB::table('arrets')->truncate();
        \DB::table('arrets_groupes')->truncate();
        \DB::table('groupes')->truncate();
        \DB::table('arret_categories')->truncate();

        \DB::table('analyses')->truncate();
        \DB::table('analyse_authors')->truncate();
        \DB::table('analyse_categories')->truncate();
        \DB::table('analyses_arret')->truncate();

        \DB::table('newsletters')->truncate();
        \DB::table('newsletter_campagnes')->truncate();
        \DB::table('newsletter_contents')->truncate();
        \DB::table('newsletter_subscriptions')->truncate();
        \DB::table('newsletter_types')->truncate();
        \DB::table('newsletter_users')->truncate();

        \DB::table('categories')->truncate();
        \DB::table('authors')->truncate();
        \DB::table('sites')->truncate();

        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $this->artisan('db:seed',['--class' => 'TypeSeeder']);

    }
}