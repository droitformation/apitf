<?php
/**
 * Created by PhpStorm.
 * User: cindyleschaud
 * Date: 19.03.18
 * Time: 14:40
 */

namespace App\Droit\Newsletter;

use Mailjet\Request;
use \InlineStyle\InlineStyle;

class NewsletterWorker
{
    protected $mailjet;
    protected $main_list = '1793991';
    protected $url = null;
    protected $date;

    public function __construct()
    {
        setlocale(LC_ALL, 'fr_FR.UTF-8');

        $this->mailjet = new \App\Droit\Newsletter\MailjetService(
            new \Mailjet\Client(config('services.mailjet.api'),config('services.mailjet.secret')),
            new \Mailjet\Resources()
        );;
    }

    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    public function getSubject()
    {
        setlocale(LC_ALL, 'fr_FR.UTF-8');

        $date  = $this->date ? \Carbon\Carbon::parse($this->date) : \Carbon\Carbon::now();

        $dates = weekRange($date->toDateString());
        $start = $dates->first();
        $end   = $dates->last();

        return 'Newsletter Droit pour le Praticien | '.\Carbon\Carbon::parse($start)->formatLocalized("%d %B %Y").' au '.\Carbon\Carbon::parse($end)->formatLocalized("%d %B %Y");
    }

    /**
     * Put styles inline for campagne
     * Used when sending cammpagne or test
     * */
    public function html()
    {
        if(!$this->url){
            throw new \App\Exceptions\ProblemFetchException('Aucune url donnée');
            die();
        }

        $context = [
            "ssl" => [
                "verify_peer" => false,
                "verify_peer_name" => false,
            ]
        ];

        libxml_use_internal_errors(true);
        $htmldoc = new InlineStyle( file_get_contents( url($this->url), false, stream_context_create($context)));

        $htmldoc->applyStylesheet($htmldoc->extractStylesheets());

        $html = $htmldoc->getHTML();
        $html = preg_replace('#<script(.*?)>(.*?)</script>#is', '', $html);

        return $html;
    }

    public function send()
    {
        $this->mailjet->setList($this->main_list);

        $ID   = $this->mailjet->createCampagne($this->getSubject());
        $html = $this->html();

        $this->mailjet->setHtml($html,$ID);
       // $this->mailjet->sendTest($ID,'cindy.leschaud@gmail.com','Newsletter Droit pour le Praticien | Semaine du 4 test au 15 test 2018');

        $toSend = \Carbon\Carbon::now()->addMinutes(20);

        $this->mailjet->sendCampagne($ID, $toSend->toIso8601String());
    }

    public function send_test()
    {
        $html = $this->html();

        \Mail::send([], [], function ($message) use ($html) {
            $message->to('cindy.leschaud@gmail.com')->subject($this->getSubject())->setBody($html, 'text/html');
        });
    }
}