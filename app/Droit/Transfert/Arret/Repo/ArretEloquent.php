<?php namespace App\Droit\Transfert\Arret\Repo;

use App\Droit\Transfert\Arret\Repo\ArretInterface;
use App\Droit\Transfert\Arret\Entities\Arret as M;

class ArretEloquent implements ArretInterface{

	protected $arret;

	public function __construct(M $arret)
	{
		$this->arret = $arret;
	}

    public function getAll($site = null, $exclude = null)
    {
        $arrets = $this->arret->with(['categories','analyses'])->site($site);

        if(!empty($exclude))
        {
            $arrets->whereNotIn('id', $exclude);
        }

        return $arrets->orderBy('reference', 'ASC')->get();
    }

    public function allForSite($site, $options)
    {
        return $this->arret
            ->with(['categories','analyses'])
            ->categories($options['categories'])
            ->years($options['years'])
            ->site($site)
            ->analyses($options['display'])
            ->orderBy('pub_date', 'DESC')
            ->get();
            //->paginate(10);
    }

    public function getCount($site = null)
    {
        return $this->arret->site($site)->select('id')->get();
    }

    public function getLast($nbr,$site)
    {
        return $this->arret->with(['categories','analyses'])->site($site)->orderBy('id', 'ASC')->take(5)->get();
    }

    public function annees($site)
    {
        $arrets = $this->arret->where('site_id','=',$site)->get();

        return $arrets->groupBy(function ($archive, $key) {
            return $archive->pub_date->year;
        })->keys();
    }

    public function getAllActives($exclude = [], $site = null)
    {
        $arrets = $this->arret->where('site_id','=',$site)->with(['categories','analyses']);

        if(!empty($exclude))
        {
            $arrets->whereNotIn('id', $exclude);
        }

        return $arrets->orderBy('reference', 'ASC')->get();
    }

    public function getPaginate($nbr)
    {
        return $this->arret->with(['categories','analyses'])->orderBy('pub_date', 'DESC')->paginate($nbr);
    }

    public function getLatest($exclude = []){

        if(!empty($exclude))
        {
            $arrets = $this->arret->whereNotIn('id', $exclude)->with(['analyses'])->orderBy('id', 'ASC')->get();

            $new = $arrets->filter(function($item)
            {
                if (!$item->analyses->isEmpty()) {
                    return true;
                }
            });

            return $new->take(5);
        }

        return false;
    }

	public function find($id, $trashed = null){

        if(is_array($id))
        {
            return $this->arret->trashed($trashed)->whereIn('id', $id)->with(['categories','analyses'])->get();
        }

		return $this->arret->with(['categories','analyses'])->where('id','=',$id)->trashed($trashed)->get()->first();
	}

    public function findyByImage($file){

        return $this->arret->where('file','=',$file)->get();
    }

	public function create(array $data){

		$arret = $this->arret->create(array(
			'site_id'    => $data['site_id'],
            'user_id'    => isset($data['user_id']) ? $data['user_id'] : null,
            'reference'  => $data['reference'],
            'pub_date'   => $data['pub_date'],
            'abstract'   => $data['abstract'],
            'pub_text'   => $data['pub_text'],
            'file'       => isset($data['file']) ? $data['file'] : null,
            'dumois'     => (isset($data['dumois']) && $data['dumois'] == 1 ? 1 : 0),
			'created_at' => date('Y-m-d G:i:s'),
			'updated_at' => date('Y-m-d G:i:s')
		));

		if( ! $arret )
		{
			return false;
		}

        // Insert related categories
        if(isset($data['categories']) && !empty($data['categories']))
        {
            $arret->categories()->sync($data['categories']);
        }

		return $arret;
		
	}
	
	public function update(array $data){

        $arret = $this->arret->findOrFail($data['id']);
		
		if( ! $arret )
		{
			return false;
		}

        $arret->fill($data);
        $arret->dumois = isset($data['dumois']) ? 1 : null;

        if(isset($data['file']))
        {
            $arret->file = $data['file'];
        }

		$arret->updated_at = date('Y-m-d G:i:s');

		$arret->save();

        // Insert related categories
        if(isset($data['categories']) && !empty($data['categories']))
        {
            $arret->categories()->sync($data['categories']);
        }
		
		return $arret;
	}

	public function delete($id){

        $arret = $this->arret->find($id);

		return $arret->delete();
	}

}
