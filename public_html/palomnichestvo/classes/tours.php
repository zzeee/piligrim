<?php

/**
 * Created by PhpStorm.
 * User: zzeee
 * Date: 04.12.2016
 * Time: 12:16
 */
class tours
{
    var $tours = "";
    var $categories = "";
    var $tourdates = "";
    var $published_tours;

    function __construct()
    {
        $sq = 'SELECT tour_dates(tours.id, month(now())) AS cm,tour_dates(tours.id, month(date_add(now(), INTERVAL 1 MONTH))) AS cm2,  tour_dates(tours.id, month(date_add(now(), INTERVAL 2 MONTH))) AS cm3, tours.id,title, tours.type, confdesc, description, program, main_descr, baseprice, blength, mainfoto, ct.name, ct.surl FROM tours JOIN categories ct ON tours.type=ct.id WHERE tours.visible=1';


        $sq = 'SELECT tour_dates(tours.id, month(now())) AS cm,tour_dates(tours.id, month(date_add(now(), INTERVAL 1 MONTH))) AS cm2,  tour_dates(tours.id, month(date_add(now(), INTERVAL 2 MONTH))) AS cm3, cr.flagurl, cr.name as countryname,tours.id,title, tours.type, confdesc, description, program, main_descr, baseprice, blength, mainfoto, ct.name as typname, ct.surl,
 if (blength=1,concat (blength,\' день\'), if ((blength>1 and blength<=4), concat (blength,\' дня\'), concat (blength,\' дней\'))) as breslength, if (nights=0 or nights is null, if (blength-1=1,\'/1 ночь\',if ( (blength-1>1 and blength-1<=4), concat (\'/\',blength-1,\' ночи\'), if (blength<>1,concat (\'/\',blength-1,\' ночей\'),"") ) ), if(nights=1, \'/1 ночь\',if (nights>1 and nights<5,concat (\'/\',nights, \'ночи\'),concat (\'/\',nights, \'ночей\')) ) ) as bresnight
FROM tours 
JOIN categories ct ON tours.type=ct.id 
join countries cr on cr.id=tours.country
WHERE tours.visible=1 and tours.type in (1,2,6) order by tours.id desc limit 130';

        $sq = "SELECT tour_dates(tours.id, month(now())) AS cm,tour_dates(tours.id, month(date_add(now(), INTERVAL 1 MONTH))) AS cm2,  tour_dates(tours.id, month(date_add(now(), INTERVAL 2 MONTH))) AS cm3, cr.flagurl, cr.name as countryname,tours.id,title, tours.type, 
confdesc, description, program, main_descr, baseprice, blength, ifnull(photos.name, mainfoto) as mainfoto, ct.name as typname, ct.surl,
 if (blength=1,concat (blength,' день'), if ((blength>1 and blength<=4), concat (blength,' дня'), concat (blength,' дней'))) as breslength, if (nights=0 or nights is null, if (blength-1=1,'/1 ночь',if ( (blength-1>1 and blength-1<=4), concat ('/',blength-1,' ночи'), if (blength<>1,concat ('/',blength-1,' ночей'),\"\") ) ), if(nights=1, '/1 ночь',if (nights>1 and nights<5,concat ('/',nights, 'ночи'),concat ('/',nights, 'ночей')) ) ) as bresnight
FROM tours 
JOIN categories ct ON tours.type=ct.id 
join countries cr on cr.id=tours.country
left join ( SELECT
           min(sorder),
           any_value(id)   AS id,
           tid,
           any_value(name) AS name
         FROM photos
         WHERE tid > 0
         GROUP BY tid) as photos on photos.tid=tours.id
WHERE tours.visible=1 and tours.type in (1,2,6) order by tours.id desc limit 130";
        $rt = db::query2($sq);
        //var_dump($rt);
        //echo($sq);
        $res = [];
        $dat = [];
        if ($rt) $res = $rt->fetchAll();/*foreach ($rt as $rm) {
            array_push($res, $rm);
        }*/
//        var_dump($res);
        $this->tours = $res;
        $sq = 'SELECT DISTINCT (id), type   FROM tours WHERE visible=1';
        $yth = db::query2($sq);
        if ($yth) $this->published_tours = $yth->fetchAll();
        $sq = 'SELECT * FROM categories WHERE visible=1';
        $rt = db::query2($sq);
        if ($rt) $this->categories = $rt->fetchAll();
        $sq = 'SELECT dt.date, month(dt.date) AS month, dt.tourid, dt.comment, dt.realmaxlimit, dt.showplaces, dt.limitperagency, tr.type, tr.title, ct.name, ct.surl FROM dates dt JOIN tours tr ON dt.tourid=tr.id JOIN categories ct ON tr.type=ct.id';
        $rtd = db::query2($sq);
        if ($rtd) $this->tourdates = $rtd->fetchAll();
    }

    function getCategories()
    {
        return $this->categories;
    }

    function getDates($tid) //возвращает список дат ТУРА (проверить)
    {
        //$rt=array_keys($this->tourdates, $search_value=);
        $res = [];
        foreach ($this->tourdates as $line) {
            if ($line["tid"] == $tid) array_push($res, $line);
        }
        return $res;
    }

    function getCategoryByUrl($str)
    {
        $key = array_search($str, array_column($this->categories, 'surl'));
        $res = $this->categories[$key];
        if ($key === false) return false;
        if (isset ($res["id"])) return $res["id"];
        return false;
    }

    function getCategoryNameById($id)
    {
        $key = array_search($id, array_column($this->categories, 'id'));
        $res = $this->categories[$key];

        //var_dump($res);
        if ($res && isset ($res["name"])) return $res["name"];
        return $res;

    }

    function showOne($id)
    {

        return self::showOneCorrected($id, 1);
    }

    function getTourPhoto($tourid)
    {
        $sql = "select id, name, thumbname as thumb,sorder, comment, galname as gallery from photos where tid=$tourid";
        $qres = db::query2($sql);
        $res = "";
        if ($qres) {
            $res = $qres->fetchAll();
        }
        return $res;
    }

    function showOneCorrected($id, $type)//должен возвращать ВСЮ информацию о туре. Не только описания, но и даты, число доступных мест итп
    {
        /* $sq = 'SELECT tours.id, tours.country, tours.type, title, description, confdesc, program, left(main_descr,50) as main_descr, baseprice, blength, mainfoto, ct.name, ct.surl FROM tours JOIN categories ct ON tours.type=ct.id join countries cr on tours.country=cr.id WHERE tours.visible=1 AND tours.id=' . $id;
         */
        $sq = "SELECT tours.id, tours.video_tmp, tours.include, tours.country, tours.type, title, description, confdesc, program, left(main_descr,50) as main_descr, baseprice, blength, photos.name as mainfoto, photos.thumb as thumb, photos.gallery as gallery, ct.name, ct.surl FROM tours JOIN categories ct ON tours.type=ct.id join countries cr on tours.country=cr.id
left join (select count(id) as mi,any_value(id) as id,tid as tid, any_value(name) as name, any_value(thumbname) as thumb, any_value(galname) as gallery from photos where tid>0   group by tid) photos on tours.id=photos.tid
WHERE tours.id=$id
";
        $rt = db::query2($sq);
        $res = [];
        $dat = [];
        $typarr = [];
        $typarr["1"] = "Места";
        $typarr["2"] = "Монастыри";
        $typarr["3"] = "Храмы, соборы, часовни";

        $typarr["6"] = "Святые источники";
        $typarr["7"] = "Святые мощи";
        $typarr["8"] = "Особо почитаемые иконы";
        $typarr["9"] = "Особо почитаемые места и предметы";
        $typarr["10"] = "Исторически значимые места";

        $res["id"] = $id;


        $rm = "";
        //foreach ($rt as $rm) {        }
        if ($rt) {
            $rm = $rt->fetch(PDO::FETCH_ASSOC);
            if ($type == 2) {//В коротком варианте мы ОБНУЛЯЕМ ВСЮ ЛИШНЮЮ ИНФУ ВМЕСТО ИЗМЕНЕНИЯ ЗАПРОСА
                $rm["description"] = "";
                $rm["program"] = "";
            }

            $res["turdata"] = $rm;
            $dat = new turspace($id);
            $res["freespaces"] = $dat->getData();
            //$res["dateline"] = $dat->showDatesLines();
            //echo(json_encode($res["dateline"]));

            if ($type != 2) {//2 - вариант КОРОТКОГО вывода информации о туре. НЕ ПЕРЕДАЕМ ЛИШНЕЕ(!)
                for ($i = 1; $i <= 10; $i++)//ДА! HARDCODE должно быь меньше числа вариантов объектов
                {
                    if ($i == 4) $i = 6;
                    $qres = db::query2(self::getCQuery($id, $i));
                    if ($qres) {
                        $rm = $qres->fetchAll();
                        $res[$i] = $rm;
                        $res[$i]["title"] = $typarr[$i];
                        $res[$i]["id"] = $i;
                        if ($i == 1) $res[$i]["prefix"] = "points"; else $res[$i]["prefix"] = "sp";
                    }

                }
            }

            $sq = "select * from add_services where (tourid=0 or tourid=$id) and visible=1";

            $qres = db::query2($sq);
            if ($qres) {
                $rmp = $qres->fetchAll();
                $res["services"] = $rmp;
            }


            $sq = "select * from dates where tourid=$id and date>now() and date<date_add(curdate(), interval 1 month))";

            $qres = db::query2($sq);
            if ($qres) {
                $rmp2 = $qres->fetchAll();
                $res["dates"] = $rmp2;
            }

            /*


        $qres = db::query2(self::getCQuery($id,5));
        if ($qres ) {
            $rm5=$qres->fetchAll();
            $res["places5"] = $rm5;}
*/
            $sql = "select id, name,thumbname as thumb, galname as gallery, sorder, comment, gal from photos where tid=$id";
            $qres = db::query2($sql);
            if ($qres) {
                $rmp = $qres->fetchAll();
                $res["photos"] = $rmp;
            }

            $sq = "select * from dates where tourid=$id";
            $res2 = db::query2($sq);
            $res["dates"] = $res2->fetchAll();

            $res["instructions"] = "Инструкция для паломника.";


            $res["otzivi"] = "Отзывы.";


        }
        //var_dump($res);


        return $res;
    }


    function addNewTour($title, $type)
    {
        $sq = "insert into tours(title,type, visible)values('$title',$type,0)";
        db::query2($sq);
        return db::lastInsertId();
    }

    function getCQuery($id, $type)
    {
        $sql = "
SELECT places.id, places.name, places.tname, places.elitsy_url,type, photodescr, lat, lon, country,  (ifnull(rt.mi,0)>0 or if (length(descr)>100,1,0)) as isdescr, rt.name as pname, descr FROM places
left join (select count(id) as mi,any_value(id) as id,pid as pid, any_value(name) as name from photos where pid>0
  group by pid) as rt on rt.pid=places.id
WHERE places.id IN (SELECT placeid FROM tours_places WHERE tourid=$id)AND type=$type and ifnull(places.visible,0)=1";
        return $sql;

    }

    function exportOne($id)
    {
        $sq = "SELECT  ifnull(photos.gallery,'') as mainfoto, tours.startcity, tours.country, tours.organizator, tours.id,
  tours.title, tours.description, tours.blength, tours.baseprice, tours.include, tours.exclude,
  tours.main_descr, tours.nights, tours.program, tours.type, tours.price1, tours.sync_date
FROM tours
  left JOIN (
         SELECT
           min(sorder),
           any_value(id)   AS id,
           any_value(thumbname) as thumb,
           any_value(galname) as gallery,           
           tid,
           
           any_value(name) AS name
         FROM photos
         WHERE tid > 0
         GROUP BY tid)
    AS photos
    ON photos.tid = tours.id

WHERE tours.id=$id;
";
        $res = db::query2($sq);
        $rt = [];
        $rt["id"] = $id;
        $rt["tourdata"] = $res->fetch(PDO::FETCH_ASSOC);
        $sq = "select * from add_services where (tourid=0 or tourid=$id) and visible=1";
        $qres = db::query2($sq);
        if ($qres) {
            $rmp = $qres->fetchAll();
            $rt["services"] = $rmp;
        }
        $sq = "select * from dates where tourid=$id and date>now()";

        $qres = db::query2($sq);
        if ($qres) {
            $rmp2 = $qres->fetchAll();
            $rt["dates"] = $rmp2;
        }

        return $rt;
    }


    function getList($typ, $limit=9)//Toptours
    {
        $sq = "SELECT tours.id, day(max(dt.date)) as ddate, month(max(dt.date)) as mdate, dt.id as did, ct.stext as typname, title, main_descr, baseprice, 
if (blength=1,concat (blength,' день'),  if ((blength>1 and blength<=4), concat (blength,' дня'), concat (blength,' дней'))) as breslength, if (nights=0,
if (blength=1,'',
if (blength=2,'1 ночь',if (  (blength>2 and blength<=5), concat (blength-1,' ночи'), concat (blength-1,' ночей')   )      ),  
if(nights=1, '1 ночь',if (nights>1 and nights<5,concat (nights, 'ночи'),concat (nights, 'ночей')) )
)) as bresnight,
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
  ct.stext as typname, tours.title, tours.description as main_descr, tours.startcity,
  ifnull(dates.pricefull,tours.baseprice) as baseprice,
  
  tours.blength, cr.name as countryname, cr.flagurl,photos.name as mainfoto, photos.thumb, photos.gallery, ct.name, ct.surl,
  if (blength=1,concat (blength,' день'),  if ((blength>1 and blength<=4), concat (blength,' дня'), concat (blength,' дней'))) as breslength, if (nights=0,if (blength-1=1,'1 ночь',if (  (blength-1>1 and blength-1<=4), concat (blength-1,' ночи'), concat (blength-1,' ночей')   )      ),if(nights=1, '1 ночь',if (nights>1 and nights<5,concat (nights, 'ночи'),concat (nights, 'ночей')) )
) as bresnight
from dates
  join tours on dates.tourid=tours.id
join categories ct ON tours.type=ct.id
JOIN countries cr ON cr.id=tours.country
  left join (select count(id) as mi,any_value(id) as id,tid as tid, any_value(name) as name, any_value(thumbname) as thumb, any_value(galname) as gallery from photos where tid>0   group by tid) photos on tours.id=photos.tid
where tours.visible=1 and tours.type=$typ and dates.`date`>now()  order by date limit 3
";

        $sq="SELECT
  tours.id,
  day(dates.date)                                                                           AS ddate,
  month(dates.date)                                                                         AS mdate,
  dates.id                                                                                  AS did,
  dates.tourid                                                                              AS tourid,
  dates.date                                                                                AS date,
  dates.id                                                                                  AS did,
  tours.type                                                                                AS typ,
  ct.stext                                                                                  AS typname,
  tours.title,
  tours.description                                                                         AS main_descr,
  tours.startcity,
  ifnull(dates.pricefull, tours.baseprice)                                                  AS baseprice,

  tours.blength,
  cr.name                                                                                   AS countryname,
  cr.flagurl,
  photos.name                                                                               AS mainfoto,
  photos.thumb,
  photos.gallery,
  ct.name,
  ct.surl,
  if(blength = 1, concat(blength, ' день'),
     if((blength > 1 AND blength <= 4), concat(blength, ' дня'), concat(blength, ' дней'))) AS breslength,
  if(nights = 0,
     if(blength - 1 = 1,
        '1 ночь',
          if((blength - 1 > 1 AND blength - 1 <= 4), concat(blength - 1, ' ночи'),
                                                  if ((blength>1),concat(blength - 1, ' ночей'),\"\"))
     ),
     if(nights = 1, '1 ночь', if(nights > 1 AND nights < 5, concat(nights, 'ночи'), concat(nights, '+ночей')))
  )                                                                                         AS bresnight
FROM dates
  JOIN tours ON dates.tourid = tours.id
  JOIN categories ct ON tours.type = ct.id
  JOIN countries cr ON cr.id = tours.country
  LEFT JOIN (SELECT
               count(id)            AS mi,
               any_value(id)        AS id,
               tid                  AS tid,
               any_value(name)      AS name,
               any_value(thumbname) AS thumb,
               any_value(galname)   AS gallery
             FROM photos
             WHERE tid > 0
             GROUP BY tid) photos ON tours.id = photos.tid
WHERE tours.visible = 1 AND tours.type = $typ AND dates.actual=1 and dates.`date` > now()
ORDER BY date
LIMIT $limit;";
//echo($sq);

        $rt = db::query2($sq);
        if ($rt) {

            $res = $rt->fetchAll();
            if (count($res) == 0) $res = $this->list;
        } else $res = $this->list;
        //var_dump($res);
        return $res;

    }


    function exportList($search = 0)
    {
                $res = [];
        $sq = "SELECT ifnull(photos.name,'') as mainfoto, tours.startcity, tours.country, tours.organizator, tours.id,
  tours.title, tours.description, tours.blength, tours.baseprice, tours.include, tours.exclude,
  tours.main_descr, tours.nights, tours.visible, tours.program, tours.type, tours.price1, tours.sync_date
FROM tours
  left JOIN (
         SELECT
           min(sorder),
           any_value(id)   AS id,
           tid,
           any_value(name) AS name
         FROM photos
         WHERE tid > 0
         GROUP BY tid)
    AS photos
    ON photos.tid = tours.id
order by tours.id desc
";
        $rt = "";
        $res = db::query2($sq);
        if ($res) $rt = $res->fetchAll();

        return $rt;
    }

    function exportDates()
    {
        $sq = "SELECT dates.tourid, dates.date, dates.id, dates.comment, dates.realmaxlimit, ur.cn
FROM dates
left join
  (select count(*) as cn, turdate from u_reserves where deleted=0 group by turdate  ) ur on ur.turdate=dates.id
";
        $rt = "";
        $res = db::query2($sq);
        if ($res) $rt = $res->fetchAll();
        return $rt;
    }

    function getLocations($site)
    {
        $sq = "select  * from view_main where siteid=$site";
        $dat = db::query2($sq);
        $res = [];
        if (isset($dat) && $dat) $res = $dat->fetchAll();
        return $res;
    }

    function getTourLocations($id)
    {
        $sq = "select id, tourid, placeid, sorder from tours_places where tourid=$id";
        $dat = db::query2($sq);
        $rs = false;
        if ($dat) $rs = $dat->fetchAll();
        return $rs;
    }

    function updateTourLocations($tourid, $plc)
    {
        if (isset($plc)) {
            $sq = "delete from tours_places where tourid=" . $tourid;
            db::query2($sq);
            for ($i = 0; $i < count($plc); $i++) {
                $sq = "insert into tours_places (tourid,placeid)values(" . $tourid . "," . $plc[$i] . ")";
                db::query2($sq);
            }
        }
    }

    function addTourLocations($tourid, $placeid)
    {
        if (intVal($tourid) * intVal($placeid) == 0) return false;
        $sq = "insert into tours_places (tourid,placeid)values($tourid,$placeid)";
        $resid = false;
        $gt = db::query2($sq);
        if ($gt) $resid = intVal(db::lastInsertId());
        return $resid;
    }

    function deleteTourLocations($tourid, $placeid)
    {
        if (intVal($tourid) * intVal($placeid) == 0) return false;

        $sq = "delete from tours_places where tourid=$tourid and placeid=$placeid";
        return db::query2($sq);
    }

    function depublishTour($id)
    {
        $sq = "update tours set visible=0 where id=$id";
        return db::query2($sq);

    }

    function publishTour($id)
    {
        $sq = "update tours set visible=1 where id=$id";
        return db::query2($sq);
    }

    function deleteTour($id)
    {

        $sq = "delete from tours where id=$id";
        // var_dump($sq);
        return db::query2($sq);
    }


    function showList($type = 0)
    {
        $res = [];
        if ($type != 0) {
            foreach ($this->tours as $line) {
                if ($line["type"] == $type) array_push($res, $line);
            }
            //  var_dump($res);
            $totres["tours"] = $res;
            $totres["dates"] = $this->getDatesCategory($type);
            //var_dump($totres["dates"]);
            return $totres;
        }
        return $this->tours;
    }

    function getDatesCategory($cid)
    {
        $res = [];
        foreach ($this->tourdates as $line) {
            if ($line["type"] == $cid) array_push($res, $line);
        }
        //в массиве $res содержатся все даты туров нужной категории
        $qt = array_keys($res, $search_value = "month");
    }

    function showLocList($loc, $site)
    {

        /*$sq = "SELECT tours.id, day(max(dt.date)) as ddate, month(max(dt.date)) as mdate, dt.id as did, typ.name as typname, title, main_descr, baseprice,
if (blength=1,concat (blength,' день'),  if ((blength>1 and blength<=4), concat (blength,' дня'), concat (blength,' дней'))) as breslength, if (nights=0,
if (blength-1=1,'1 ночь',if (  (blength-1>1 and blength-1<=4), concat (blength-1,' ночи'), concat (blength-1,' ночей')   )      ),  
if(nights=1, '1 ночь',if (nights>1 and nights<5,concat (nights, 'ночи'),concat (nights, 'ночей')) )

) as bresnight,
blength, cr.name as countryname, cr.flagurl,mainfoto, ct.name, ct.surl FROM `tourmain` tm
JOIN tours tr 
ON tr.id=tm.tourid join countries cr on cr.id=tr.country
join tour_types typ on typ.id=tours.type
join dates dt on dt.tourid=tours.id

";*/

        $sq = "SELECT tr.id, vm.id as vid, vm.title as vtitle, day((dt.date)) as ddate, month((dt.date)) as mdate, dt.id as did, typ.stext as typname, tr.title, main_descr, baseprice,  blength, cr.name as countryname, cr.flagurl,mainfoto, typ.stext, typ.surl,if (blength=1,concat (blength,' день'), if ((blength>1 and blength<=4), concat (blength,' дня'), concat (blength,' дней'))) as breslength, if (nights=0 or nights is null, if (blength-1=1,'/1 ночь',if ( (blength-1>1 and blength-1<=4), concat ('/',blength-1,' ночи'), if (blength<>1,concat ('/',blength-1,' ночей'),\"\") ) ), if(nights=1, '/1 ночь',if (nights>1 and nights<5,concat ('/',nights, 'ночи'),concat ('/',nights, 'ночей')) ) ) as bresnight 
FROM `tourmain` tm 
JOIN tours tr ON tr.id=tm.tourid 
join countries cr on cr.id=tr.country 
join categories typ on typ.id=tr.type 
join dates dt on dt.id=tm.dateid
join view_main vm on vm.id=tm.locid
where tm.siteid=$site and tm.locid=$loc";


        // echo ($sq);
        $loclist = db::query2($sq);
        $rlist = 0;
        if (isset($loclist) && $loclist) {
            $rlist = $loclist->fetchAll();
            //echo("<hr/>$loc");
            //var_dump($rlist);
            //echo(count($rlist)."!");
            //echo("<hr/>");

            return $rlist;
        }


        return $rlist;
    }


    function showLonPrice()
    {
        $rline = "";
        foreach ($this->res as $line) {
            $cm = $line['comment'];
            $rline = $rline . $line['day'] . "." . $line['month'] . ($cm != "" ? " - " . $cm : "") . "; ";
        }
        return $rline;
    }

    function newTour($params)
    {
        $name = "";
        $organizator = 0;
        $days = 0;
        $qt = 0;
        $sq = "insert into tours(title, organizator,days) values(:title, :organizator, :days)";
        if (isset($params["name"])) $name = $params["name"];
        if (isset($params["organizator"])) $organizator = $params["organizator"];
        if (isset($params["days"])) $days = $params["days"];
        $rt = db::prepare($sq);
        if (isset($rt)) {
            $rt->bindParam(':name', $name, PDO::PARAM_STR);
            $rt->bindParam(':organizator', $organizator, PDO::PARAM_INT);
            $rt->bindParam(':days', $days, PDO::PARAM_INT);
//            $rt->debugDumpParams();
            $rt->execute();
            $qt = db::lastInsertId();
        }
        return $qt;
    }

    function updateTour($params)
    {
        $sql = "update tours set";
        if (isset($params["id"])) {
            if (isset($params['title'])) {
                $sql = $sql . " title='" . $params['title'] . "'";
            }
            if (isset($params['description'])) {
                $sql = $sql . ", description='" . $params['description'] . "'";
            }
            if (isset($params['include'])) {
                $sql = $sql . ",  include='" . $params['include'] . "'";
            }
            if (isset($params['exclude'])) {
                $sql = $sql . ", exclude='" . $params['exclude'] . "'";
            }
            if (isset($params['main_descr'])) {
                $sql = $sql . ", main_descr='" . $params['main_descr'] . "'";
            }
            if (isset($params['program'])) {
                $sql = $sql . ", program='" . $params['program'] . "'";
            }
            if (strlen($params['baseprice']) > 0) $sql = $sql . ' ,baseprice=' . $params['baseprice'] . ' ';
            if (strlen($params['startcity']) > 0) $sql = $sql . ' ,startcity=' . intVal($params['startcity']) . ' ';
            if (strlen($params['nights']) > 0) $sql = $sql . ' ,nights=' . $params['nights'] . ' ';
            if (strlen($params['blength']) > 0) $sql = $sql . ' ,blength=' . $params['blength'] . ' ';
            if (strlen($params['type']) > 0) $sql = $sql . ' ,type=' . $params['type'] . ' ';
            $sql = $sql . " where id=" . $params["id"];
        }
        $res = db::query2($sql);
        return json_encode(["res" => $res, "query" => $sql]);

    }

    function getTourDates($tourid)
    {
        $sq = 'select * from dates where tourid=' . $tourid;

        $gt = db::query2($sq);
        $res = false;
        if ($gt) $res = $gt->fetchAll();
        return $res;
    }

    function addTourDate($params)
    {
        if ($params == "") return false;
        $dat = 0;
        $tourid = 0;
        $comment = "";
        $maxplaces = 0;
        $elevent = 0;
        $vkevent = 0;
        $prepay = 0;
        $owner = 0;
        $actual = 0;
        $pricefull = 0;
        $userid = 0;
        $res = 0;
        if (isset($params["date"])) {
            $dat = $params["date"];
        } else return false;
        if (isset($params["tourid"])) {
            $tourid = $params["tourid"];
        } else return false;
        if (isset($params["elevent"])) {
            $elevent = $params["elevent"];
        }
        if (isset($params["userid"])) {
            $userid = $params["userid"];
        }
        if (isset($params["vkevent"])) {
            $vkevent = $params["vkevent"];
        }
        if (isset($params["pricefull"])) {
            $pricefull = $params["pricefull"];
        }
        if (isset($params["prepay"])) {
            $prepay = $params["prepay"];
        }
        if (isset($params["owner"])) {
            $owner = $params["owner"];
        }
        if (isset($params["actual"])) {
            $actual = $params["actual"] ? 1 : 0;
        }
        if (isset($params["comment"])) {
            $comment = $params["comment"];
        }
        if (isset($params["realmaxlimit"])) {
            $maxplaces = $params["realmaxlimit"];
        }
        if ($owner == "" || $owner == 0) $owner = $userid;
        $sq = "insert into dates (owner,prepay, pricefull, actual,tourid, date, realmaxlimit, elevent, vkevent, comment) values ($owner,$prepay, $pricefull, $actual, $tourid, '$dat',$maxplaces, $elevent, $vkevent, '$comment')";
        main::logVar($sq);
        $rt = db::query2($sq);
        $res = [];
        $res["sql"] = $sq;
        if ($rt) $res["newid"] = db::lastInsertId();
        return $res;
    }

    function addTourService($params)
    {
        if ($params == "") return false;
        $dat = 0;
        $tourid = 0;
        $title = "";
        $description = "";
        $price = 0;
        $description = 0;
        $vkevent = 0;
        $res = 0;
        if (isset($params["description"])) {
            $description = $params["description"];
        }
        if (isset($params["title"])) {
            $title = $params["title"];
        } else return false;
        if (isset($params["price"])) {
            $price = $params["price"];
        } else return false;
        if (isset($params["tourid"])) {
            $tourid = $params["tourid"];
        }

        $sq = "insert into add_services(tourid, price, title, description) values ($tourid,$price, '$title','$description')";
        main::logVar($sq);
        $rt = "";
        $rt = db::query2($sq);
        $res = [];
        $res["sql"] = $sq;
        if ($rt) $res["newid"] = db::lastInsertId();
        return $res;
    }

    function updateTourDate($params)
    {


        if ($params == "") return false;
        main::logVar(json_encode($params));
        $dateid = "";
        if (isset($params["dateid"])) {
            $dateid = $params["dateid"];
        } else return false;
        $sq = 'select tourid from dates where id=' . $dateid;
        $rm = db::query2($sq);
        $tourid = 0;
        $rt = false;
        if ($rm) {
            $tres = $rm->fetch(PDO::FETCH_ASSOC);
            if ($tres && $tres["tourid"]) $tourid = $tres["tourid"];

            $dat = 0;

            $comment = "";
            $maxplaces = 0;
            $elevent = 0;
            $vkevent = 0;
            $res = 0;
            $prepay = 0;
            $owner = 0;
            $actual = 0;
            $pricefull = 0;
            $userid = 0;

            if (isset($params["userid"])) {
                $userid = $params["userid"];
            }
            if (isset($params["date"])) {
                $dat = $params["date"];
            }
            if (isset($params["elevent"])) {
                $elevent = $params["elevent"];
            }
            if (isset($params["vkevent"])) {
                $vkevent = $params["vkevent"];
            }
            if (isset($params["comment"])) {
                $comment = $params["comment"];
            }
            if (isset($params["realmaxlimit"])) {
                $maxplaces = $params["realmaxlimit"];
            }
            if (isset($params["pricefull"])) {
                $pricefull = $params["pricefull"];
            }
            if (isset($params["prepay"])) {
                $prepay = $params["prepay"];
            }
            if (isset($params["actual"])) {
                $actual = ($params["actual"] == "1" || $params["actual"] == "true")?1:0;
            }

            if (isset($params["owner"])) {
                $owner = $params["owner"];
                if ($owner == "" || $owner == 0) {
                    $owner = $userid;
                }

            }
            $sq = "update dates set realmaxlimit=$maxplaces, elevent=$elevent, vkevent=$vkevent, comment='$comment',date='$dat', prepay=$prepay, pricefull=$pricefull, actual=$actual, owner=$owner
             where id=$dateid";
            main::logVar($sq);
            //$res["paramsarr"]=json_encode($params);
            $rt = 0;
            if ($tourid != 0) {
                $rt = db::query2($sq);
                $res = [];
                $res["sql"] = $sq;
                if ($rt) $res["tourid"] = $tourid;
            }
        }
        return $res;
    }

    function updateTourDateById($params)
    {
        //main::logVar(json$params);
        /*
           * принимает только изменения comment, maxplaces, vkevent, elevent, date
         * нельзя поменять tourid
           * */
        if ($params == "") return false;

        $dat = 0;
        $tourid = 0;
        $id = 0;
        $comment = "";
        $maxplaces = 0;
        $elevent = 0;
        $vkevent = 0;
        if (isset($params["id"])) {
            $id = $params["id"];
        } else return false;
        if (isset($params["date"])) {
            $dat = $params["date"];
        }
        if (isset($params["tourid"])) {
            $tourid = $params["tourid"];
        }
        if (isset($params["elevent"])) {
            $elevent = $params["elevent"];
        }
        if (isset($params["vkevent"])) {
            $elevent = $params["vkevent"];
        }
        if (isset($params["comment"])) {
            $comment = $params["comment"];
        }
        if (isset($params["maxplaces"])) {
            $maxplaces = $params["maxplaces"];
        }
        $sq = "update dates set tourid=";
        $sq = "update dates set comment='$comment', date='$dat', elevent=$elevent, vkevent=$vkevent, maxplaces=$maxplaces where id=" . $id;
        main::logVar($sq);
        $rt = db::query2($sq);
        if ($rt) return true; else return false;
    }


    function delTourDate($dateid)
    {
        $sq = 'select tourid from dates where id=' . $dateid;
        $rm = db::query2($sq);
        $tourid = 0;
        $rt = false;
        if ($rm) {
            $tres = $rm->fetch(PDO::FETCH_ASSOC);
            if ($tres && $tres["tourid"]) $tourid = $tres["tourid"];
            $sq = 'delete from dates where id=' . $dateid;
            $rq = db::query2($sq);
            if ($rq) $rt = true;
        }

        return ["tourid" => $tourid, "res" => $rt];
    }

    function delTourService($sid)
    {
        $sq = 'select tourid from add_services where id=' . $sid;
        $rm = db::query2($sq);
        $tourid = 0;
        $rt = false;
        if ($rm) {
            $tres = $rm->fetch(PDO::FETCH_ASSOC);
            if ($tres && $tres["tourid"]) $tourid = $tres["tourid"];
            $sq = 'delete from add_services where id=' . $sid;
            $rq = db::query2($sq);
            if ($rq) $rt = true;
        }

        return ["tourid" => $tourid, "res" => $rt];
    }

    function getAddServicesById($id)
    {
        $sq = 'select * from add_services where id=' . $id;
        $gt = db::query2($sq);
        $res = false;
        if ($gt) $res = $gt->fetch(PDO::FETCH_ASSOC);
        return $res;
    }

    function getAddServicesByTourId($tourid)
    {
        $sq = 'select * from add_services where tourid=' . $tourid;
        $gt = db::query2($sq);
        $res = false;
        if ($gt) $res = $gt->fetchAll();
        return $res;
    }

    function getAddServicesByPlaceId($placeid)
    {
        $sq = 'select * from add_services where placeid=' . $placeid;
        $gt = db::query2($sq);
        $res = false;
        if ($gt) $res = $gt->fetchAll();
        return $res;
    }

    function addAddService($params)
    {
        //удалить
        main::logVar(json_encode($params));
        if ($params == "") return false;
        $tourid = 0;
        $placeid = 0;
        $id = 0;
        $price = 0;
        $description = "";
        $visible = 0;
        $type = 0;
        if (isset($params["tourid"])) {
            $tourid = $params["tourid"];
        }
        if (isset($params["placeid"])) {
            $placeid = $params["placeid"];
        }
        if (isset($params["visible"])) {
            $visible = $params["visible"];
        }
        if (isset($params["price"])) {
            $price = $params["price"];
        }
        if (isset($params["title"])) {
            $title = $params["title"];
        } else return false;
        if (isset($params["description"])) {
            $description = $params["description"];
        }
        if (isset($params["type"])) {
            $type = $params["type"];
        } else return false;
        $sq = "insert into add_services (tourid, placeid, price, type, visible, title, description) values($tourid, $placeid, $price, $type, $visible, '$title','$description')";
        main::logVar($sq);
        $rt = db::query2($sq);
        if ($rt) $res = db::lastInsertId();
        return $res;
    }

    function updateTourService($params)
    {

        /*
         * Доделать вариант если приходят только измененные значения
         * */
        //main::logVar("UPDATE".json_encode($params));
        if ($params == "") return false;
        $tourid2 = 0;
        $placeid = 0;
        $id = 0;

        $price = 0;
        $description = "";
        $visible = 0;
        $type = 0;
        if (isset($params["id"])) {
            $id = $params["id"];
        } else return false;

        $sq1 = 'select tourid from add_services where id=' . $id;
        $rm = db::query2($sq1);
        if ($rm) {
            $t = $rm->fetch(PDO::FETCH_ASSOC);
            if ($t && isset($t["tourid"])) $tourid2 = $t["tourid"];
        }


        //if (isset($params["tourid"])) { $tourid=$params["tourid"]; }
        //if (isset($params["placeid"])) { $placeid=$params["placeid"]; }
        //if (isset($params["visible"])) { $visible=$params["visible"]; }
        if (isset($params["price"])) {
            $price = $params["price"];
        }
        if (isset($params["title"])) {
            $title = $params["title"];
        } else return false;
        if (isset($params["description"])) {
            $description = $params["description"];
        }
        //if (isset($params["type"])) {$type=$params["type"]; }else return false;


//    $sq="update add_services set tourid=$tourid, type=$type, visible=$visible, placeid=$placeid, //visible=$visible, price=$price, title='$title',description='$description' where id=".$id;

        $sq = "update add_services set price=$price, title='$title',description='$description' where id=" . $id;
        $res = [];
        $res["sql"] = $sq;
        $res["sq0"] = $sq1;
        $rt = db::query2($sq);
        if ($rt) $res["res"] = "ok";
        if ($tourid2 && intVal($tourid2) > 0) $res["tourid"] = $tourid2;
        return $res;
    }


    function delAddServiceById($id)
    {
        $sq = 'delete from add_services where id=' . $id;
        $rt = db::query2($sq);
        if ($rt) return true; else return false;
    }


}