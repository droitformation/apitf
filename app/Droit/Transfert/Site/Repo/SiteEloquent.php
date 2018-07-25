<?php namespace App\Droit\Transfert\Site\Repo;

use App\Droit\Transfert\Site\Repo\SiteInterface;
use App\Droit\Transfert\Site\Entities\Site as M;

class SiteEloquent implements SiteInterface{

    protected $site;

    public function __construct(M $site)
    {
        $this->site = $site;
    }

    public function getAll(){

        return $this->site->with(['arrets','analyses'])->orderBy('id','desc')->get();
    }

    public function find($id){

        return $this->site->find($id);
    }

    public function findBySlug($slug)
    {
        $site = $this->site->with(['menus'])->where('slug','=',$slug)->get();

        if(!$site->isEmpty())
        {
            return $site->first();
        }

        return null;
    }

    public function create(array $data){

        $site = $this->site->create(array(
            'nom'    => $data['nom'],
            'url'    => $data['url'],
            'logo'   => $data['logo'],
            'slug'   => $data['slug'],
            'prefix' => $data['prefix']
        ));

        if( ! $site )
        {
            return false;
        }

        return $site;
    }

    public function update(array $data){

        $site = $this->site->findOrFail($data['id']);

        if( ! $site )
        {
            return false;
        }

        $site->fill($data);
        $site->save();

        return $site;
    }

    public function delete($id){

        $site = $this->site->find($id);

        return $site->delete();

    }
}
