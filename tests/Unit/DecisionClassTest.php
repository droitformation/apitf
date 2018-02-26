<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Goutte\Client;

class DecisionClassTest extends TestCase
{

    public function testMakeUrlToArret()
    {
        $decision = new \App\Droit\Bger\Utility\Decision();

        $decision->setDecision(['decision_at'=> '2016-09-15', 'numero' => '5A_1016/2015']);

        $result = $decision->makeUrl();
        $expect = 'http://relevancy.bger.ch/php/aza/http/index.php?highlight_docid=aza://aza://15-09-2016-5A_1016-2015&lang=fr&zoom=&type=show_document';

        $this->assertSame($expect,$result);
    }

    public function testCrawlerHtml()
    {
        $url = 'http://relevancy.bger.ch/php/aza/http/index.php?highlight_docid=aza://aza://15-09-2016-5A_1016-2015&lang=fr&zoom=&type=show_document';
        $data =  [
            'numero'         => '5A_1016/2015',
            'categorie'      => 'Droits réels',
            'subcategorie'   => 'mesures provisionnelles (rectification du registre foncier)',
            'publication_at' => '2017-10-01',
            'decision_at'    => '2016-09-15',
        ];

        $crawler        = \Mockery::mock('Symfony\Component\DomCrawler\Crawler');
        $client         = \Mockery::mock('Goutte\Client');

        $decision       = new \App\Droit\Bger\Utility\Decision();
        $decision->html = $client;

        $client->shouldReceive('request')->with('GET',$url)->andReturn($crawler);
        $crawler->shouldReceive('filter')->twice()->andReturn($crawler);
        $crawler->shouldReceive('each')->once()->andReturn(['Un titre']);
        $crawler->shouldReceive('each')->once()->andReturn(['Un contenu']);

        $result = $decision->setDecision($data)->grabHtml();

        $this->assertEquals(['title' => '<h1>Un titre</h1>', 'content' => 'Un contenu'],$result);
    }

    public function testGetArrayFails()
    {
        $url = 'http://relevancy.bger.ch/php/aza/http/index.php?highlight_docid=aza://aza://15-09-2016-5A_1016-2015&lang=fr&zoom=&type=show_document';
        $data =  [
            'numero'         => '5A_1016/2015',
            'categorie'      => 'Droits réels',
            'subcategorie'   => 'mesures provisionnelles (rectification du registre foncier)',
            'publication_at' => '2017-10-01',
            'decision_at'    => '2016-09-15',
        ];

        $crawler        = \Mockery::mock('Symfony\Component\DomCrawler\Crawler');
        $client         = \Mockery::mock('Goutte\Client');

        $decision       = new \App\Droit\Bger\Utility\Decision();
        $decision->html = $client;

        $client->shouldReceive('request')->with('GET',$url)->andReturn($crawler);
        $crawler->shouldReceive('filter')->twice()->andReturn($crawler);
        $crawler->shouldReceive('each')->once()->andReturn([]);
        $crawler->shouldReceive('each')->once()->andReturn([]);

        $result = $decision->setDecision($data)->getArret();

        $this->assertFalse($result);
    }
}
