<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Goutte\Client;

class ListeClassTest extends TestCase
{

    public function testGetDateList()
    {
        $liste = new \App\Droit\Bger\Utility\Liste();

        $htmlarray = [
            ['','15.09.2016','5A_1016/2015','','Droits réels'],
            ['','','','','mesures provisionnelles (rectification du registre foncier)'],
            ['','14.09.2016','5A_6/2016','','Droit de la famille*'],
            ['','','','','divorce, communication de la décision'],
        ];

        $expect = [
            [
                'numero'         => '5A_1016/2015',
                'categorie'      => 'Droits réels',
                'subcategorie'   => 'mesures provisionnelles (rectification du registre foncier)',
                'publication_at' => '2017-10-01',
                'decision_at'    => '2016-09-15',
            ],
            [
                'numero'         => '5A_6/2016',
                'categorie'      => 'Droit de la famille*',
                'subcategorie'   => 'divorce, communication de la décision',
                'publication_at' => '2017-10-01',
                'decision_at'    => '2016-09-14',
            ]
        ];


        $liste->date = '20171001';
        $liste->listData = $htmlarray;

        $liste->prepareDataFromList();

        $arrets = $liste->listArret;

        $this->assertSame($expect,$arrets->toArray());

    }

    public function testValidateAndTransformDateToDateString()
    {
        $liste = new \App\Droit\Bger\Utility\Liste();

        $date   = '20170312';
        $expect = '2017-03-12';

        $result = $liste->validConvertDateString($date,'Ymd');

        $this->assertSame($expect,$result);
    }

    /**
     * @expectedException \App\Exceptions\ProblemFetchException
     */
    public function testGetDateListFailsWithoutDate()
    {
        $liste = new \App\Droit\Bger\Utility\Liste();
        $liste->getListDecisions();
    }
}
