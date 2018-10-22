<?php namespace App\Droit\Colloque\Repo;

use App\Droit\Colloque\Repo\ColloqueInterface;
use App\Droit\Colloque\Entities\Colloque as M;

class ColloqueEloquent implements ColloqueInterface{

    protected $colloque;

    public function __construct(M $colloque)
    {
        $this->colloque = $colloque;
    }

    public function getAll($active = false, $archives = false)
    {
        return $this->colloque->active($active)->archives($archives)->orderBy('start_at','DESC')->get();
    }

    public function getAllAdmin($active = false, $archives = false)
    {
        return $this->colloque->admin($active)->archives($archives)->orderBy('start_at','DESC')->get();
    }

    public function search($term)
    {
        return $this->colloque->where('visible','=',1)
            ->where(function($q) use ($term){
                $q ->where('titre', 'like', '%'.$term.'%')
                    ->orWhere('sujet', 'like', '%'.$term.'%')
                    ->orWhere('soustitre', 'like', '%'.$term.'%');
            })->get();
    }

    public function getCurrent($registration = true, $isVisible = true)
    {
        return $this->colloque
            ->active($registration)
            ->visible($isVisible)
            ->orderBy('start_at','ASC')->get();
    }

    public function getArchive()
    {
        return $this->colloque->archives(true)->isVisible(true)->orderBy('start_at','DESC')->get();
    }

    public function getByYear($year)
    {
        return $this->colloque->whereRaw('YEAR(`start_at`) = ?', [$year])->orderBy('start_at','DESC')->get();
    }

    public function getYears()
    {
        return $this->colloque->select('start_at')->archives(true)->orderBy('start_at','DESC')->get();
    }

    public function find($id){

        return $this->colloque->with(['documents','location','options','options.groupe'])->findOrFail($id);
    }

    public function eventList($centres = [],$archived = false, $name = null)
    {
        return $this->colloque->archived($archived)->name($name)->with(['centres'])->centres($centres)->get();
    }

    public function increment($colloque_id)
    {
        $colloque = $this->colloque->find($colloque_id);
        $colloque->counter = $colloque->counter + 1;
        $colloque->save();

        return $colloque->counter;
    }

    public function getNewNoInscription($colloque_id){

        $counter = $this->increment($colloque_id);

        return $colloque_id.'-'.date('Y') .'/'.$counter;
    }

    public function create(array $data){

        $colloque = $this->colloque->create(array(
            'titre'           => $data['titre'],
            'sujet'           => $data['sujet'],
            'organisateur'    => $data['organisateur'],
            'soustitre'       => (isset($data['soustitre']) ? $data['soustitre'] : null),
            'url'             => (isset($data['url']) ? $data['url'] : null),
            'remarques'       => (isset($data['remarques']) ? $data['remarques'] : null),
            'location_id'     => (isset($data['location_id']) ? $data['location_id'] : null),
            'adresse_id'      => (isset($data['adresse_id']) ? $data['adresse_id'] : 1),
            'start_at'        => $data['start_at'],
            'end_at'          => (isset($data['end_at']) && !empty($data['end_at']) ? $data['end_at'] : null),
            'registration_at' => $data['registration_at'],
            'active_at'       => (isset($data['active_at']) ? $data['active_at'] : null),
            'visible'         => null,
            'created_at'      => \Carbon\Carbon::now(),
            'updated_at'      => \Carbon\Carbon::now()
        ));

        if( ! $colloque )
        {
            return false;
        }

        // centres
        if(isset($data['centres']))
        {
            $colloque->centres()->attach($data['centres']);
        }

        // illustration
        if(isset($data['illustration']))
        {
            $colloque->documents()->create( ['colloque_id' => $colloque->id ,'display' => 1,'type' => 'illustration','titre' => 'Illustration','path' => $data['illustration'] ]);
        }

        return $colloque;
    }

    public function update(array $data){

        $colloque = $this->colloque->findOrFail($data['id']);

        if( ! $colloque )
        {
            return false;
        }

        $colloque->fill($data);

        if(isset($data['end_at'])){
            $colloque->end_at  = (!empty($data['end_at']) ? $data['end_at'] : null);
        }

        if(isset($data['active_at'])){
            $colloque->active_at  = (!empty($data['active_at']) ? $data['active_at'] : null);
        }

        if(isset($data['url'])){
            $colloque->url  = (!empty($data['url']) ? $data['url'] : null);
        }

        if(isset($data['capacite']))
        {
            $colloque->capacite = $data['capacite'] > 0 ? $data['capacite'] : null;
        }

        $colloque->save();

        // centres
        if(isset($data['centres']))
        {
            $colloque->centres()->sync($data['centres']);
        }

        return $colloque;
    }

    public function delete($id){

        $colloque = $this->colloque->find($id);

        return $colloque->delete();
    }
}
