<?php

/**
 * Created by PhpStorm.
 * User: леново
 * Date: 12.12.2016
 * Time: 21:29
 */
class price
{
    var $uid=0;
    function __construct ($uid)
    {
        $this->uid=$uid;

    }

    function getTourPrice($id, $date)
    {
        //$sq='select baseprice from tours where id='.$id;

        if ($date=="")
            $sq="select ifnull(dt.pricefull,tours.baseprice) as baseprice, blength from tours join (select min(date) as md,any_value(pricefull) as pricefull, tourid  from dates where date>now()  group by tourid) as dt on dt.tourid=tours.id where tours.id=$id";
        else
            $sq="select ifnull(dt.pricefull,tours.baseprice) as baseprice,blength  from tours
  left join dates dt on tours.id=dt.tourid where dt.id=$date";

        $rm=db::query2($sq);
       // echo($sq);
        $rt="";
        $price=0;
        if ($rm)  { $rt=$rm->fetchAll();

        if (count($rt)>0) $price=$rt[0]["baseprice"];
        //echo($price);
        }
        return $price;
    }

    function dday($ldata)
    {
        switch ($ldata):
            case 1: return "день";
            case 2: return "дня";
            case 3: return "дня";
            case 4: return "дня";
            default:
                return "дней";
        endswitch;

    }

    function dnight($ldata)
    {
        switch ($ldata):
            case 1: return "ночь";
            case 2: return "ночи";
            case 3: return "ночи";
            case 4: return "ночи";
            default:
                return "ночей";
        endswitch;

    }




    function showTextMonth($id)
    {
        switch ($id):
            case 1: return "января";
            case 2: return "февраля";
            case 3: return "марта";
            case 4: return "апреля";
            case 5: return "мая";
            case 6: return "июня";
            case 7: return "июля";
            case 8: return "августа";
            case 9: return "сентября";
            case 10: return "октября";
            case 11: return "ноября";
            case 12: return "декабря";


        endswitch;
        return "";
    }


    function getTourLenPrice($id,$date)
    {
        if ($date=="")
        $sq="select ifnull(dt.pricefull,tours.baseprice) as baseprice, blength from tours join (select min(date) as md,any_value(pricefull) as pricefull, tourid  from dates where date>now()  group by tourid) as dt on dt.tourid=tours.id where tours.id=$id";
        else
           $sq="select ifnull(dt.pricefull,tours.baseprice) as baseprice,blength  from tours
  left join dates dt on tours.id=dt.tourid where dt.id=$date";

        $rm=db::query2($sq);
        // echo($sq);
        $rt="";
        $lin="";
        if ($rm)  { $rt=$rm->fetchAll();
        if (count($rt)>0){
        $price=$rt[0]["baseprice"];
            $blength=$rt[0]["blength"];
            if (intVal($price)>0)
            $lin=$blength." ".$this->dday($blength)." за ".$price." руб.";
        }
           // echo($lin);
        }
        return $lin;
    }



}