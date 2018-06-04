<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Droit\Decision\Repo\DecisionInterface;
use App\Droit\Decision\Worker\DecisionWorkerInterface;
use App\Droit\Categorie\Repo\CategorieInterface;

class MainController extends Controller
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
                'id' => $key,
                'categories' => $items->toArray()
            ]];
        })->toArray();

        ksort($categories);

        return response()->json($categories,200);
    }

    public function categorie($id)
    {
        $decisions = $this->decision->byCategories($id);
        $decisions = $decisions->sortBy(function($col) {
            return \Carbon\Carbon::parse($col->publication_at)->format('ymd');
        })->reverse();

        return response()->json($decisions,200);
    }

    public function decisions()
    {
        $decisions = $this->decision->getAll();

        return response()->json($decisions,200);
    }

    public function search(Request $request)
    {
        $decisions = $request->input('terms',null) || $request->input('categorie_id',null) || $request->input('period',null) ?
            $this->decision->searchArchives([
                'period' => $request->input('period',null),
                'categorie_id' => $request->input('categorie_id',null),
                'published' => $request->input('published',null),
                'terms' => $request->input('terms',null)
            ]) : collect([]);

        return response()->json($decisions,200);
    }

    public function decision($id,$year)
    {
        /*if($year == date('Y')){
            $decision = $this->decision->find($id);
        }
        else{
            $decision = $this->decision->findArchive($id,$year);
        }*/

        // For now without archives tables
        $decision = $this->decision->find($id);

        return response()->json($decision,200);
    }
}


