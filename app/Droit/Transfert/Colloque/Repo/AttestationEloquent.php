<?php namespace App\Droit\Colloque\Repo;

use App\Droit\Colloque\Repo\AttestationInterface;
use App\Droit\Colloque\Entities\Colloque_attestation as M;

class AttestationEloquent implements AttestationInterface{

    protected $attestation;

    public function __construct(M $attestation)
    {
        $this->attestation = $attestation;
    }

    public function getAll()
    {
        return $this->attestation->with(['colloque'])->get();
    }

    public function find($id){

        return $this->attestation->with(['colloque'])->find($id);
    }

    public function create(array $data){

        $attestation = $this->attestation->create(array(
            'colloque_id'     => $data['colloque_id'],
            'title'           => $data['title'],
            'signature'       => $data['signature'],
            'lieu'            => (isset($data['lieu']) ? $data['lieu'] : null),
            'telephone'       => (isset($data['telephone']) ? $data['telephone'] : null),
            'organisateur'    => (isset($data['organisateur']) ? $data['organisateur'] : null),
            'comment'         => (isset($data['comment']) ? $data['comment'] : null),
            'created_at'      => \Carbon\Carbon::now(),
            'updated_at'      => \Carbon\Carbon::now()
        ));

        if( ! $attestation )
        {
            return false;
        }

        return $attestation;
    }

    public function update(array $data){

        $attestation = $this->attestation->findOrFail($data['id']);

        if( ! $attestation )
        {
            return false;
        }

        $attestation->fill($data);

        $attestation->save();

        return $attestation;
    }

    public function delete($id){

        $attestation = $this->attestation->find($id);

        return $attestation->delete();

    }
}
