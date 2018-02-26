<?php

namespace App\Droit\Bger\Worker;

interface AlertInterface{

    public function setCadence($cadence);
    public function setDate($publication_at);
    public function getDate();
    public function getUsers();
    public function findDecision($keyword,$categorie_id,$published);
    public function sent($abo);
    public function alertAlreadySent();
}