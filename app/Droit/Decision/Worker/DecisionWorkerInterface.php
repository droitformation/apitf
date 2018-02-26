<?php
/**
 * Created by PhpStorm.
 * User: cindyleschaud
 * Date: 12.07.17
 * Time: 15:09
 */

namespace App\Droit\Decision\Worker;

use Illuminate\Support\Collection;

interface DecisionWorkerInterface
{
    public function setMissingDates(Collection $dates = null);
    public function getMissingDates();
    public function getExistingDates();
    public function update();
    public function insert($data);
}