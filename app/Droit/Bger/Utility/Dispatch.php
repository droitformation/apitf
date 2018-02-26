<?php

namespace App\Droit\Bger\Utility;

/*
* Class to dispatch categories, subacategorie, languages
*/

class Dispatch
{
    protected $categorie;

    public function __construct()
    {
        $this->categorie = \App::make('App\Droit\Categorie\Repo\CategorieInterface');
    }

    public function isPublication($categorie)
    {
        return (strpos($categorie , '*') !== false);
    }

    public function isLangugage($categorie)
    {
        $categories = $this->categorie->getAll();

        $french  = $categories->pluck('name');
        $german  = $categories->pluck('name_de');
        $italian = $categories->pluck('name_it');

        if($french->contains($categorie))  return 0;
        if($german->contains($categorie))  return 1;
        if($italian->contains($categorie)) return 2;
    }

    public function findCategory($name)
    {
        $exist = $this->categorie->searchByName($name);
        
        if(!$exist) {
            $exist = $this->categorie->create(['name' => $name,  'name_de' => $name,  'name_it' => $name]);
        }

        return $exist;
    }
}