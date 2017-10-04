<?php

/**
 * Created by PhpStorm.
 * User: Zienko
 * Date: 06.12.2016
 * Time: 11:04
 */
class users
{

    var $users;

    function __construct()
    {
        $sq = 'select * from clients';
        $rt = db::query2($sq);
        $res = [];
        foreach ($rt as $line) {
            array_push($res, $line);
        }

        $rt = db::query2($sq);
        foreach ($rt as $rm) {
            array_push($res, $rm);
        }


        $this->users = $res;

    }

    function getUsers()
    {
        return $this->users;

    }


    function getUserFio($id)
    {
        $key = array_search($id, array_column($this->users, 'id'));
        echo($key);
    }

    function checkAuth($login, $pwd)
    {
        $md5p = md5($pwd);
        $sq = "select id, name, phone,logemail, type from clients where md5(pwd)='$md5p' and logemail=:login";// and
        //echo($sq);
        $rt = db::prepare($sq);
        if (isset($rt)) {
            $rt->bindParam(':login', $login, PDO::PARAM_STR);
            $qt = $rt->execute();
            if ($qt) {
                $res = $rt->fetch(PDO::FETCH_ASSOC);
                if ($res) {
                    $res["sid"] = session_id();
                    $res["findate"] = "423424";
                    // echo('efewf');
                    //  echo ($_SESSION["userid"]);

                }
                return $res;
            } else {
                return false;
            }
        } else return false;
    }

    function getOrAddUser($phonenum)
    {
        //$sq="replace c";

        $sq = "select * from clients where phone='$phonenum'";
        $rs = db::query2($sq);
        if ($rs) $rt = $rs->fetch(PDO::FETCH_ASSOC);
        $res = 0;

        if ($rt) {
            $res = $rt["id"];
        } else {
            $sq = "insert into clients(phone, regdate) values('$phonenum', now())";
            //              echo($sq);
            $rs = db::query2($sq);
            if ($rs) $res = db::lastInsertId();


        }
//echo("USER:$res\n");
        return $res;

    }

    function getUserById($uid)
    {
        $sq = "select * from clients where id=$uid";
        $res = 0;
        $rs = db::query2($sq);

        if ($rs) $rt = $rs->fetch(PDO::FETCH_ASSOC);
        if ($rt) $res = $rt;


        return $res;

    }

    function regElUser($ouserid)
    {
        $res = 0;
        $userid = 0;
        $sq2 = "select id from clients where eluser=$ouserid";
        $rt = db::query2($sq2);
        //var_dump($rt);
        if ($rt) {
            $rs = $rt->fetch(PDO::FETCH_ASSOC);
            main::setUserId($rs["id"]);
            $userid = $rs["id"];


            if ($rs) {
                $res = $rs;

            } else {
                $userid = $this->addElUser($ouserid);
                main::setUserId($userid);
            }

        } else {
            $userid = $this->addElUser($ouserid);
            main::setUserId($userid);

        }

        $sq = "select id, eluser, name, phone, isadmin, iseditor, istourmaster, isadv, regdate, email from clients where id=$userid";
        $rt = db::query2($sq);
        $res = $rt->fetch(PDO::FETCH_ASSOC);
        return $res;
    }

    function addElUser($userid)
    {
        $sq = "insert into clients(eluser, regdate) values($userid, now())";
        //echo ($sq);
        $rs = db::query2($sq);
        $rsq = db::lastInsertId();
        //echo ($rsq);
        return $rsq;
        //return  ["id" => $rsq];
    }

    function getUserBills($userid)
    {
        $sq = "select cl.email,b.id as bid,  cl.name, cl.phone,b.sum, b.uid, b.comment, b.status  from bills  b left join payments  p on  p.billid=b.id 
join clients cl on cl.id=b.uid
where b.uid=$userid";
        //echo($sq);
        $rt = db::query2($sq);
        if ($rt) {
            $rq = $rt->fetchAll();
            return $rq;
        } else return 0;
    }

    function getUserInfo($uid)
    {
        $sq = "select id, eluser, name, phone, regdate, email from clients where id=$uid";


        $rt = db::query2($sq);
        if ($rt) {
            $rq = $rt->fetchAll();
            return $rq;
        } else return 0;


    }

    function setRawData($ln)
    {
        $uphone = $ln["uphone"];
        $uemail = $ln["uemail"];
        $uname = $ln["uname"];
        $uid = $ln["userid"];
        $sq = "update clients set phone='$uphone', email='$uemail', name='$uname' where id=$uid";
        $res = "";
        //echo($sq);

        $res = db::query2($sq);


        return $res;

    }


}