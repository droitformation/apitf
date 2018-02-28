<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Droit\Decision\Repo\DecisionInterface;
use App\Droit\Decision\Worker\DecisionWorkerInterface;
use App\Droit\Categorie\Repo\CategorieInterface;

class ApiController extends Controller
{
    protected $decision;
    protected $worker;
    protected $categorie;

    public function __construct(DecisionInterface $decision, DecisionWorkerInterface $worker, CategorieInterface $categorie)
    {
        setlocale(LC_ALL, 'fr_FR.UTF-8');

        $this->decision = $decision;
        $this->worker = $worker;
        $this->categorie = $categorie;
    }

    public function categories()
    {
        $categories = $this->categorie->getAll();
        $parents    = $this->categorie->getParents()->pluck('nom','id')->all();

        $categories = $categories->groupBy('parent_id')->mapWithKeys(function ($items,$key) use ($parents){
            $title = isset($parents[$key]) ? $parents[$key] : 0;
            return [str_slug($title) => [
                'title' => $title,
                'categories' => $items->toArray()
            ]];
        })->toArray();

        ksort($categories);

        return response()->json($categories,200);
    }

    public function categorie($id)
    {
        $decisions = $this->decision->byCategories($id);

        return response()->json($decisions,200);
    }
}


