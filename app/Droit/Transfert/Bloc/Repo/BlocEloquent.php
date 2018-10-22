<?php namespace App\Droit\Transfert\Bloc\Repo;

use App\Droit\Transfert\Bloc\Repo\BlocInterface;
use App\Droit\Transfert\Bloc\Entities\Bloc as M;

class BlocEloquent implements BlocInterface{

	protected $bloc;

	/**
	 * Construct a new SentryUser Object
	 */
	public function __construct(M $bloc)
	{
		$this->bloc = $bloc;
	}

    public function getAll($site = null)
	{
        return $this->bloc->with(['pages'])->whereHas('pages', function ($query) use ($site) {
			$query->where('pages.site_id', '=', $site);
		})->orderBy('rang','ASC')->get();
    }

	public function find($id)
	{
		return $this->bloc->find($id);
	}

    public function findyByPosition(array $positions)
	{
        return $this->bloc->whereIn('position', $positions)->orderBy('rang','ASC')->get();
    }

	public function findyByType($type)
	{
		return $this->bloc->where('type','=',$type)->orderBy('rang','ASC')->get();
	}

	public function create(array $data)
	{
		$bloc = $this->bloc->create([
			'title'      => (isset($data['title']) ? $data['title'] : ''),
			'content'    => $data['content'],
			'image'      => (isset($data['image']) ? $data['image'] : ''),
			'url'        => (isset($data['url']) ? $data['url'] : ''),
			'rang'       => (isset($data['rang']) ? $data['rang'] : 0),
			'type'       => $data['type'],
			'site_id'    => (isset($data['site_id']) ? $data['site_id'] : 1),
			'position'   => $data['position'],
			'created_at' => date('Y-m-d G:i:s'),
			'updated_at' => date('Y-m-d G:i:s')
		]);

		if( ! $bloc )
		{
			return false;
		}

        // pages
        if(isset($data['page_id']))
        {
            $bloc->pages()->attach($data['page_id']);
        }

        return $bloc;
	}
	
	public function update(array $data){

        $bloc = $this->bloc->find($data['id']);
		
		if( ! $bloc )
		{
			return false;
		}

        $bloc->fill($data);

        if(isset($data['image']))
        {
            $bloc->image = $data['image'];
        }

        // pages
        if(isset($data['page_id']))
        {
            $bloc->pages()->sync($data['page_id']);
        }

		$bloc->updated_at = date('Y-m-d G:i:s');

		$bloc->save();
		
		return $bloc;
	}

	public function delete($id){

        $bloc = $this->bloc->find($id);

		return $bloc->delete();
	}

}
