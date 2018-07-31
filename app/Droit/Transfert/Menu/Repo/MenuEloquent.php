<?php namespace App\Droit\Transfert\Menu\Repo;

use App\Droit\Transfert\Menu\Repo\MenuInterface;
use App\Droit\Transfert\Menu\Entities\Menu as M;

class MenuEloquent implements MenuInterface{

    protected $menu;

    public function __construct(M $menu)
    {
        $this->menu = $menu;
    }

    public function getAll($site = null){

        return $this->menu->sites($site)->get();
    }

    public function find($id){

        return $this->menu->find($id);
    }

    public function create(array $data){

        $menu = $this->menu->create(array(
            'title'    => $data['title'],
            'position' => $data['position'],
            'site_id'  => $data['site_id']
        ));

        if( ! $menu )
        {
            return false;
        }

        return $menu;

    }

    public function update(array $data){

        $menu = $this->menu->findOrFail($data['id']);

        if( ! $menu )
        {
            return false;
        }

        $menu->fill($data);

        $menu->save();

        return $menu;
    }

    public function delete($id){

        $menu = $this->menu->find($id);

        return $menu->delete();

    }
}
