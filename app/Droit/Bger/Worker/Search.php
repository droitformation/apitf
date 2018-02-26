<?php

namespace App\Droit\Bger\Worker;

use App\Droit\Bger\Worker\SearchInterface;

use App\Droit\Decision\Repo\DecisionInterface;
use App\Droit\Bger\Utility\Clean;

class Search implements SearchInterface
{
    protected $clean;
    protected $decision;

    public function __construct(DecisionInterface $decision, Clean $clean)
    {
        $this->decision = $decision;
        $this->clean    = $clean;
    }

    public function all($search)
    {

    }
}