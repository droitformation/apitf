<?php

use Illuminate\Database\Seeder;

class SubCategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::unprepared(file_get_contents(storage_path('db/subcategories.sql')));
        \DB::unprepared(file_get_contents(storage_path('db/decision_categories.sql')));
        \DB::unprepared(file_get_contents(storage_path('db/categorie_keywords.sql')));
        //\DB::unprepared(file_get_contents(storage_path('db/wp_archives.sql')));
       // \DB::unprepared(file_get_contents(storage_path('db/wp_archives_2.sql')));
    }
}
