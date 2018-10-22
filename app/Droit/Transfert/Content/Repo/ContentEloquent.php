<?php namespace App\Droit\Transfert\Content\Repo;

use App\Droit\Transfert\Content\Repo\ContentInterface;
use App\Droit\Transfert\Content\Entities\Content as M;

class ContentEloquent implements ContentInterface{

	protected $content;
	
	public function __construct(M $content)
	{
		$this->content = $content;
	}

    public function getAll(){

        return $this->content->with(['page'])->get();
    }

	public function find($id){
				
		return $this->content->findOrFail($id);
	}

	public function findyByType($type){

		return $this->content->where('type','=',$type)->orderBy('rang','ASC')->get();
	}

	public function create(array $data){

		$content = $this->content->create(array(
			'title'        => (isset($data['title']) ? $data['title'] : ''),
			'content'      => (isset($data['content']) ? $data['content'] : ''),
            'image'        => (isset($data['image']) ? $data['image'] : ''),
			'url'          => (isset($data['url']) ? $data['url'] : ''),
			'categorie_id' => (isset($data['categorie_id']) ? $data['categorie_id'] : null),
			'type'         => $data['type'],
			'page_id'      => $data['page_id'],
            'rang'         => (isset($data['rang']) ? $data['rang'] : 0),
			'style'        => (isset($data['style']) ? $data['style'] : ''),
			'created_at'   => date('Y-m-d G:i:s'),
			'updated_at'   => date('Y-m-d G:i:s')
		));

		if( ! $content )
		{
			return false;
		}
		
		return $content;
		
	}
	
	public function update(array $data){

        $content = $this->content->findOrFail($data['id']);
		
		if( ! $content )
		{
			return false;
		}

        $content->fill($data);

        if(isset($data['image']))
        {
            $content->image = $data['image'];
        }

		$content->updated_at = date('Y-m-d G:i:s');

		$content->save();
		
		return $content;
	}

	public function updateSorting(array $data)
	{
		if(!empty($data))
		{
			foreach($data as $rang => $id)
			{
				$content = $this->find($id);

				if( ! $content )
				{
					return false;
				}

				$content->rang = $rang;
				$content->save();
			}

			return true;
		}
	}
	
	public function delete($id){

        $content = $this->content->find($id);

		return $content->delete();
	}

}
