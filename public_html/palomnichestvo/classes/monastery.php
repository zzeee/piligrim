<?php
/**
 * Created by PhpStorm.
 * User: Zienko
 * Date: 01.12.2016
 * Time: 16:10
 */


class monastery
{
    function __construct()
    {
    }


    function showOne($id)
    {
       // $sq = "select pl.id as id, pl.name as name, pl.main_descr as main_descr , pr.name as cityname, pr.id as cityid from places pl JOIN places pr on pl.cityid=pr.id where  pl.id=$id";
        $sq="select pl.id as id, pl.tname as tname, pl.name as name,pl.descr, pl.elitsy_url as eelitsy, photos.name as pname, photos.thumb as thumb, pl.main_descr as main_descr , pr.name as cityname, pr.tname as citytname, pr.id as cityid from places pl 
  left JOIN places pr on pl.cityid=pr.id
  left join (
      select min(sorder),any_value(id) as id,pid as pid, any_value(thumbname) as thumb, any_value(name) as name from photos where pid>0
      group by pid)
as photos on photos.pid=pr.id 
where  pl.id=$id";
        
       //echo($sq);
        //$rt = db::query($sq);
        $rt = db::query2($sq);
        if ($rt) $res["data"]=$rt->fetchAll();
        $sq="select * from photos where pid=$id order by sorder";
        $rm = db::query2($sq);
        $res["photos"]=$rm->fetchAll();
        $sq="select tours.id as id, tours.title as title, ct.surl, ct.name as catname from tours_places  tp join tours on tours.id=tp.tourid
join categories ct on ct.id=tours.type
where placeid=$id and tours.visible=1";

        $sq="select photos.thumb as thumb,photos.name as mainfoto,  cr.flagurl, cr.name as countryname, dt.md, day(dt.md) as ddate, tours.description as main_descr,  ifnull(dt.pricefull, tours.baseprice) as baseprice, month(dt.md) as mdate,  
tours.id as id, tours.title as title, ct.surl, ct.name as typname from tours_places  tp join tours on tours.id=tp.tourid
join categories ct on ct.id=tours.type
join countries cr on cr.id=tours.country
left join ( SELECT
           min(sorder),
           any_value(id)   AS id,
           tid,
           any_value(thumbname) as thumb,
           any_value(name) AS name
         FROM photos
         WHERE tid > 0
         GROUP BY tid) as photos on photos.tid=tours.id 
left join (select min(date) as md,any_value(pricefull) as pricefull, tourid  from dates where date>now()  group by tourid ) as dt on dt.tourid=tours.id 
where placeid=$id  and tours.visible=1";
        //echo($sq);
        $rm=db::query2($sq);
        if ($rm ) {
            $res["places"] = $rm->fetchAll();
        }
        //var_dump($res["places"]);
        return $res;
    }

    function showList($type=0, $visible=1)
    {
        $sq = 'select * from places where type=2 and  visible=1 order by name';
        $sq="select cr.flagurl, cit.visible as cv, cit.id as cid, cit.name as cname, p.tname as tname, cr.name as countryname, p.id as id,p.name as name, p.url, p.descr, ph.thumb as thumb, ph.name as pname from places p
  left join (select min(sorder),any_value(thumbname) as thumb, any_value(id) as id,pid as pid, any_value(name) as name from photos where pid>0 group by pid) as ph on ph.pid=p.id
  left join places cit on p.cityid=cit.id
  left join countries cr on cr.id=p.country
where p.type=2  and ifnull(p.visible,0)=$visible order by (length(p.descr)>100) desc, p.rating, p.name";


        if ($type==1) {$sq="select cr.flagurl, p.tname as tname, cr.name as countryname, p.id as id,p.name as name, p.url, p.descr, ph.name as pname from places p
join photos ph on ph.asid=p.id
join countries cr on cr.id=p.country
where p.type=2 and  p.showtop=1 and p.visible=1 order by p.rating limit 3";

        $sq="           select cr.flagurl, p.tname as tname, cr.name as countryname, p.id as id,p.name as name, p.url, p.descr, ph.name as pname, ph.thumb, ph.gallery from places p
  join (select min(sorder),any_value(id) as id,pid as pid, any_value(name) as name, any_value(thumbname) as thumb, any_value(galname) as gallery from photos where pid>0 group by pid) as ph on ph.pid=p.id
  join countries cr on cr.id=p.country
where p.type=2 and  p.showtop=1 and p.visible=1 order by p.rating limit 3";

        }

//echo($sq);
        $res=[];
        $dat=[];
        try{
//            echo($sq);
        $rt = db::query2($sq);
            foreach ($rt as $rm) {
                array_push($res,$rm);
            }
        }catch (Exception $e) {echo($e->getMessage());}
        return $res;
    }






}