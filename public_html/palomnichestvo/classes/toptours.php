<?php

/**
 * Created by PhpStorm.
 * User: Zienko
 * Date: 20.12.2016
 * Time: 12:27
 */
class toptours
{
    private $list = "";
    private $defaultid;

    function __construct()
    {/*
        $sq = 'SELECT tours.id,title, main_descr, baseprice, blength, mainfoto, ct.name, ct.surl FROM tours JOIN categories ct ON tours.type=ct.id WHERE tours.id IN (SELECT tourid FROM toptours WHERE typ=1) ';
        $rm = db::query2($sq);
        if ($rm)
            $this->list = $rm->fetchAll();*/
    }

    function getList($typ)
    {
        $sq = "SELECT tours.id, day(max(dt.date)) as ddate, month(max(dt.date)) as mdate, dt.id as did, ct.stext as typname, title, main_descr, baseprice, 
if (blength=1,concat (blength,' день'),  if ((blength>1 and blength<=4), concat (blength,' дня'), concat (blength,' дней'))) as breslength, if (nights=0,
if (blength-1=1,'1 ночь',if (  (blength-1>1 and blength-1<=4), concat (blength-1,' ночи'), concat (blength-1,' ночей')   )      ),  
if(nights=1, '1 ночь',if (nights>1 and nights<5,concat (nights, 'ночи'),concat (nights, 'ночей')) )
) as bresnight,
blength, cr.name as countryname, cr.flagurl,mainfoto, ct.name, ct.surl 
FROM tours JOIN 
categories ct ON tours.type=ct.id JOIN
toptours tt ON tt.tourid=tours.id JOIN
countries cr ON cr.id=tours.country 
join dates dt on dt.tourid=tours.id
WHERE tt.typ=$typ ";

        $sq="select tours.id, day(dates.date) as ddate,month(dates.date) as mdate, dates.id as did, dates.tourid as tourid, dates.date as date,  dates.id as did, tours.type as typ,
  ct.stext as typname, tours.title, tours.main_descr, tours.baseprice,
  tours.blength, cr.name as countryname, cr.flagurl,mainfoto, ct.name, ct.surl,
  if (blength=1,concat (blength,' день'),  if ((blength>1 and blength<=4), concat (blength,' дня'), concat (blength,' дней'))) as breslength, if (nights=0,
                                                                                                                                                  if (blength-1=1,'1 ночь',if (  (blength-1>1 and blength-1<=4), concat (blength-1,' ночи'), concat (blength-1,' ночей')   )      ),
                                                                                                                                                  if(nights=1, '1 ночь',if (nights>1 and nights<5,concat (nights, 'ночи'),concat (nights, 'ночей')) )
) as bresnight
from dates
  join tours on dates.tourid=tours.id
join categories ct ON tours.type=ct.id
JOIN countries cr ON cr.id=tours.country
where tours.visible=1 and tours.type=$typ order by date limit 3


";
$sq="select tours.id, day(dates.date) as ddate,month(dates.date) as mdate, dates.id as did, dates.tourid as tourid, dates.date as date,  dates.id as did, tours.type as typ,
  ct.stext as typname, tours.title, tours.description as main_descr, tours.baseprice,
  tours.blength, cr.name as countryname, cr.flagurl,photos.name as mainfoto, ct.name, ct.surl,
  if (blength=1,concat (blength,' день'),  if ((blength>1 and blength<=4), concat (blength,' дня'), concat (blength,' дней'))) as breslength, if (nights=0,
                                                                                                                                                  if (blength-1=1,'1 ночь',if (  (blength-1>1 and blength-1<=4), concat (blength-1,' ночи'), concat (blength-1,' ночей')   )      ),
                                                                                                                                                  if(nights=1, '1 ночь',if (nights>1 and nights<5,concat (nights, 'ночи'),concat (nights, 'ночей')) )
) as bresnight
from dates
  join tours on dates.tourid=tours.id
join categories ct ON tours.type=ct.id
JOIN countries cr ON cr.id=tours.country
  left join (select count(id) as mi,any_value(id) as id,tid as tid, any_value(name) as name from photos where tid>0   group by tid) photos on tours.id=photos.tid
where tours.visible=1 and tours.type=$typ and dates.`date`>now()  order by date limit 3
";
//echo($sq);

        $rt = db::query2($sq);
        if ($rt) {

            $res = $rt->fetchAll();
            if (count($res) == 0) $res = $this->list;
        } else $res = $this->list;
        //var_dump($res);
        return $res;

    }


}