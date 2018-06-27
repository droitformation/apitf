<?php namespace App\Droit\Transfert\Analyse\Repo;

use App\Droit\Transfert\Analyse\Repo\AnalyseInterface;
use App\Droit\Transfert\Analyse\Entities\Analyse as M;

class AnalyseEloquent implements AnalyseInterface{

	protected $analyse;

	/**
	 * Construct a new SentryUser Object
	 */
	public function __construct(M $analyse)
	{
		$this->analyse = $analyse;
	}

    public function getAll($site = null, $exclude = [])
    {
        $analyse = $this->analyse->site($site)->with(['authors','categories','arrets']);

        if(!empty($exclude))
        {
            $analyse->whereHas('arrets', function ($query) use ($exclude) {
                $query->whereNotIn('arrets.id', $exclude);
            });
        }

        return $analyse->orderBy('pub_date', 'DESC')->get();
    }

    public function allForSite($site, $years = null)
    {
        return $this->analyse
            ->with(['arrets'])
            ->years($years)
            ->site($site)
            ->orderBy('pub_date', 'DESC')
            ->get();
    }

    public function getCount($site = null)
    {
        return $this->analyse->site($site)->select('id')->get();
    }

    public function getLast($nbr,$site)
    {
        return $this->analyse->with(['authors','categories','arrets'])->site($site)->orderBy('id', 'ASC')->take(5)->get();
    }

	public function find($id){
				
		return $this->analyse->with(['authors','categories','arrets'])->find($id);
	}

	public function create(array $data){

		$analyse = $this->analyse->create(array(
			'user_id'    => (isset($data['user_id']) ? $data['user_id'] : null),
            'pub_date'   => $data['pub_date'],
            'abstract'   => $data['abstract'],
            'file'       => (isset($data['file']) ? $data['file'] : null),
            'site_id'    => (isset($data['site_id']) ? $data['site_id'] : null),
            'title'      => (isset($data['title']) ? $data['title'] : null),
			'created_at' => date('Y-m-d G:i:s'),
			'updated_at' => date('Y-m-d G:i:s')
		));

		if( ! $analyse )
		{
			return false;
		}

        if(isset($data['categories']))
        {
            $analyse->categories()->sync($data['categories']);
        }

        if(isset($data['arrets']))
        {
            $analyse->arrets()->sync($data['arrets']);
        }

        if(isset($data['author_id']) && !empty($data['author_id']))
        {
            $analyse->authors()->sync($data['author_id']);
        }
		
		return $analyse;
		
	}
	
	public function update(array $data){

        $analyse = $this->analyse->findOrFail($data['id']);
		
		if( ! $analyse )
		{
			return false;
		}

        $analyse->fill($data);

        if(isset($data['file']))
        {
            $analyse->file = $data['file'];
        }

		$analyse->updated_at = date('Y-m-d G:i:s');

        if(isset($data['categories']))
        {
            $analyse->categories()->sync($data['categories']);
        }

        if(isset($data['arrets']))
        {
            $analyse->arrets()->sync($data['arrets']);
        }

        if(isset($data['author_id']) && !empty($data['author_id']))
        {
            $analyse->authors()->sync($data['author_id']);
        }

        if(isset($data['title']))
        {
            $analyse->title = !empty($data['title']) ? $data['title'] : null;
        }

		$analyse->save();
		
		return $analyse;
	}

	public function delete($id){

        $analyse = $this->analyse->find($id);

		return $analyse->delete();
	}

}
