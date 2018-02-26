<?php

namespace App\Droit\Bger\Utility;

use Goutte\Client;
use \ForceUTF8\Encoding;

class Decision {

    public $html;
    public $dispatch;
    public $decision;

	function __construct() 
    {
        $this->dispatch = new \App\Droit\Bger\Utility\Dispatch();
		$this->html     = new Client();

        // Hackery to allow HTTPS
        $this->html->setClient(new \GuzzleHttp\Client(['timeout' => 60, 'verify' => false]));
	}

    public function setDecision($decision)
    {
        $this->decision = $decision;

        return $this;
    }

    public function makeUrl()
    {
        $date   = \Carbon\Carbon::parse($this->decision['decision_at'])->format('d-m-Y');
        $numero = str_replace('/','-', $this->decision['numero']);

        return 'http://relevancy.bger.ch/php/aza/http/index.php?highlight_docid=aza://aza://'.$date.'-'.$numero.'&lang=fr&zoom=&type=show_document';
    }

	/* ========================================
	   GRAB ARRET FROM TF WEBSITE
	==========================================*/
    /**
     * [numero] => 2C_348/2015
     * [categorie] => Energie *
     * [subcategorie] => Verzugszinsen auf der Rückerstattung für in den Jahren 2009 und 2010 geleistete SDL-Akontozahlungen
     * [publication_at] => 2016-10-20
     * [decision_at] => 2016-05-23
     *
     * @param  array
     * @return array
     */
	public function getArret()
	{
        $arret = $this->grabHtml($this->decision);

        // The grab of html has failed return false
        if(empty(array_filter($arret))){
            return false;
        }

        $url   = $this->makeUrl($this->decision['decision_at'],$this->decision['numero']);

        $categorie = cleanCategorieString($this->decision['categorie']);

        $publish   = $this->dispatch->isPublication($this->decision['categorie']);
        $language  = $this->dispatch->isLangugage($categorie);
        $categorie = $this->dispatch->findCategory($categorie);

        return [
            'publication_at' => isset($this->decision['publication_at']) ? $this->decision['publication_at'] : '',
            'decision_at'    => isset($this->decision['decision_at']) ? $this->decision['decision_at'] : '',
            'categorie_id'   => $categorie->id,
            'remarque'       => isset($this->decision['subcategorie']) ? $this->decision['subcategorie'] : '',
            'link'           => $url,
            'numero'         => isset($this->decision['numero']) ? $this->decision['numero'] : '',
            'texte'          => $arret['title'].$arret['content'],
            'langue'         => $language,
            'publish'        => $publish,
            'updated'        => null
        ];
	}

    public function grabHtml()
    {
        $crawler = $this->html->request('GET', $this->makeUrl());

        // Get title of page
        $content['title'] = $crawler->filter('title')->each(function ($node) {
            return $node->text();
        });

        // Get the content
        $content['content'] = $crawler->filter('div[class=content]')->each(function ($node) {
            return cleanText(utf8_decode($node->html()), $repto = NULL);
        });

        if(!empty($content['content'])) {
            // delete http://relevancy.bger.ch comment
            if(isset($content['content'][1])) unset($content['content'][1]);

            $content['content'] = isset($content['content'][0]) ? Encoding::toUTF8($content['content'][0]) : '';
        }

        if(!empty($content['title'])) {
            $content['title'] = isset($content['title'][0]) ? '<h1>'.$content['title'][0].'</h1>' : '';
        }

        return $content;
	}
}