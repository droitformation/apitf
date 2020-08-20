<?php

namespace App\Droit\User\Worker;

interface UserWorkerInterface{

    public function find($id,$data = null);
}