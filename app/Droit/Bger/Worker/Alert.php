<?php

namespace App\Droit\Bger\Worker;

use App\Droit\Bger\Worker\AlertInterface;

use App\Droit\Decision\Repo\DecisionInterface;
use App\Droit\User\Repo\UserInterface;

class Alert implements AlertInterface
{
    protected $decision;
    protected $user;
    protected $cadence;

    public $publication_at;

    public function __construct(DecisionInterface $decision, UserInterface $user)
    {
        $this->decision = $decision;
        $this->user     = $user;
    }

    /**
     * @param mixed $cadence
     */
    public function setCadence($cadence)
    {
        $this->cadence = $cadence;

        return $this;
    }

    /**
     * @param mixed $publication_at
     */
    public function setDate($publication_at)
    {
        $this->publication_at = $publication_at;

        return $this;
    }

    /**
     * @param mixed $publication_at
     */
    public function getDate()
    {
        return !is_array($this->publication_at) ? $this->publication_at : array_pop($this->publication_at);
    }

    public function getUsers()
    {
        // get all user
        $already = $this->alertAlreadySent();
        $abos    = $this->user->getByCadence($this->cadence, $already->pluck('user_id')->toArray());

        return $abos->map(function($user){
            // Search in new decisisions of the day or week
            return ['user' => $user, 'abos' => $this->getUserAbos($user)];
        })->reject(function($item){
            // Reject if no decisions found
            return $item['abos']->isEmpty();
        });
    }

    public function getUserAbos($user)
    {
        return $user->abonnements->map(function($list,$categorie_id){
            // list keys:  keywords => collection, published => bool
            $keywords  = $list['keywords'];
            $published = $list['published'] > 0 ? 1 : 0;

            return $keywords->map(function($keyword) use ($categorie_id,$published){
                // Find decisions for categories published or not
                $keyword = isset($keyword) && !$keyword->isEmpty() ? array_filter($keyword->toArray()) : null;

                // don't search for général categorie if no keywords
                if(!$keyword && $categorie_id == 247){
                    return collect([]);
                }

                return $this->findDecision($keyword,$categorie_id,$published);

            })->reject(function($item){
                // Reject if no decisions found
                return $item->isEmpty();
            });

        })->flatten()->groupBy('id')->map(function ($item, $key) {
            return [
                'decision'  => $item->first(),
                'categorie' => $item->first()->categorie_id,
                'keywords'  => $item->pluck('keywords')->flatten()->implode(','),
            ];
        });
    }

    public function findDecision($keyword,$categorie_id,$published)
    {
        $decisions = $this->decision->search([
            'terms'     => $keyword,
            'categorie' => $categorie_id,
            'published' => $published,
            'publication_at' => $this->publication_at
        ]);

        return $decisions->map(function ($item, $key) use ($categorie_id,$keyword) {
            $item = $item->setAttribute( 'keywords', $keyword);
            return $item;
        });
    }

    public function sent($abo){

        return \App\Droit\Bger\Entities\Alert_sent::create(['user_id' => $abo->id, 'publication_at' => $this->getDate()]);
    }

    public function alertAlreadySent(){

        return \App\Droit\Bger\Entities\Alert_sent::whereDate('publication_at', $this->getDate())->get();
    }
}