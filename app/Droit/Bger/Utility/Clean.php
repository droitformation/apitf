<?php

namespace App\Droit\Bger\Utility;

/* ================================================
   Clean, remove blanks from an array or string
==================================================*/

class Clean
{
    public function removeWhitespace($string) {

        $string = preg_replace('/\s+/', ' ', $string);
        $string = trim($string);
        return $string;
    }

    public function convertDate($date, $from, $to)
    {
        return \Carbon\Carbon::createFromFormat($from, $date)->format($to);
    }

    // clean almost identical category string
    public function cleanString($string)
    {
        // remove *, "(en" and trim
        $string = str_replace('*', '', $string);
        $string = str_replace('(en ', '(', $string);
        $string = trim($string);

        return $string;
    }

    public function cleanText($str, $repto = null)
    {
        //** Return if string not given or empty.
        if (!is_string ($str)
            || trim ($str) == '')
            return $str;

        //** Recursive empty HTML tags.
        $str  = strip_tags($str,"\n,<b>,<div>");

        $str  = preg_replace('/\s+/', ' ', $str);
        //$str  = str_replace("<div>","",$str);
        $str  = str_replace("<div class=\"para\">","<div>",$str);
        $str  = str_replace('\u00a0', '', $str );
        $str  = str_replace("\xc2\xa0","",$str);
        $str  = preg_replace('/\s{2,}/', ' ',$str);
        $str =  preg_replace('/<([^<\/>]*)>([\s]*?|(?R))<\/\1>/imsU', !is_string ($repto) ? '' : $repto,$str);
        //$str  = str_replace("</div>","\n",$str);
        $str  = str_replace("<div>{T0/2}</div>","",$str);
        $str  = preg_replace("/ +/", " ", $str);
        $str  = preg_replace("/(\r\n){2,}/","\r\n",trim($str));
        $str  = trim($str);

        return $str;
    }

}