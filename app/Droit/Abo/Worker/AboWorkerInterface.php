<?php

namespace App\Droit\Abo\Worker;

interface AboWorkerInterface{

    public function make($data);
    public function remove($data);
}