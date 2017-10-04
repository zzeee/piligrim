<?php

/**
 * Created by PhpStorm.
 * User: zzeee
 * Date: 01.12.2016
 * Time: 22:54
 */
class pointsList
{
    var $plist;

    function __construct()
    {
        $sq = 'SELECT id, name, descr,  main_descr, lon,lat, address, type FROM places WHERE type=1';


        $sq = "SELECT mons.cn, places.d_author, places.id, places.tname, places.descr, places.elitsy_url AS eelitsy,  photos.name AS pname, photos.thumb as thumb, cr.name AS countryname, cr.flagurl AS flagurl, places.name, places.descr,places.visible,
  places.main_descr, lon,lat, address, type FROM places  LEFT JOIN
  (
    SELECT min(sorder),any_value(id) AS id,pid AS pid, any_value(thumbname) AS thumb, any_value(name) AS name FROM photos WHERE pid>0
    GROUP BY pid)
  AS photos
    ON photos.pid=places.id 
    LEFT JOIN (
SELECT count(*) AS cn, cityid AS pid FROM places WHERE type IN (2,3,7) GROUP BY cityid
) AS mons ON mons.pid=places.id

  JOIN countries cr ON cr.id=places.country 
WHERE places.type=1 AND places.visible=1 ORDER BY cn DESC, places.name, places.rating
";

        // echo($sq);
        $rt = db::query2($sq);
        //var_dump($rt);
        $res = [];
        foreach ($rt as $rm) {

            array_push($res, $rm);
        }
        $this->plist = $res;
    }

    function getList($type=0)
    {
        //if ($type==0) return "";
        return $this->plist;

    }

    function getTopPoint()
    {
        $sq = 'SELECT id,url,tname, name FROM places WHERE type=1 AND showtop=1 ORDER BY rating LIMIT 8';
        $rt = db::query2($sq);
        $res = [];
        foreach ($rt as $rm) {
            array_push($res, $rm);
        }
        return $res;
    }


    function getTopMainPoint()
    {
        $sq = "SELECT  cr.name AS countryname, cr.flagurl AS flagurl, p.id,p.url,p.tname, p.descr, p.name, pmon.cn, photos.name AS pname, photos.thumb, photos.gallery FROM places p
LEFT JOIN (
SELECT count(*) AS cn, cityid AS pid FROM places WHERE type IN (2)  AND cityid>0 GROUP BY cityid
) AS pmon ON p.cityid=pmon.pid 

LEFT JOIN
  (
    SELECT min(sorder),any_value(id) AS id,pid AS pid, any_value(name) AS name, any_value(thumbname) as thumb, any_value(galname) as gallery FROM photos WHERE pid>0
    GROUP BY pid)
  AS photos
    ON photos.pid=p.id 
  JOIN countries cr ON cr.id=p.country 

WHERE p.type=1 AND  length(p.descr)>1000  and length(photos.name)>0 and p.id>floor(1+62*rand(100))  order by rand() LIMIT 3

";
        $rt = db::query2($sq);
        if ($rt)
            $rm = $rt->fetchAll();
        return $rm;

    }

    function getOne($id)
    {
        // $key = array_search($id, array_column($this->plist, 'id'));
        // return $this->plist[$key];


        $sq = 'select * from places where id=' . $id;


$sq="SELECT mons.cn, places.d_author, places.id, places.tname, places.descr, places.elitsy_url AS eelitsy,  photos.name AS pname, photos.thumb as thumb, cr.name AS countryname, cr.flagurl AS flagurl, places.name, places.descr,
  places.main_descr, lon,lat, address, type FROM places  LEFT JOIN
  (
    SELECT min(sorder),any_value(id) AS id,any_value(thumbname) as thumb,pid AS pid, any_value(name) AS name FROM photos WHERE pid>0
    GROUP BY pid)
    AS photos
    ON photos.pid=places.id
  LEFT JOIN (
              SELECT count(*) AS cn, cityid AS pid FROM places WHERE type IN (2,3,7) GROUP BY cityid
            ) AS mons ON mons.pid=places.id

  JOIN countries cr ON cr.id=places.country
WHERE places.type=1 AND places.visible=1 and places.id=$id ORDER BY cn DESC, places.name, places.rating
";
        $rt = db::query2($sq);
//echo($sq);


        $res = "";
        if ($rt) $res = $rt->fetch(PDO::FETCH_ASSOC);
        return $res;
    }

    function getTopHotels()
    {
        $sq = "SELECT pq.id AS citid, pq.tname AS pqt, pq.name AS citname, cr.name AS countryname, cr.flagurl AS flagurl, p.id, p.name, p.tname, p.descr, photos.name AS pname, photos.thumb, photos.gallery FROM places p 
LEFT JOIN
  (
    SELECT min(sorder),any_value(thumbname) as thumb, any_value(galname) as gallery, any_value(id) AS id,pid AS pid, any_value(name) AS name FROM photos WHERE pid>0
    GROUP BY pid)
  AS photos
    ON photos.pid=p.id 
    JOIN countries cr ON cr.id=p.country 
LEFT JOIN places pq ON
p.cityid=pq.id    
WHERE p.type=100 LIMIT 3;";

        $rt = db::query2($sq);
        if ($rt)
            $rm = $rt->fetchAll();
        return $rm;


    }

    function getOnesPhoto($id)
    {
        $sq = "select * from photos where pid=$id";
//echo($sq);
        $rt = db::query2($sq);
        if ($rt)
            $rm = $rt->fetchAll();
        return $rm;
    }

    function getToursToPlace($id)
    {
        $sq = "select tours.id, tours.title, tours.description from tours join tours_places tr on tr.tourid=tours.id where placeid=$id";
        $sq = "select photos.thumb, photos.gallery, photos.super, photos.name as mainfoto, ifnull(dt.pricefull,tours.baseprice) as baseprice, cr.flagurl, cr.name as countryname, dt.md, day(dt.md) as ddate, tours.description as main_descr,  month(dt.md) as mdate,  
tours.id as id, tours.title as title, ct.surl, ct.name as typname from tours_places  tp join tours on tours.id=tp.tourid
join categories ct on ct.id=tours.type
join countries cr on cr.id=tours.country
left join ( SELECT
           min(sorder),any_value(thumbname) as thumb, any_value(galname) as gallery, any_value(supername) as super,
           any_value(id)   AS id,
           tid,
           any_value(name) AS name
         FROM photos
         WHERE tid > 0
         GROUP BY tid) as photos on photos.tid=tours.id 
left join (select min(date) as md, any_value(pricefull) as pricefull, tourid from dates where date>now()  group by tourid ) as dt on dt.tourid=tours.id 
where placeid=$id  and tours.visible=1";
        ///echo($sq);
        $rt = db::query2($sq);
        $rm=false;
        if ($rt)
        $rm = $rt->fetchAll();
        return $rm;
    }

    function getMonasteriesofPlace($id)
    {
        $sq = "select * from places where cityid=$id and type=2 ";

        $sq = "select places.id, places.tname, photos.thumb, photos.gallery, photos.super, photos.name as pname, cr.name as countryname, cr.flagurl as flagurl, places.name, places.descr,
  places.main_descr, lon,lat, address, type from places  left join
  (
    select min(sorder),any_value(thumbname) as thumb, any_value(galname) as gallery, any_value(supername) as super, any_value(id) as id,pid as pid, any_value(name) as name from photos where pid>0
    group by pid)
  as photos
  on photos.pid=places.id 
  join countries cr on cr.id=places.country 
where places.type=2 and places.cityid=$id order by places.rating
";

        $rt = db::query2($sq);
        $rm = $rt->fetchAll();
        return $rm;

    }

    function getSaintWater($id)
    {
        $sq = "select * from places where cityid=$id and type=6";
        $rt = db::query2($sq);
        $rm = $rt->fetchAll();
        return $rm;

    }

    function getHram($id)
    {
        $sq = "select * from places where cityid=$id and type=3";

        $sq = "select places.id, places.tname,  places.name, places.descr, places.type, places.country, places.cityid, ph.name as pname, places.main_descr from places 
  join (select min(sorder),any_value(id) as id,pid as pid, any_value(name) as name from photos where pid>0 group by pid) as ph on ph.pid=places.id
where cityid=$id and type=3
";
        $rt = db::query2($sq);
        $rm = $rt->fetchAll();
        return $rm;
    }

    function getHallow($id)
    {
        $sq = "select * from places where cityid=$id and type=7";
        $rt = db::query2($sq);
        $rm = $rt->fetchAll();
        return $rm;
    }

    function getIcon($id)
    {
        $sq = "select * from places where cityid=$id and type=8";
        $rt = db::query2($sq);
        $rm = $rt->fetchAll();
        return $rm;
    }


    function getHist($id)
    {

        $sq = "select places.id, places.tname, places.name, places.type, places.country, places.cityid, ph.name as pname, places.main_descr from places
  left join (select min(sorder),any_value(id) as id,pid as pid, any_value(name) as name from photos where pid>0 group by pid) as ph on ph.pid=places.id
where cityid=$id and type=10";
        $rt = db::query2($sq);
        $rm = $rt->fetchAll();
        return $rm;
    }

    function getHotelServices($id)
    {
        $sq = "select * from add_services where placeid=$id and type=3 and visible=1";

        $sq = "select asr.id,  asr.title, asr.price, asr.price1, asr.price2, asr.description, asr.placeid, photos.name as mainfoto  from add_services asr left join(
 SELECT
                min(sorder),
                any_value(id)   AS id,
                asid,
                any_value(name) AS name
              FROM photos
              WHERE asid > 0
              GROUP BY asid)
    AS photos
  on asr.id=photos.asid
where type=3 and visible=1 and asr.placeid=$id
";
        $rt = db::query2($sq);
        $rm = $rt->fetchAll();
        return $rm;
    }

    function getHotels($id)
    {
        $sq = "select * from places where cityid=$id and type=100 and visible=1";
        $rt = db::query2($sq);
        $rm = $rt->fetchAll();
        return $rm;
    }


    function showHotel($id)
    {
        $res = array('common' => $this->getHotelById($id), 'services' => $this->getHotelServices($id));

        return $res;


    }


    function getHotelById($id)
    {
        $sq = "select * from places where id=$id and type=100 and visible=1";
        $sq = "select places.id, places.name as title, places.descr, places.country, places.cityid, places.elitsy_url, places.tname, photos.name as mainfoto from places 
left join( 
 SELECT
                min(sorder),
                any_value(id)   AS id,
                pid,
                any_value(name) AS name
              FROM photos
              WHERE pid > 0
              GROUP BY pid)
    AS photos
  on places.id=photos.pid
  where places.id=$id and type=100 and visible=1";
        $rt = db::query2($sq);
        $rm = $rt->fetch(PDO::FETCH_ASSOC);
        //var_dump($rm);
        //echo ($rm["name"]);
        return $rm;
    }


    function exportPoints($type = 0)
    {
        $sq = "SELECT places.id, places.visible, places.tname, ifnull(places.elitsy_url,'') as elitsy_url, places.reqvis as reqvis, ifnull(places.name,'') AS name, places.descr, places.d_author,ifnull(places.cityid,'') AS cityid, places.address,places.type, places.country, places.main_descr, places.lat, places.lon, photos.name as mainfoto,photos.id as mainfoto_id FROM places
  LEFT JOIN (
              SELECT
                min(sorder),
                any_value(id)   AS id,
                pid,
                any_value(name) AS name
              FROM photos
              WHERE pid > 0
              GROUP BY pid)
    AS photos
  ON places.id=photos.pid order by places.id desc
";
        $rt = "";
        $res = db::query2($sq);
        if ($res) $rt = $res->fetchAll();

        return $rt;

    }

    function getPointAddInfo($placeid)
    {
        $sq="select * from add_info where pointid=$placeid";
        $rt = "";
        $res = db::query2($sq);
        if ($res) $rt = $res->fetchAll();

        return $rt;

    }
function delPointAddInfo($pointid)
    {
        $sq="delete from add_info where id=$pointid";
        $rt = ["status"=>"nok"];
        $res = db::query2($sq);
        if ($res) $rt["status"]="ok";
        return $rt;
    }

    function savePointAddInfo($params)
    {
        $param=$params;
        $res=["status"=>"nok"];
        $rt=false;
        if (!isset($param["id"]) && isset($param["pointid"])){
            $sq0="insert into add_info(pointid) values(${param["pointid"]}) ";
            db::query2($sq0);
            $param["id"]=db::lastInsertId();
        }

        if (!isset($param["url"]) || $param["url"]==="null") $param["url"]="";
        if (!isset($param["type"])) $param["type"]=1;
        if (!isset($param["title"])) $param["title"]="";
        if (!isset($param["description"])) $param["description"]="";



        $sq="update add_info set title='${param["title"]}', description='${param["description"]}', type=${param["type"]}, url='${param["url"]}' where id=${param["id"]}";
        //echo($sq);
        $rt=db::query2($sq);

        if ($rt) {$res["status"]="ok";$res["data"]=$rt;}
        return $res;
    }

    function getPlacesUrlArray()
    {
        $sq = "SELECT id, type,tname FROM places WHERE visible=1";
        $sq = "SELECT places.id, places.name, type,tname, photos.name as main_foto FROM places
LEFT JOIN
(
SELECT min(sorder),any_value(id) AS id,pid AS pid, any_value(name) AS name FROM photos WHERE pid>0
GROUP BY pid)
AS photos
ON photos.pid=places.id
WHERE places.visible=1;
";
        $res = db::query2($sq);
        $rt = [];
        if ($res) $rt = $res->fetchAll();
        $gt = array_map(function ($n) {
            $qres = [];
            $rl = "/palomnichestvo/";
            switch ($n["type"]):
                case 1:
                    $rl = $rl . "point/" . $n["tname"];
                    break;
                case 2;
                    $rl = $rl . "sp/" . $n["tname"];
                    break;
                case 3;
                    $rl = $rl . "sp/" . $n["tname"];
                    break;
                default:
                    $rl = "";
                    break;

            endswitch;
            $qres["url"] = $rl;
            $qres["foto"] = $n["main_foto"];
            $qres["title"] = $n["name"];
            return $qres;
        },
            $rt);
        //echo("");
        return $gt;
    }


    function updatePoint($params)
    {
        @main::logVar(json_encode($params));
        if ($params == null) {
            return (json_encode(["res" => 'Ошибка приема', "status" => "nok"]));
        }
        $id = $params["id"];
        $newname = $params["name"];
        $sq = "update places set name='" . $newname . "' ";
        if (isset($params["lat"])) $sq .= ", lat=" . (($params["lat"] == "") ? "null" : "'" . floatval($params["lat"]) . "'");
        if (isset($params["cityid"])) $sq .= ", cityid=" . (($params["cityid"] == "") ? "null" : "'" . intval($params["cityid"]) . "'");
        if (isset($params["lon"])) $sq .= ", lon=" . (($params["lon"] == "") ? "null" : "'" . floatval($params["lon"]) . "'");
        if (isset($params["elitsy_url"])) $sq .= ", elitsy_url=" . (($params["elitsy_url"] == "") ? "0" : floatval($params["elitsy_url"]));

        if (isset($params["address"])) $sq .= ", address='" . $params["address"] . "'";
        if (isset($params["main_descr"])) $sq .= ", main_descr='" . $params["main_descr"] . "'";
        if (isset($params["descr"])) $sq .= ", descr='" . $params["descr"] . "'";
        if (isset($params["d_author"])) $sq .= ", d_author='" . $params["d_author"] . "'";
        $sq .= " where id=" . $id;
        $resq = "";
        @main::logVar($sq);
        try {
            $resq = db::query2($sq);
            if ($resq) return (json_encode(["status" => "ok", "updated_id" => $id, "res" => $resq]));
            else            return (json_encode(["status" => "nok", "sq" => $sq]));

        } catch (Exception $e) {
            return (json_encode(["status" => "nok", "res" => $e->getMessage(), "sq" => $sq]));
        }
        return "";
    }


    function newPoint($name, $type)
    {

        $sq = "insert into places(name, tname, type, createdate) values(:name, _fs_transliterate_ru(:name),:type, now())";
        $rt = db::prepare($sq);
        $qt = "";
        if (isset($rt)) {
            $rt->bindParam(':name', $name, PDO::PARAM_STR);
            $rt->bindParam(':type', $type, PDO::PARAM_INT);
//            $rt->debugDumpParams();
            $qr = $rt->execute();
            $qt = db::lastInsertId();
        }
        if ($qt) return intVal($qt);
        else return false;
    }

    function delPoint($id)
    {
        $sq = "delete from places where id=:id";
        $rt = db::prepare($sq);
        $qt = "";
        if (isset($rt)) {
            $rt->bindParam(':id', $id, PDO::PARAM_INT);
            $qr = $rt->execute();
            $qt = $qr;//db::lastInsertId();
        }
        return $qt;
    }

    function depublish($id)
    {
        $sq = "update places set visible=0 where id=:id";
        $rt = db::prepare($sq);
        $qt = "";
        if (isset($rt)) {
            $rt->bindParam(':id', $id, PDO::PARAM_INT);
            $qr = $rt->execute();
            $qt = $qr;//db::lastInsertId();
        }
        return $qt;
    }

    function publishpoint($id)
    {
        $sq = "update places set visible=1 where  id=:id";
        $rt = db::prepare($sq);
        $qt = "";
        if (isset($rt)) {
            $rt->bindParam(':id', $id, PDO::PARAM_INT);
            $qr = $rt->execute();
            $qt = $qr;//db::lastInsertId();
        }
        return $qt;
    }


    function updatePointPic()
    {
        $rawInput = file_get_contents('php://input');
//var_dump($rawInput);
        //   $params = json_decode($rawInput, true);
        //    echo("QQQQQQQQ".substr($rawInput,0,200));
        $qrt = strpos($rawInput, "!+!");
//echo($qrt);
//echo("sdef");
        $len_filenamelen = (substr($rawInput, 0, $qrt));
        //echo("!!!!!!".$len_filenamelen);
        //$filename_len=substr($rawInput, 0,$len_filenamelen);
        $filename = substr($rawInput, $qrt + 3, $len_filenamelen);
        //echo(("AAAAA".$filename));
        $par2 = substr($rawInput, $qrt + strlen("!+!") + $len_filenamelen + strlen("data:image/png;base64,"));
        $qrt2 = base64_decode($par2);
        //var_dump($qrt2);
        $fp = fopen(main::picDirectoryRoot() . '/eltest/tmp/data.png', 'w');
        if (fwrite($fp, $qrt2) === FALSE) {
            echo "Не могу произвести запись в файл ($qrt2)";
            exit;
        }

        fclose($fp);

    }

    function getPointPhotos($id)
    {
     $sq="select * from photos where pid=$id";
     $rs=db::query2($sq);
     $res=["status"=>"nok", "id"=>$id];
     if ($rs) $res=$rs->fetchAll();
     return $res;

    }


}