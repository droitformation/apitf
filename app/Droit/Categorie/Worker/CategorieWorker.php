<?php namespace  App\Droit\Categorie\Worker;

use App\Droit\Categorie\Worker\CategorieWorkerInterface;
use App\Droit\Categorie\Repo\CategorieKeywordInterface;
use App\Droit\Decision\Repo\DecisionInterface;

class CategorieWorker implements CategorieWorkerInterface
{
    protected $keyword;
    protected $decision;

    public function __construct(CategorieKeywordInterface $keyword, DecisionInterface $decision)
    {
        $this->keyword  = $keyword;
        $this->decision = $decision;
    }

    public function process($publications_at)
    {
        $categories = $this->find($publications_at);

        // Attach special categories to decisions
        $categories->map(function($decisions,$categorie_id){
            $decisions->map(function($decision) use ($categorie_id){
                return $decision->other_categories()->attach($categorie_id);
            });
        });
    }

    // Find decision with special keywords
    public function find($publications_at)
    {
        $keywords = $this->keyword->getAll();

        return $keywords->groupBy('categorie_id')->map(function($keyword){
            return $keyword->pluck('keywords_list')->toArray();
        })->map(function ($keywords, $categorie_id) use ($publications_at) {
            return collect($keywords)->map(function ($keyword) use ($publications_at){
                return $this->decision->search(['terms' => $keyword, 'publications_at' => $publications_at]);
            })->flatten(1);
        });
    }
}