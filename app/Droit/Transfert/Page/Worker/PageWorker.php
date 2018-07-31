<?php namespace App\Droit\Page\Worker;

use App\Droit\Page\Repo\PageInterface;

class PageWorker{

    protected $page;

    public function __construct(PageInterface $page)
    {
        $this->page = $page;
    }

    public function prepareTree($nodes){

        $data = [];

        if(is_array($nodes))
        {
            foreach($nodes as $node)
            {
                $page = $this->page->find($node['id']);

                $data[$node['id']]['id']         = $node['id'];
                $data[$node['id']]['auteur']     = $page->auteur;
                $data[$node['id']]['title']      = $page->title;
                $data[$node['id']]['slug']       = $page->slug;
                $data[$node['id']]['page']       = $page->page;
                $data[$node['id']]['content']    = $page->content;

                if(isset($node['children']) && !empty($node['children']))
                {
                    $children = $this->prepareTree($node['children']);
                    $data[$node['id']]['children'] = $children;
                }
            }
        }

        return $data;

    }

}