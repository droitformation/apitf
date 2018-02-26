<?php

/**
 * Menu
 */

function renderSidebar($node){

    //if( $node->children->isEmpty() ) {  echo '<li>'.$node->term->name.'</li>';  }
   // else{
       // $html = '<li><a href="'.url('category/'.$node->term_id).'"><i class="fa fa-caret-right"></i> '.$node->term->name.'</a>';
        $html = '';

        if(!$node->children->isEmpty()){
            $html .= '<ol class="subcategories">';

            foreach($node->children as $child) {

                $html .= '<li><a href="'.url('category/'.$child->slug).'"><i class="fa fa-caret-right"></i>  '.$child->term->name.'</a>';

                if(isset($child->children) && !$child->children->isEmpty()){
                    foreach($child->children as $children) {
                        $html .= '<ol class="subsubcategories">';
                        $html .= '<li><a href="'.url('category/'.$children->slug).'">'.$children->term->name.'</a></li>';
                        $html .= '</ol>';
                    }
                }

                $html .= '</li>';
            }
            $html .= '</ol>';
        }

        $html .= '</li>';

        echo $html;
    //}
}

function prepareTerms($terms){

    $search = htmlspecialchars_decode($terms);

    return collect(explode(',',$search))->groupBy(function ($item, $key) {
        return preg_match('/\"([^\"]*?)\"/', $item, $m) ? 'quotes' : 'noquotes';
    })->map(function ($items, $key) {
        return $items->map(function ($item, $key2) use ($key) {
            $item = trim(str_replace('"', '', $item));// Clean quotes

            if($key == 'quotes'){ return '% '.$item.' %'; }
            return '%'.$item.'%';
        });
    })->flatten(1);

}

/**
 * helpers functions
 */

function dateIsValid($date,$format = 'Y-m-d')
{
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) === $date;
}

function dates_range($nbr){

    return collect(range(1,$nbr))->map(function ($item, $key) {
        return \Carbon\Carbon::today()->subDay($item)->toDateTimeString();
    });
}

function generateDateRange($start_date, $end_date)
{
    $dates = [];

    for($date = $start_date; $date->lte($end_date); $date->addDay()) {
        if($date->isWeekday()){
            $dates[] = $date->toDateTimeString();
        }
    }

    return $dates;
}

function archiveTableForDates($start_date, $end_date){

    $start_date = \Carbon\Carbon::parse($start_date);
    $end_date   = \Carbon\Carbon::parse($end_date);

    $years = [];

    for($date = $start_date; $date->lte($end_date); $date->addYear()) {
        $years[] = $date->year;
    }

    return $years;
}

function formatDateOrRange($date){

    setlocale(LC_ALL, 'fr_FR.UTF-8');

    if(is_array($date)){
        $start = array_shift($date);
        $end   = array_pop($date);

        $start = \Carbon\Carbon::parse($start);
        $end   = \Carbon\Carbon::parse($end);

        $format = $start->month == $end->month ? '%d' : '%d %B';

        return $start->formatLocalized($format).' au '.$end->formatLocalized('%d %B %Y');;
    }

    return \Carbon\Carbon::parse($date)->formatLocalized('%d %B %Y');
}

function extractParams($string){

    preg_match_all('/([^?&=#]+)=([^&#]*)/',$string, $match);

    //combine the keys and values onto an assoc array
    if(isset($match[1]) && isset($match[2])){
        $combine = array_combine( $match[1], $match[2]);
        // Get date variable
        return isset($combine['date']) ? $combine['date'] : null;
    }

    return null;
}

function strip_img_data($string) {
    return str_replace('<img height="68px" src="data:image/gif;base64,iVBORw0KGgoAAAANSUhEUgAACOgAAAcIAQMAAAC2R/5yAAAABlBMVEX///8AAABVwtN+AAAxx0lE QVR4nO3dTZLjuJItYNWrQQ3r7YBb6OGbcVs9aDP60rgULiGHNSjreDcy/kSROHB3OCQQOMfs2s0M kQTwpQiAHlGK241hGIZhGIZhGIZhGIZhGIZhGIZhGIZhGIZhGIZhGIZhGIZhGIZhGIZhGIZhGIZh GIZhGIZhGIZhGIZhGIZhGIZhmHayyKt70HCWN3l1F9rN8kadZKY36iTz9xt1kvnrjTrJ/PFGnXQW 6qQzvVEnmb/eqJPOG3XSmamTzvd9RZ2TLNRJ5+836iTzxxt10pmok86fb9RJZ6FOOn+9USedN+qk M1EnnT/eqJPOTJ10/nrEoc5dFuqk8/cBhzrfOUzJ1LnL42pOnbv8eYJDna8cVnPq/OT0rUOdz5y+ dajzkfO3DnU+crZgUeczZ3sd6nwl8dahzntSbx3qvCf11qHOe1I41LmdPpxT5ztJHOqgtw510FuH OifVZOr8ZKZOOonnT+r8zkQdEIQzvA5Yzqlz8h0+6nwHzsnD68zUSSdZ2KHOLTcnj66zUCcd9IhF nZk66eTm5LF1JuqAZHFG1snOyUPrzNRJJz8nj6yT2yePraO4scbV0dxY4+pobqxxdRbqpJMpCg6u M1EHRIUzqo7iKWJgnZk66ag2O8PqKG+sQXWUN9aYOtoba0wd1VPEsDoLddLRPUWMqqO+sYbUUd9Y I+rob6wRdfQ31og6+htrQB31VnBIHcONNaDOTJ10LDfWeDra4sWYOpYbazwdC85wOqYbazidiTog JpzRdAxPoAPqTNQBWaiTjvHGGkxnog7IQp10rDfWWDqW0s54Ogt10jGVdobTsT2BjqYzUQfEjDOS jnk9H0rHvJ4PpTNTB8SOM5COfT0fSWeiDshCnXTsjxEj6XimnXF0ZuqAeHCG0XE8Rgyk43iMGEhn pk46rvV8GB3Xej6MzkQdkIU66TinnUF0fOv5KDozdUCcOGPo+B4jRtHxTjtj6CzUAfHiDKHjnnaG 0HFPO0PozNQBceOMoOOfdkbQ8U87I+jM1AHx4wygUzDtDKBTMO0MoDNRB2ShTjrekvIYOs7vZA2i M1EHZKFOOkXTTvc6RdNO9zoTdUAW6qRTNu30rlPyCNq/TskjaP86M3VAynA61ymcdjrXKZx2OteZ qQNSiNO3Tum007dO6bTTt85MHZCFOukUPoJ2rlM8KXetUzwpd60zUwdkoU465ZNyzzpl347oXad8 Uu5ZZ6YOSDlOxzoBk3LHOgGTcsc6E3VAZuqABOD0q1P+gN6zTsSk3K/ORB2QmTogETjd6oRMyt3q hEzK3epM1AFZqAMSgtOrTsyk3KtOQNW0Y52JOiALddKJqJr2qxM0KXeqEzQpd6ozUwdkoU46UZNy nzoxD+i96kRNyn3qzNQBWagDEoXTpU7UTrlPnbAlq0udiTogM3VAFuqkE/Yc0aVO3JLVo07Yc0SX OhN1QBbqgMThdKgTuGR1qBP3HNGjTuCS1aHOTB2QhToggTj96QQ+R3SoE7lk9aczUQdkpg5IJE53 OpHPEf3phC5Z3elEPkf0pzNRB2ShDkgoTm86sUtWbzqhzxHd6cQuWb3pzNQBWaiTTvCk3JlO7HNE bzrBS1ZnOhN1QGbqgATj9KUTvWT1pRO9ZPWlE71k9aUzUQdkpg7IQh2QaJyudMIX9K50wpesrnSC C4Od6UzUAZmpA7JQJ534JasnnfBn0K504hf0nnTiF/SedCbqgCzUAYnH6UinwoLekU6FBb0jnQpL Vkc6E3VAZuqALNQBqYDTj06NJasfnQrPoB3p1FjQ+9GZqAMyUwekBk43OjWeQfvRqbKgd6NTZUHv RqfKgt6NzkwdkIU6IFVwetGps6D3olNnQe9Fp86S1YvORB2QmTogC3VA6uB0olNpyepEp84zaC86 lRb0TnQm6oDM1AFZqANSCacPnUpP6J3o1Nru9KFTa7vTh06t7U4fOjN1QBbqgNTC6UKn2oLehU61 Bb0LnWoLehc61Rb0LnQm6oDM1AFZqANSDacHnXrbnR506m13etCpt93pQafedqcHnYk6IAt1QOrh dKBTcUHvQKfigk6dznUqbnc60Km43elAZ6IOyEwdkIU6IBVxrq9TczN4fZ2a253r69Tc7lxfp+Z2 5/o6E3VAZuqALNQBqYlzeZ2q253L61Td7lxep+p25/I6Vbc7l9eZqAMyUwdkoQ5IVZyr69Td7lxd p+525+o6dbc7benYx/qPuY2FOiDzZXXsG1/qxOpM1AG5ro6p59SpoGN6f0r8GP2ZqQPyDB3TrkHi x+gPdVAW6oA8Q8f03CrxY/SHOihmHOoE65jqZRI/RncchT7qxOqY3qASPkZ/qIPiKBJTJ1hnuaiO o4Q+mo5tvGegGz5lHlsnmX9/n3JVnb9/hqANdVBMOh9vy4k6Her87vf/mk6hDopJ59fvUyyFZakw Sm/m3z0ynUIdFIeO5RSpMEpv/qMz19TZzKdI/CDdWX7riOWUsXSmmjrr71Msz7qmvlTO8va/09cY lLHq/N/r6rzV1/kvW41NqozTl/9slP+uqSNX1vnjQ2eznGPV+X+X1vmrss5/30ylU6kxTl/+o/NP VZ3/HP8/t6vq/Pmh88tyjlXnvQJwXZ0/K+q8P9++6yxX1fn1p7F0atV5/981df6qr/PHu858VZ3t j4o6/15a5+8PHVPp1KrzPjNfV+dWV+fPd53pqjprTZ1/3nXkqjrTh46psGzU+et9vIbSqdQZqSe/ dZZ6Or+uriPvmxHLOWad9ao6c32dv991DOdInZF6Mr/DzPV0tned7ao6y6eOGM4ZTGeqp7O+X30z FZYtXamc38tVbZ1fV9V5+9RZDecYdeZ3HUPpVOqM1JPfOsayu/Fbd5fW+ffzYUsfs84/F9X54wk6 y+/6yEV1/vko8hhOsujc3nVshWWpMlJP/vzSsRSWXTrLZXWMZXeDzvvT7duVdX59Galj1rGV3aXO UB35q77OHx8682V1jGV3g86/H2v57bI629e6bjlpDJ3PnU5NnT8/dKZr6qy3ijofC6Kt7C5VRurJ 9KVjKSy7dPSlU6kyUk8+dWxld4POr4+j18vqyO1mLCwPozPX1/n7Q0d/klQZqSefOnMtne1DZ7us zuf/if4kl46+dGroSeUsHzpTLZ31Yynfrqrze7GqrWMquxt6Ujlv3zqr/iSXjr50KlVG6smnjq3s bvvO3fz2ZissS5WRevL5CGErLI+i88cTdJYPHX1hWWqM1JOvwo6t7G7QuX3omMruUmOkntzpGArL Pp3lcjpfJVNb2V2v81FWNhaWpcpQHflSoc5Z/vrRMRSWfTrzZXVsZXe9zr9fK/ntojrb+//byu4+ nelyOn/X1/nz0jrr7z9U0vnnS0curmMoLPt01KVTqTFST6YvHVPZXa/z6+vg9aI68vsPi6V0OorO /EQd9VlSY6SefOvMdXS2r7fMdlGd7z+I+iyfjrp0qu9I5SxfOlMdnfVrIbcUlvUdqZzvpYo6J7nX WdVn+XTUpVOpMVJPvjeBprK76dua8+8/WArLUmOknnw/QJgKyz4ddelUKgzUkz+oA/KjYyq763Vu XyVBS9ldKozUk5+il6ns7tRZLqbzUzA1FZbVOl+FU1thWWoM1ZEfE+ocs9PRF5adOvPFdH5mG1PZ Xa3z788u53ZJne3jT6bCslNnupjO38/TkSvrmMruap1/fp495ZI66+cfLWV3p462dCoVRurJ9KNj KburdX79HLteUkc+/7gYSqdOHe1pkmv/SaEOyvzTlbmGzvZzO22X1Ln7o2hPc+poS6fqflTO8jwd Q2FZ3Y/KudOZauisP5ucS+p8L+OTobDs1NEWlqXCSD252wJayu6W72rOn380lN2lwkg9uXt8sBSW nTra0qnED9SVJ+oYCssSP1BP7h/MLWV3tc7tpyB4SZ3vkpel7O7VWcCh7encl0sthWWtzn0x2VBY lsJhWT4eCKxFz9C5//N8NZ1fd39WF5bH0Lmfayxld23z/97vcW4X1Nm+/mwpLHt1JuqkdORqOrsd YAWdf+6fPOWCOj+vGcruXh1lYVkKZCzdy+hM968Zyu7a5ndVi5U6B52fQ1f1eVIgY+leXuenJ4u+ sOzS2a6mM9/3ZI7X2e5vpu2COru/aLvl1VGWTrXdKO1eRmd5po6+sKztRmn3LDpTvM56v8XRF5a1 3SjtXlbnbpma9IXlMXR2G0BD2d3wTc0fHX3ZXQpkLN0z6mzBzcv9Y7m+7C5+GFP3sjp3j1a1dfSF ZfHDmLqHdfaP5Yayu7b5232x9II6/+yuqS2dunWW5KHt6eyLpYbCsrL5faFdX3aXEhp99xrQ2f9t vpbOr93ftIVll46+sCwlNPruZXT2M42h7K5s/mEevl1cR1s61evc7/9u2tKpFNkE6mw/f6uuI0/U Mf360/M87P/Cdf7ZP5WLtnQqheMK01nv/qovu/t01mvpTHsdfWFZqfOLOljn/shVe6IUjitM574j i7qw7NPZrqUz7zsyR+tsnelI4shj80qdv/d/1RWWlb1A3YvRefirqJt36KjL7speoO4F6Cx7nSla Z91v/9SFZWUvUPdCdHaL1KQuLPt01IVlKRxXjM7D9k9fdtd/T5M6aJTzXkdXOpXCcUXp7C6iL7v7 dNSFZSkcV4jO40O5vuyu1LkddZYr6yhLp3qdew11YVkKBxalsysGRus8alxL57GQrC+763Ue/z5f SefXw9+VhWWfjrqwLIUDC9F5vJP0ZXedzmGNul1cR3lRtc5+93dTFpalfGAhOtv936vryJV0Dru/ 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 Ub71rFO+nvesswU01q+OBDTWrU7EtNOvTsS006/OFtFYtzoS0VivOiHTTmUd+49AZqcL5SVDpp1u dbaQ/v9h7v97VuXVX6cjRSpf6VQnoHrxnk51AqoX7+lUR9vBTDrVKUP5Tp86QdNOpzpB006nOtr+ 5dKnTpnJT7rUiZp2+tSJmnb61NF2L5sudcpI7tKjTti006VO2LTTpY62d/n0qFMmcp8OdeKmnR51 4qadHnW0nVOkQ50ykF3604n5Xs1H+tPZijz26U9H2zdNquvEfMt2d0mc4PasaVsncLfjSts6gbsd V9rW0XatVtrWkeD2rGlaJ7o5c5rWidwLutK0zhbcnDlN60hwc+Y0rRPcmj0t67x6L9i2zqv3gq/W +RPqaDtWLy3rBDfmSMM6L98LNq3z8r1g0zpbcGOONKwjwY050q5OA9NOwzqv3wu2rPP6vWDLOtpu 1Uy7OsFNudKsTgvTTrs6DewFG9bZgptypVkdCW7KlVZ1WtgLtqvTxLTTrE4Le8F2dbSdqptWdYIb cqZRnSb2gs3qtDHttKqzBTfkTKM6EtyQM23qtLEXbFWnkUm5UZ1GJuVGdbbgdrxpU0eC2/GmSZ1W JuU2dVqZlNvUaWVSblNnC27GnSZ1JLgZd5rUCW7FnxZ1mpmUm9RpZlJuUmcLbsWfFnUkuBV/WtQJ bqQgDeq0Mym3qNPGN/p+p0GdLbiRgjSoI8GNFKRBneA2StKeTkOTcoM6DU3KDepswW2UpD0dCW6j JO3pBDdRlOZ0WpqU29NpaVJuT2cLbqIozeloe/OUNKcT3EJZWtNpalJuTqepSbk5nS24hbK0pqPt zHPSmk5wA4VpTKetSbk1nbYm5dZ0tuAGCtOYjrYvT0pjOsHXL01bOs38wOBn2tJpbFJuTKedn035 SFs62q48K23pSPD1S9OWTvDli9OUTmPPEY3ptLZktaWzBV++OE3paHvytDSlE3z18rSk09pzRFs6 zS1ZTem09hzRls4WfPXytKQjwVcvT0s6wRcPSEM67U3KLek09xzRlE57S1ZLOtp+PDEN6UjwxQPS jk57zxEt6TS4ZDWk0+CS1ZDOFnztiLSjo+3GM9OOjgRfOyLN6LS4ZLWj0+KS1Y5Oi0tWOzpb8KVD 0oyOthdPTTM6EnzpkLSi0+SS1YxOk0tWMzoNlr5u7ehswVeOSSs62k48N63oBF84KI3otLlktaLT 5pLVik6bS1YrOlvwhYPSiI62D09OIzrB141KGzqNLlmN6DS6ZDWi02Rh8NaKzhZ83ai0oaPtwrPT ho4EXzcqbegEXzYsTei0umS1odPqktWGTqPPoI3obMGXDUsTOhJ82bA0oRN81bi0oNPqM2gbOs0u 6E3oNLtkNaGzBV81Li3oaDvw/LSgI8FXjUsDOu0uWS3otLtktaDT7DNoEzrtLugt6Gjbf0Ea0JHg iwamAZ3ga0bm9ToNL1kN6DS8ZDWg0/CS1YDOFnzNyLxeR9v8K/J6HQm+ZmRertPwM2gDOi0v6K/X aXlBf71Oywv663W24EuG5uU62tZfkpfrBF8xNq/WaXpBf7lO0wv6y3WaXtBfrtP0gv5ynS34irF5 tY4EXzE2r9YJvmBwXqzT9oL+ap22F/RX67S9oL9aZwu+YHBerKNt+0V5sY4EXzA4L9YJvl50Xqvz f4KvF53X6rQe6qBQB4U6KNRBoQ4KdVCog0IdFOqgUAeFOijUQaEOCnVQqINCHRTqoFAHhToo1EGh Dgp1UKiDQh0U6qBQB4U6KNRBoQ4KdVCog0IdFOqgUAeFOijUQaEOCnVQqINCHRTqoFAHhToo1EGh Dgp1UKiDQh0U6qBQB4U6KNRBoQ4KdVCog0IdFOqgUAeFOijUQaEOCnVQqINCHRTqoFAHhToo1EGh Dgp1UKiDQh0U6qBQB4U6KNRBoQ4KdVCog0IdFOqgUAeFOijUQaEOCnVQqINCHRTqoFAHhToo1EGh Dgp1UKiDQh0U6qBQB4U6KNRBoQ4KdVCog0IdFOqgUAeFOijUQaEOCnVQqINCHRTqoFAHhToo1EGh Dgp1UKiDQh0U6qBQB4U6KNRBoQ4KdVCog0IdFOqgUAeFOijUQaEOCnVQqINCHRTqoFAHhToo1EGh Dgp1UKiDQh0U6qBQB4U6KNRBoQ4KdVCog0IdFOqgUAeFOijUQaEOCnVQqINCHRTqoFAHhToo1EGh Dgp1UKiDQh0U6qBQB4U6KNRBoQ4KdVCog0IdFOqgUAeFOijUQaEOCnVQqINCHRTqoFAHhToo1EGh Dgp1UKiDQh0U6qBQB4U6KG+mUIc6P6EOCnVQqINCHRTqoFAHhToo1EGhDgp1UKiDQh0U6qBQB4U6 KNRBoQ4KdVCog0IdFOqgUAeFOgzDMAzDMAzDMAzDMAzDMAzDMAzDMAzDMAzDMAzDMAzDMAzDMAzD MAzDMAzDMAzDMAzDMAzDMAzDMAzDMAzDMAxTL/8frIR2ALAGK1kAAAAASUVORK5CYII=" width="95px"></img>','',$string);
}

function cleanWhitespace($string) {
    $string = preg_replace('/\s+/', ' ', $string);
    $string = trim($string);
    return $string;
}

function cleanText($str, $repto = null)
{
    //** Return if string not given or empty.
    if (!is_string ($str)
        || trim ($str) == '')
        return $str;

    //** Recursive empty HTML tags.
    $str  = strip_tags($str,"\n,<b>,<div>");
    $str  = preg_replace('/\s+/', ' ', $str);
   // $str  = strip_img_data($str);
    $str  = str_replace("<div class=\"para\">","<div>",$str);
    $str  = str_replace('\u00a0', '', $str );
    $str  = str_replace("\xc2\xa0","",$str);
   // $str  = str_replace("&nbsp;"," ",$str);
    $str  = preg_replace('/\s{2,}/', ' ',$str);
    $str =  preg_replace('/<([^<\/>]*)>([\s]*?|(?R))<\/\1>/imsU', !is_string ($repto) ? '' : $repto,$str);
    //$str  = str_replace("</div>","\n",$str);
    $str  = str_replace("<div>{T0/2}</div>","",$str);
    $str  = preg_replace("/ +/", " ", $str);
    $str  = preg_replace("/(\r\n){2,}/","\r\n",trim($str));
    $str  = trim($str);

    return $str;
}

function cleanCategorieString($string)
{
    // remove *, "(en" and trim
    $string = str_replace('*', '', $string);
    $string = str_replace('(en ', '(', $string);
    $string = trim($string);

    return $string;
}

function wpautop( $pee, $br = true ) {
    $pre_tags = array();

    if ( trim($pee) === '' )
        return '';

    // Just to make things a little easier, pad the end.
    $pee = $pee . "\n";

    /*
     * Pre tags shouldn't be touched by autop.
     * Replace pre tags with placeholders and bring them back after autop.
     */
    if ( strpos($pee, '<pre') !== false ) {
        $pee_parts = explode( '</pre>', $pee );
        $last_pee = array_pop($pee_parts);
        $pee = '';
        $i = 0;

        foreach ( $pee_parts as $pee_part ) {
            $start = strpos($pee_part, '<pre');

            // Malformed html?
            if ( $start === false ) {
                $pee .= $pee_part;
                continue;
            }

            $name = "<pre wp-pre-tag-$i></pre>";
            $pre_tags[$name] = substr( $pee_part, $start ) . '</pre>';

            $pee .= substr( $pee_part, 0, $start ) . $name;
            $i++;
        }

        $pee .= $last_pee;
    }
    // Change multiple <br>s into two line breaks, which will turn into paragraphs.
    $pee = preg_replace('|<br\s*/?>\s*<br\s*/?>|', "\n\n", $pee);

    $allblocks = '(?:table|thead|tfoot|caption|col|colgroup|tbody|tr|td|th|div|dl|dd|dt|ul|ol|li|pre|form|map|area|blockquote|address|math|style|p|h[1-6]|hr|fieldset|legend|section|article|aside|hgroup|header|footer|nav|figure|figcaption|details|menu|summary)';

    // Add a double line break above block-level opening tags.
    $pee = preg_replace('!(<' . $allblocks . '[\s/>])!', "\n\n$1", $pee);

    // Add a double line break below block-level closing tags.
    $pee = preg_replace('!(</' . $allblocks . '>)!', "$1\n\n", $pee);

    // Standardize newline characters to "\n".
    $pee = str_replace(array("\r\n", "\r"), "\n", $pee);

    // Find newlines in all elements and add placeholders.
    $pee = wp_replace_in_html_tags( $pee, array( "\n" => " <!-- wpnl --> " ) );

    // Collapse line breaks before and after <option> elements so they don't get autop'd.
    if ( strpos( $pee, '<option' ) !== false ) {
        $pee = preg_replace( '|\s*<option|', '<option', $pee );
        $pee = preg_replace( '|</option>\s*|', '</option>', $pee );
    }

    /*
     * Collapse line breaks inside <object> elements, before <param> and <embed> elements
     * so they don't get autop'd.
     */
    if ( strpos( $pee, '</object>' ) !== false ) {
        $pee = preg_replace( '|(<object[^>]*>)\s*|', '$1', $pee );
        $pee = preg_replace( '|\s*</object>|', '</object>', $pee );
        $pee = preg_replace( '%\s*(</?(?:param|embed)[^>]*>)\s*%', '$1', $pee );
    }

    /*
     * Collapse line breaks inside <audio> and <video> elements,
     * before and after <source> and <track> elements.
     */
    if ( strpos( $pee, '<source' ) !== false || strpos( $pee, '<track' ) !== false ) {
        $pee = preg_replace( '%([<\[](?:audio|video)[^>\]]*[>\]])\s*%', '$1', $pee );
        $pee = preg_replace( '%\s*([<\[]/(?:audio|video)[>\]])%', '$1', $pee );
        $pee = preg_replace( '%\s*(<(?:source|track)[^>]*>)\s*%', '$1', $pee );
    }

    // Collapse line breaks before and after <figcaption> elements.
    if ( strpos( $pee, '<figcaption' ) !== false ) {
        $pee = preg_replace( '|\s*(<figcaption[^>]*>)|', '$1', $pee );
        $pee = preg_replace( '|</figcaption>\s*|', '</figcaption>', $pee );
    }

    // Remove more than two contiguous line breaks.
    $pee = preg_replace("/\n\n+/", "\n\n", $pee);

    // Split up the contents into an array of strings, separated by double line breaks.
    $pees = preg_split('/\n\s*\n/', $pee, -1, PREG_SPLIT_NO_EMPTY);

    // Reset $pee prior to rebuilding.
    $pee = '';

    // Rebuild the content as a string, wrapping every bit with a <p>.
    foreach ( $pees as $tinkle ) {
        $pee .= '<p>' . trim($tinkle, "\n") . "</p>\n";
    }

    // Under certain strange conditions it could create a P of entirely whitespace.
    $pee = preg_replace('|<p>\s*</p>|', '', $pee);

    // Add a closing <p> inside <div>, <address>, or <form> tag if missing.
    $pee = preg_replace('!<p>([^<]+)</(div|address|form)>!', "<p>$1</p></$2>", $pee);

    // If an opening or closing block element tag is wrapped in a <p>, unwrap it.
    $pee = preg_replace('!<p>\s*(</?' . $allblocks . '[^>]*>)\s*</p>!', "$1", $pee);

    // In some cases <li> may get wrapped in <p>, fix them.
    $pee = preg_replace("|<p>(<li.+?)</p>|", "$1", $pee);

    // If a <blockquote> is wrapped with a <p>, move it inside the <blockquote>.
    $pee = preg_replace('|<p><blockquote([^>]*)>|i', "<blockquote$1><p>", $pee);
    $pee = str_replace('</blockquote></p>', '</p></blockquote>', $pee);

    // If an opening or closing block element tag is preceded by an opening <p> tag, remove it.
    $pee = preg_replace('!<p>\s*(</?' . $allblocks . '[^>]*>)!', "$1", $pee);

    // If an opening or closing block element tag is followed by a closing <p> tag, remove it.
    $pee = preg_replace('!(</?' . $allblocks . '[^>]*>)\s*</p>!', "$1", $pee);

    // Optionally insert line breaks.
    if ( $br ) {
        // Replace newlines that shouldn't be touched with a placeholder.
        $pee = preg_replace_callback('/<(script|style).*?<\/\\1>/s', '_autop_newline_preservation_helper', $pee);

        // Normalize <br>
        $pee = str_replace( array( '<br>', '<br/>' ), '<br />', $pee );

        // Replace any new line characters that aren't preceded by a <br /> with a <br />.
        $pee = preg_replace('|(?<!<br />)\s*\n|', "<br />\n", $pee);

        // Replace newline placeholders with newlines.
        $pee = str_replace('<WPPreserveNewline />', "\n", $pee);
    }

    // If a <br /> tag is after an opening or closing block tag, remove it.
    $pee = preg_replace('!(</?' . $allblocks . '[^>]*>)\s*<br />!', "$1", $pee);

    // If a <br /> tag is before a subset of opening or closing block tags, remove it.
    $pee = preg_replace('!<br />(\s*</?(?:p|li|div|dl|dd|dt|th|pre|td|ul|ol)[^>]*>)!', '$1', $pee);
    $pee = preg_replace( "|\n</p>$|", '</p>', $pee );

    // Replace placeholder <pre> tags with their original content.
    if ( !empty($pre_tags) )
        $pee = str_replace(array_keys($pre_tags), array_values($pre_tags), $pee);

    // Restore newlines in all elements.
    if ( false !== strpos( $pee, '<!-- wpnl -->' ) ) {
        $pee = str_replace( array( ' <!-- wpnl --> ', '<!-- wpnl -->' ), "\n", $pee );
    }

    return $pee;
}

function wp_replace_in_html_tags( $haystack, $replace_pairs ) {
    // Find all elements.
    $textarr = wp_html_split( $haystack );
    $changed = false;

    // Optimize when searching for one item.
    if ( 1 === count( $replace_pairs ) ) {
        // Extract $needle and $replace.
        foreach ( $replace_pairs as $needle => $replace );

        // Loop through delimiters (elements) only.
        for ( $i = 1, $c = count( $textarr ); $i < $c; $i += 2 ) {
            if ( false !== strpos( $textarr[$i], $needle ) ) {
                $textarr[$i] = str_replace( $needle, $replace, $textarr[$i] );
                $changed = true;
            }
        }
    } else {
        // Extract all $needles.
        $needles = array_keys( $replace_pairs );

        // Loop through delimiters (elements) only.
        for ( $i = 1, $c = count( $textarr ); $i < $c; $i += 2 ) {
            foreach ( $needles as $needle ) {
                if ( false !== strpos( $textarr[$i], $needle ) ) {
                    $textarr[$i] = strtr( $textarr[$i], $replace_pairs );
                    $changed = true;
                    // After one strtr() break out of the foreach loop and look at next element.
                    break;
                }
            }
        }
    }

    if ( $changed ) {
        $haystack = implode( $textarr );
    }

    return $haystack;
}

/**
 * Separate HTML elements and comments from the text.
 *
 * @since 4.2.4
 *
 * @param string $input The text which has to be formatted.
 * @return array The formatted text.
 */
function wp_html_split( $input ) {
    return preg_split( get_html_split_regex(), $input, -1, PREG_SPLIT_DELIM_CAPTURE );
}

/**
 * Retrieve the regular expression for an HTML element.
 *
 * @since 4.4.0
 *
 * @return string The regular expression
 */
function get_html_split_regex() {
    static $regex;

    if ( ! isset( $regex ) ) {
        $comments =
            '!'           // Start of comment, after the <.
            . '(?:'         // Unroll the loop: Consume everything until --> is found.
            .     '-(?!->)' // Dash not followed by end of comment.
            .     '[^\-]*+' // Consume non-dashes.
            . ')*+'         // Loop possessively.
            . '(?:-->)?';   // End of comment. If not found, match all input.

        $cdata =
            '!\[CDATA\['  // Start of comment, after the <.
            . '[^\]]*+'     // Consume non-].
            . '(?:'         // Unroll the loop: Consume everything until ]]> is found.
            .     '](?!]>)' // One ] not followed by end of comment.
            .     '[^\]]*+' // Consume non-].
            . ')*+'         // Loop possessively.
            . '(?:]]>)?';   // End of comment. If not found, match all input.

        $escaped =
            '(?='           // Is the element escaped?
            .    '!--'
            . '|'
            .    '!\[CDATA\['
            . ')'
            . '(?(?=!-)'      // If yes, which type?
            .     $comments
            . '|'
            .     $cdata
            . ')';

        $regex =
            '/('              // Capture the entire match.
            .     '<'           // Find start of element.
            .     '(?'          // Conditional expression follows.
            .         $escaped  // Find end of escaped element.
            .     '|'           // ... else ...
            .         '[^>]*>?' // Find end of normal element.
            .     ')'
            . ')/';
    }

    return $regex;
}


/**
 * Newline preservation help function for wpautop
 *
 * @since 3.1.0
 * @access private
 *
 * @param array $matches preg_replace_callback matches array
 * @return string
 */
function _autop_newline_preservation_helper( $matches ) {
    return str_replace( "\n", "<WPPreserveNewline />", $matches[0] );
}