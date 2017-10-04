<?php

/**
 * Created by PhpStorm.
 * User: Zienko
 * Date: 06.12.2016
 * Time: 11:03
 * Работа с заказами
 * Создание нового
 * итп.
 *
 *
 *
 */
class order
{
    var $orderdata;
    var $uid;

    function __construct()
    {

    }

    function showOne($id)
    {
        $sq = "select * from orders where id=$id";
        $rt = db::query2($sq);
        $order = $rt->fetchAll();
        $result["order"] = $order;

        $sq = "select * from u_reserves where orderid=$id";
        $rm = db::query2($sq);
        $reserves = $rm->fetchAll();

        $result["reserves"] = $reserves;


        return $result;
        //$key = array_search($id, array_column($this->orderdata, 'id'));
        //return $this->orderdata[$key];
    }

    function deleteReserve($rid, $reason = 0)
    {

        $sq = "update u_reserves set deleted=1, deletereason=$reason where id=$rid";
        //echo($sq);
        $res = db::query2($sq);
        if ($res) return ["res" => $res, "sq" => $sq];
        else return ["res" => "nok", "sq" => $sq];
    }

    function changeReserveStatus($rid, $status = 0)
    {
        $sq = "update u_reserves set status=$status where id=$rid";
        //echo($sq);
        $res = db::query2($sq);
        if ($res) return ["res" => $res, "sq" => $sq];
        else return ["res" => "nok", "sq" => $sq];
    }

    function changeTripStatus($rid, $status = 0)
    {
        $sq = "update u_reserves set checkedin=$status where id=$rid";
        //echo($sq);
        $res = db::query2($sq);
        if ($res) return ["res" => $res, "sq" => $sq];
        else return ["res" => "nok", "sq" => $sq];
    }


    function makeHotelOrder($reqline)
    {
        // echo("!");
        $userid = "";
        // var_dump($reqline);
        if (isset($reqline["userid"])) $userid = $reqline["userid"];
        $hid = $reqline["hid"];
        $sdate = $reqline["sdate"];
        $edate = $reqline["edate"];
        $comment = $reqline["comment"];
        $phonenum = $reqline["phone"];
        $sum = $reqline["total"];
        $prepaysum = $reqline["prepay"];

        $sid = $reqline["service_id"];
        $tnum = $reqline["tnum"];


        if ($userid == "") {
            $rtq = main::getUsers();
            $userid = $rtq->getOrAddUser($phonenum);
        }
        $sq = "insert into orders (uid, status, phone, sdate, edate, psum,prepaysum, hid) 
values($userid, 1, '$phonenum','$sdate', '$edate', $sum, $prepaysum, $hid)";


        db::query2($sq);
        $orderid = db::lastInsertId();

        $sq2 = "insert into add_u_reserves (orderid, service_id, `value`) values($orderid, $sid, $tnum)";
        // echo($sq2);
        $rt = db::query2($sq2);

        $result = [];
        $result["userid"] = $userid;
        $result["orderid"] = $orderid;
        $result["status"] = "ok";


        return $result;

    }

    private function getUserData($orderid)
    {
        $sq = "select * from orders where id=$orderid";
        $rm = db::query2($sq);
        $res = false;
        if ($rm) {
            $res = $rm->fetch(PDO::FETCH_ASSOC);
            return $res;
        }
        return $res;
    }

    function getSitePrefix()
    {
        if (strpos($_SERVER["SERVER_NAME"], "dev4.elitsy") !== false || strpos($_SERVER["SERVER_NAME"], "elitsy.pozamerkam") !== false)
            $res = "debug";
        else $res = "prod";
        if ($res=="prod") return "https://elitsy.ru";else return "http://elitsy.pozamerkam.ru";

    }


    function sendUserNotice($orderid)
    {
        $res=$this->getUserData($orderid);
        $res2 = [];
        $res2["orderid"] = $orderid;
        if ($res) {
            $res2["src"] = json_encode($res);
            $res2["mail"] = $res["email"];
            $res2["uid"] = $res["uid"];
            $email = $res["email"];
            $phone = $res["phone"];
            $uid = $res["uid"];
            $letter_text = "Благодарим вас за заказ! \n Вы можете скачать посадочный купон перейдя по ссылке: ".$this->getSitePrefix()."/palomnichestvo/printtour/$uid/v/$orderid \n";
            $res2["text"] = $letter_text;
            main::sendSms($phone,"Ваш посадочный купон в поездку:".$this->getSitePrefix()."/palomnichestvo/printtour/$uid/v/$orderid");
            main::sendMail($email, "Елицы. Паломничество", $letter_text);
        }

        return $res2;
    }

    function sendUserBill($orderid)
    {
        $res=$this->getUserData($orderid);
        $res2 = [];
        $res2["orderid"] = $orderid;
        if ($res) {
            $res2["src"] = json_encode($res);
            $res2["mail"] = $res["email"];
            $res2["uid"] = $res["uid"];
            $email = $res["email"];
            $uid = $res["uid"];
            $letter_text = "Благодарим вас за заказ! \n Просим оплатить счет перейдя по ссылке: ".$this->getSitePrefix()."/palomnichestvo/bill/$uid/v/$orderid \n";
            $res2["text"] = $letter_text;

            main::sendMail($email, "Елицы. Паломничество", $letter_text);
        }

        return $res2;
    }

    function noticer($reqline, $res)
    {
        if (isset ($res["orderid"])) {
            $orderid = $res["orderid"];

            $sq = "SELECT *
FROM clients where istourmaster=1 and id in (
  select owner from dates where id in
                                (select dateid from orders where id=$orderid)
)";
            $rm = db::query2($sq);
            if ($rm) {
                $res = $rm->fetch(PDO::FETCH_ASSOC);
                $owner_name = $res["name"];
                $owner_phone = $res["phone"];
                $owner_email = $res["email"];
                $emailtext = "Вам пришел заказ $orderid, посмотреть на сайте: ";
                main::sendMail($owner_email, "вам пришел заказ", $emailtext);

                //main::logVar($emailtext);
                $this->sendUserNotice($orderid);
            }
        }

    }


    function makeRawOrder($reqline)
    {
        $userid = "0";
        $el = "";
        $prepaysum = 0;
        $totalpaysum = 0;
        $params = [];
        $params = $reqline;

        if (!isset($params["vkuser_id"]) || $params["vkuser_id"] == "") $params["vkuser_id"] = 0;
        if (!isset($params["vkid"]) || $params["vkid"] == "") $params["vkid"] = 0;
        if (!isset($params["oelid"]) || $params["oelid"] == "") $params["oelid"] = 0;
        if (!isset($params["nrid"]) || $params["nrid"] == "") $params["nrid"] = 0;
        if (!isset($params["emid"]) || $params["emid"] == "") $params["emid"] = "";
        if (!isset($params["email"]) || $params["email"] == "") $params["email"] = "";


        main::logVar(json_encode($reqline));

        if (isset($reqline["userid"])) $userid = $reqline["userid"];
        $tourid = $reqline["tourid"];
        $dateid = $reqline["dateid"];
        if ($dateid == "") $dateid = "999999"; //То ли костыль то ли предварительная бронь
        $comment = $reqline["comment"];
        $phonenum = $reqline["phonenum"];
        $inpfam = $reqline["inpfam"];
        if (isset($reqline["prepay"])) $prepaysum = $reqline["prepay"];
        if (isset($reqline["totalpay"])) $totalpaysum = $reqline["totalpay"];


        $elid = 0;
        $elname = "";
        if (isset($reqline["elid"])) $elid = $reqline["elid"];
        if (isset($reqline["elname"])) $elname = $reqline["elname"];
        //if (isset($reqline["elparams"])) $el=addcslashes ($reqline["elparams"]);

        $comment = $comment . " " . $elid . " " . $elname;

        if (isset($reqline["addservices"])) $addservices = $reqline["addservices"];
        if (isset($reqline["hotel"])) $hotel = $reqline["hotel"];


        //$hotel
        $total = count($inpfam);
        if ($total == 0) {
            $total = 1;
            $inpfam[0] = "";


        }
        if ($userid == "" || $userid="1788") { //если юзера нет или админ. в дальнейшем добавить сравнение с БД
            $rtq = main::getUsers();
            $userid = $rtq->getOrAddUser($phonenum);
        }

        /*        if (!isset($req["vkuser_id"])) $params["vkuser_id"]=0;
                if (!isset($req["vkid"])) $params["vkid"]=0;
                if (!isset($req["oelid"])) $params["oelid"]=0;
                if (!isset($req["nrid"])) $params["nrid"]=0;
                if (!isset($req["emid"])) $params["emid"]="";

        */

        $sq0 = "insert into orders (uid, dateid, prepaysum, psum, status, phone, elid, elname, vkuser_id, vkid, oelid, mrid,emid, email) values($userid, $dateid,$prepaysum,$totalpaysum, 1, '$phonenum', $elid,'$elname', ${params["vkuser_id"]},${params["vkid"]}, ${params["oelid"]},${params["nrid"]}, '${params["emid"]}', '${params["email"]}')";
        //  echo($sq);
        main::logVar($sq0);
        db::query2($sq0);
        //TODO Проверить откуда возникает ситуация с ORDERID=0. Такие заказы более нигде не отображаются
        $orderid = db::lastInsertId();
        $resrt = "";
        for ($i = 0; $i < $total; $i++) {
            $str = $inpfam[$i];
            $sq = "INSERT INTO u_reserves(reservedate, fio,orderid,comment, turid, turdate , price )VALUES(now(),'$str', $orderid, '$comment', $tourid, $dateid, getTourPrice($tourid))";
            //echo($sq);
            main::logVar($sq);
            $qrs = db::query2($sq);

            $resrt = $resrt . " " . $sq . " " . db::lastInsertId() . " ";
        }

        if (isset($addservices) and count($addservices > 0)) {
            for ($i = 0; $i < count($addservices); $i++) {
                //echo($orderid);
                $l1 = $addservices[$i];
                //var_dump($l1);
                $sq = "insert into add_u_reserves(service_id, `value`, `orderid`)values($l1,1,$orderid)";
                $qrs = db::query2($sq);
                main::logVar($sq);
                $resrt = $resrt . " " . $sq . " " . db::lastInsertId() . " ";
                //echo($resrt);
            }

        }

        /*
        $hotel=[];
        $hotel["id"]=128;
        $hotreserve=array("id"=>128,"qrt"=>256,"fst"=>208);
        $hotel["reserves"]=array($hotreserve,$hotreserve);
        echo(JSON_encode($hotel));
*/
        if (isset($hotel) and count($hotel > 0))//Скорее всего это нужно удалить...
        {
            $hid = 0;
            $reserves = [];
            if (isset($hotel["id"])) $hid = $hotel["id"];
            if (isset($hotel["reserves"])) $reserves = $hotel["reserves"];
            for ($i = 0; $i < count($reserves); $i++) {
                $rline = $reserves[$i];
                $id = 0;
                $qrt = 0;
                $fst = 0;
                $len = 0;
                if (isset($rline["id"])) $id = $rline["id"];
                if (isset($rline["qrt"])) $qrt = $rline["qrt"];
                if (isset($rline["fst"])) $fst = $rline["fst"];
                if (isset($rline["len"])) $len = $rline["len"];
                if (isset($rline["comment"])) $len = $rline["comment"];
                $sq = "insert into add_u_reserves(orderid, service_id, `value`,conf_startdate, conf_length, conf_comment ) values($orderid, $id,$qrt, date('$fst'), $len, '$comment' )";
                $qrs = db::query2($sq);
                $resrt = $resrt . " " . $sq . " " . db::lastInsertId() . " ";
            }


        }

        $result = [];
        $result["sq0"] = $sq0;
        $result["userid"] = $userid;
        $result["tourid"] = $tourid;
        $result["tourdate"] = $dateid;

        $sq = "select * from dates where id=$dateid";

        $rtt = db::query2($sq);
        if ($rtt) {
            $rdate = $rtt->fetch(PDO::FETCH_ASSOC);

            $rdate2 = $rdate["date"];
            $result["date_num"] = $rdate2;
        }

        $result["prepaysum"] = $prepaysum;
        $result["paymentline"] = $orderid . ":" . $tourid . ":" . $dateid . "x" . $total;


        //line.title + "(" + line.id + ")*" + line.cnt + " " + line.date + " " + line.order_id

        if (isset ($reqline["elevent"])) $result["elevent"] = $reqline["elevent"];


        $result["orderid"] = $orderid;
        $result["resrt"] = $resrt;
        $result["status"] = "ok";
        main::logVar(json_encode($result));
        return $result;

    }

    function getOrder($test)
    {
        return "A$test";

    }


    function showAdminReservedList($id = 0)
    {
        $sq = "SELECT orders.id,orders.payment_status,clients.name AS uname,ur.id AS urrid, orders.status AS ostatus,ur.turdate AS urid, ur.turid AS turid, ur.price AS price, orders.phone AS ophone, clients.phone AS uphone, clients.regdate2, clients.logemail, clients.eluser, ur.fio, ur.phone, comment, orderid, reservedate,turdate, orders.status AS ostatus, ur.status,orders.uid FROM u_reserves ur
JOIN orders  ON ur.orderid=orders.id
JOIN clients ON orders.uid=clients.id
WHERE  " . ($id > 0 ? "turdate=$id and" : "") . " ifnull(ur.deleted,0)<>1 ORDER BY ur.reservedate DESC;";
        $res = db::query2($sq);
//        echo ($sq);
        $rt = "";
        if ($res) $rt = $res->fetchAll();
        return $rt;
    }

    function showAdminList()
    {
        $sq = "SELECT dates.actual, dates.date, day(dates.date) AS day, dates.id AS turdate,  ifnull(ur.cn,0) AS cn,  tours.title, month_name(month(dates.date)) AS month, month(dates.date) AS mn,dates.id,dates.comment, realmaxlimit, tourid FROM 
dates JOIN tours ON 
dates.tourid=tours.id 
LEFT JOIN (SELECT count(*) AS cn, turdate FROM u_reserves WHERE ifnull(deleted,0)=0 AND orderid>0 AND turdate>0 GROUP BY turdate)  ur ON ur.turdate=dates.id
WHERE abs(timestampdiff(MONTH, now(), dates.date))<3 AND 
tours.visible=1 AND tours.type IN (1,2) ORDER BY dates.date;";
        $res = db::query2($sq);
        $rt = "";
        if ($res) $rt = $res->fetchAll();
        return $rt;
    }
}