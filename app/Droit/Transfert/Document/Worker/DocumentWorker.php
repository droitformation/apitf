<?php
namespace App\Droit\Document\Worker;

use App\Droit\Document\Repo\DocumentInterface;

class DocumentWorker{

    protected $document;

    public function __construct(DocumentInterface $document)
    {
        $this->document = $document;
    }

    public function updateColloqueDoc($colloque_id, $document)
    {
        $type  = key($document);
        $path  = $document[$type];

        $exist = $this->document->getDocForColloque($colloque_id, $type);

        if($exist)
        {
            $exist->path = $path;
            $exist->save();
        }
        else
        {
            $this->document->create(['colloque_id' => $colloque_id, 'type' => $type, 'path' => $path]);
        }

    }
}

