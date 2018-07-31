<?php namespace App\Droit\Transfert\Page\Repo;

use App\Droit\Transfert\Page\Repo\PageInterface;
use App\Droit\Transfert\Page\Entities\Page as M;

class PageEloquent implements PageInterface{

    protected $page;

    public function __construct(M $page)
    {
        $this->page = $page;
    }

    public function getAll($site = null)
    {
        return $this->page->sites($site)->orderBy('pages.rang')->get();
    }

    public function getTree($key = null, $seperator = '  ',$site = null)
    {
        return $this->page->getNestedList('title', $key, $seperator);
    }

    public function search($term)
    {
        return $this->page->where('content','LIKE', '%'.$term.'%')->get();
    }

    public function getRoot($site = null)
    {
        return $this->page->sites($site)->where('parent_id','=',0)->orderBy('rang')->get();
    }

    public function find($id){

        return $this->page->with(['contents','blocs'])->find($id);
    }

    public function getBySlug($site,$slug)
    {
        return $this->page->with(['contents','blocs'])
            ->sites($site)
            ->where(function ($query) use ($slug) {
                $query->where('slug','=',$slug)->orWhere('template','=',$slug);
            })
            ->first();
    }

    public function buildTree($data)
    {
        return $this->page->buildTree($data);
    }

    public function create(array $data){

        $page = $this->page->create(array(
            'title'       => (isset($data['title']) ? $data['title'] : null),
            'content'     => (isset($data['content']) ? $data['content'] : null),
            'template'    => (isset($data['template']) ? $data['template'] : null),
            'site_id'     => $data['site_id'],
            'menu_title'  => $data['menu_title'],
            'slug'        => (isset($data['slug']) && !empty($data['slug']) ? $data['slug'] : \Str::slug($data['menu_title'])),
            'rang'        => (isset($data['rang']) ? $data['rang'] : 0),
            'menu_id'     => (isset($data['menu_id']) ? $data['menu_id'] : null),
            'url'         => (isset($data['url']) ? $data['url'] : null),
            'isExternal'  => (isset($data['isExternal']) && $data['isExternal'] > 0 ? 1 : null),
            'hidden'      => (isset($data['hidden']) ? 1 : null),
            'created_at'  => date('Y-m-d G:i:s'),
            'updated_at'  => date('Y-m-d G:i:s')
        ));

        if( !$page )
        {
            return false;
        }

        if($data['parent_id'] && $data['parent_id'] > 0)
        {
            $parent = $this->page->findOrFail($data['parent_id']);
            $page->makeChildOf($parent);
        }

        return $page;

    }

    public function update(array $data){

        $page = $this->page->findOrFail($data['id']);

        if( ! $page )
        {
            return false;
        }

        $page->fill($data);

        if(empty($data['parent_id']))
        {
            $page->parent_id = null;
        }

        $page->hidden     = $data['hidden'] > 0 ? 1 : null;
        $page->updated_at = date('Y-m-d G:i:s');

        $page->save();
        
        if($data['parent_id'] > 0 && $data['parent_id'] != null)
        {
            $parent = $this->page->find($data['parent_id']);
            if($parent){
                $page->makeChildOf($parent);
            }
        }

        return $page;
    }

    public function updateSorting(array $data)
    {
        if(!empty($data))
        {
            foreach($data as $rang => $id)
            {
                $page = $this->find($id);

                if( ! $page )
                {
                    return false;
                }

                $page->rang = $rang;
                $page->save();
            }

            return true;
        }
    }

    public function delete($id){

        $page = $this->page->find($id);

        return $page->delete($id);
    }

}
