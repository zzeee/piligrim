<?php

/**
 * Created by PhpStorm.
 * User: Zienko
 * Date: 12.12.2016
 * Time: 12:09
 */
class turspace
{
    var $res="";

    function __construct($tid)
    {
        $sql="select dt.date, day(dt.date) as day, month(dt.date) as month, dt.tourid , dt.id, dt.comment, dt.realmaxlimit, rr.num from dates dt left join reserved rr on  dt.id=rr.turdateid where tourid=$tid";
        $rt=db::query2($sql);
        echo($rt);
        if ($rt) $this->res=$rt->fetchAll();
    }
    function getData()
    {
        return $this->res;
    }



    function showDatesLines()
    {
        $rline = "";
        foreach ($this->res as $line) {
            $cm = $line['comment'];
            $rline = $rline . $line['day'] . "." . $line['month'] . ($cm != "" ? " - " . $cm : "") . "; ";
        }
    return $rline;
    }




}