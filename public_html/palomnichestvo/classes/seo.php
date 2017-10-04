<?php

/**
 * Created by PhpStorm.
 * User: Zienko
 * Date: 28.12.2016
 * Time: 15:07
 */
class seo
{
    var $texts="";

    function __construct($siteid)
    {
        $sq="select * from seo where siteid=$siteid";
        $rt=db::query2($sq);
       // echo($sq);
        if ($rt) {
            $rm = $rt->fetchAll();
            $this->texts = $rm;
        }
    }

    
    function getTexts($url)
    {
        //$rt=array_keys("")
///            $key = array_search($url, array_column($this->text 'id'));

$i=0;
//echo($url);

foreach($this->texts as $txt)
{
//    echo($txt["url"]);
    if ($txt["url"]==$url) break;
    $i++;
}
 if (isset($this->texts[$i])) return $this->texts[$i];
else return false;

    }

}