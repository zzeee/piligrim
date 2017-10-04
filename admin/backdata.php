<?php
header("Access-Control-Allow-Origin: *");
ini_set('display_errors','On');
error_reporting('E_ALL');

define("IN_ADMIN", TRUE);
require "sqli.php";
require "commlib.php";
//echo('sss');

try {
    //require "newconnect-head.php";
}
catch(Exception $e) {echo ($e->getMessage());}
///echo('sss');



function newClient($id, $phone)
{
    global $mysqli;

    $pwd=generate_password1(6);
    $sq="update clients set invsent=1, pwd='".$pwd."'  where id=".$id;
    //echo ($sq);
    $mysqli->query($sq);
    $smstext="Используйте телефон:".$phone." и пароль :".$pwd." для входа";
//    console.log($smstext);
    //sendSms($phone,$smstext);
}


function doReg($login, $pwd, $email)
{
    global $mysqli;
    $sq="insert into clients(regdate, phone, pwd, email) values(now(), '".$login."','".$pwd."','".$email."')";
    mail("zzeeee@gmail.com","log", $sq);
    $mysqli->query($sq);
    $res=$mysqli->insert_id;
    $resv['result']=$res;
    echo(json_encode($resv));


}






function showUserReserves($id, $type, $tourid)
{
    global $mysqli;

    if ($type=="1")  $sq='select * from my_reserves where deleted=0 and status=0 and payed=1 and uid='.$id.' order by reservedate desc';
    if ($type=="2") $sq='select * from my_reserves where deleted=0 and uid='.$id.' and status in (1) and turdate in (select id from dates where date>now()) order by reservedate desc';
    if ($type=="3") $sq='select * from my_reserves where deleted=0 and uid='.$id.' and turdate in (select id from dates where date<now()) order by reservedate desc';
    if ($type=="4")  $sq='select * from my_reserves where deleted=0 and payed=0 and turdate in (select id from dates where date>now()) and uid='.$id.' order by reservedate desc';
    if ($type=="5")  $sq='select * from my_reserves where id='.$id;
    if ($type=="6")  $sq='select * from my_reserves where deleted=0 and status=1 and turid='.$tourid.' and uid='.$id.' order by reservedate desc';
    if ($type=="7")  $sq='select * from my_reserves where deleted=0 and uid='.$id.' order by reservedate desc';


    //echo($sq);

    $res=$mysqli->query($sq);
    $rstr="";
    $arr=array();

    if ($type!=5){//Если работаем с массивом из множества резервов

        if ($res)
            while($r = $res->fetch_assoc()) {
                $arr[]=$r;
                //  $rstr=$rstr.json_encode($r);
                //$rstr=$rstr.'"id":"'.$r['id'].'"';
                //$rstr=$rstr.'"turid":"'.$r['turid'].'"';
            }
    }
    else//Если один резерв
    {
        if ($res) {
            $r = $res->fetch_assoc();
            $arr['reservedata']=$r;
            $urid=$r['uid'];
            $sq='select * from clients where id='.$urid;
            $rt=$mysqli->query($sq);
            if ($rt) {$rdata=$rt->fetch_assoc();
                $arr['userdata']=$rdata;
            }

        }
    }
    //header("Access-Control-Allow-Origin: http://molodrus.ru");
    //header("Access-Control-Allow-Credentials: true");


    echo (json_encode($arr));



}



function fastReg($phone)
{





}



function showAddServices()
{
    $tourid=$_GET['tournumber'];
    global $mysqli;
    $sq='select * from add_services where visible=1 '.($tourid!=""?" and tourid=".$tourid:"")." union (select * from add_services where type in (1,2))";

    $res=$mysqli->query($sq);
    $rows = array();
    while($r = $res->fetch_assoc()) {
        $row['id'] = $r['id'];
        $row['title'] = $r['title'];
        $row['description'] = $r['description'];
        $p=$r['price'];
        if (checkMr()=="1") $p=$r['price1'];
        if (checkAction=="1") $p=$r['price2'];
        if ($_GET['dealer']=="1") $p=$r['price3'];
        if ($_GET['inaction']=="1") $p=$r['price2'];
        $row['price']=$p;


        /*
        $row['price'] =*/
        array_push($rows,$row);
    }

    echo(json_encode($rows));



}







function sendToServer2()
{
    //echo('efwefwf2');
    global $mysqli;
    $inputfile= file_get_contents('php://input');

    //echo ($inputfile);

    $input= json_decode( $inputfile, TRUE ); //convert JSON into array
    mail("zzeeee@gmail.com", "log", $input);
    $uid=$input['uid'];//ОБЯЗАТЕЛЬНО! Если клиент новый uid=0
    $orders=$input['orderline'];
    $uid=$input['uid'];
    $uname=$input['uname'];
    $uphone=$input['uphone'];
    $ordernum=count($orders);

        $sq='select * from clients where id='.$uid;
//echo($sq);
    //die();
$res = $mysqli->query($sq);
    $suid=0;
    $usercreated=false;
    if ($res->num_rows>0) {
        $rm=$res->fetch_array();

        $suid=$rm['id'];
        //echo ($suid);//идентификатор клиента для заказов
    }
    else {
        $sq="insert into clients(regdate, phone, name) values(now(),'".$uphone."','".$uname."')";
        //echo($sq);
        $mysqli->query($sq);
        $suid=$mysqli->insert_id;
        newClient($suid, $uphone);
        $usercreated=true;

//        echo ('nre!!!');

    }
  //  echo ($res->num_rows."<br />!! ".$suid);

    //echo ($createuser);

/*    if ($createuser=="1") {
        newClient($suid, $uphone);
        //echo ($suid." ");
        //   echo ($uphone);

    }*/
    //echo ($inputfile." ".$ordernum);
    $resstr='';

    if ($ordernum>0)
    {
            //echo('!!!1!!!');
        $priceoforder=0;
        $sq="insert into orders(uid)values(".$suid.")";
        $mysqli->query($sq);
        $orderid=$mysqli->insert_id;

        for($i=0;$i<$ordernum;$i++)
        {
            $order=$orders[$i];
            $fio=$order['name'];
            $phone=$order['phone'];
            //$passport=$input['fio-p_'.$i];
            $tourid=$order['tourid'];
            $tourdate=$order['tdate'];
            $appindex=$order['fid'];
            $options=$order['option'];
            $totalprice=$order['totalprice'];
            if ($totalprice=="") $totalprice=0;

            $priceoforder=$priceoforder.$totalprice;

            $dealerid=0; //ПОКА(!)
            $comment="";



            $sq="insert into u_reserves (reservedate, orderid, price, fio, phone,  turid, turdate, comment, uid, dealerid)values(now(),".$orderid.",".$totalprice.", '".$fio."','".$phone."','".$tourid."','".$tourdate."','".$comment."', ".$suid.",".$dealerid.")";
//            echo($sq);

            $res = $mysqli->query($sq);
//            if (!$res) {echo ($sq." ".$mysqli->error);}

            if (!$res) {
                echo($mysqli->error);
            } else {
                $ok = $mysqli->insert_id;
                //echo ('swww');

$options="&".$options;
//echo ($options."++++++++++++++++++++++342");

                $arr = explode("&pars%5B%5D=", $options);
                if ($appindex!="") $resstr = $resstr . ',"' . $appindex . '":' . $ok;
                //Добавляем информацию о доп. опциях заказа
             //   echo ("????".count($arr)."!!!");
                for ($q = 1; $q <= count($arr); $q++) {
//                    echo ("---".$arr[$q]."---/---/");
                    if ($arr[$q]!="") {
                        $sql1 = 'insert into add_u_reserves(reserve_id,service_id, value) values(' . $ok . ',' . $arr[$q] . ',1)';
                        $rm = $mysqli->query($sql1);
                       //echo($q . "---" . $sql1 . " " . $arr[$q] . " " . $q);
                    }

                }


            }

        }



        //Выставляем счет:
        $sq='insert into bills(orderid, sum, status) values('.$orderid.",".$priceoforder." ,1)";
        $mysqli->query($sq);
        $bid=$mysqli->insert_id;

        $resarr['uid']=$suid;
        $resarr['usercreated']=$usercreated;
        $resarr['orderid']=$orderid;
        $resarr['bid']=$bid;
        echo(json_encode($resarr));

        //Формируем ответ: уид пользователя
        //$resstr=$resstr.',"uid":'.$suid;
        //$resstr=$resstr.',"orderid":'.$orderid;
        //$resstr=$resstr.',"bid":'.$bid;



    }


    //$resstr="{".substr($resstr, 1)."}";
    //$a=0;
    //echo ($resstr);
}




function isExist($lin)
{
    global $mysqli;
    $retv=array();
    $sq="select id from clients where phone= '".$lin."'";
    // echo($sq);
    $rs=$mysqli->query($sq);
    $uid=0;

    if ($rs->num_rows>0) {
        //$rm = $rs->fetch_assoc();
        $uid = 1;//$rm['id'];
    }
    $retv['result']=$uid;
    echo(json_encode($retv));

}




function sendToServer()
{
    
    //  echo('efwefwf2');
    global $mysqli;
    $inputfile= file_get_contents('php://input');

    //echo ($inputfile);
    $input= json_decode( $inputfile, TRUE ); //convert JSON into array
    //mail("zzeeee@gmail.com","dat",$inputfile." twst".$input['uphone']);


    //echo("!".$input);
    //echo (var_dump($input));
    //echo ('test567890op'.$inputfile."2we2w".$input);
    //$ordernum=$input->{'ordernum'};
    //  $rt=var_dump($input);
    //  if ($ordernum=="")$ordernum=500;
    //echo("WFDWEDE");
    //die();
    //ini_set('display_errors','On');
    //error_reporting('E_ALL');
    //echo ($_SERVER['REQUEST_METHOD'].count($_POST));


    //$ordernum=$_POST['ordernum'];
    $ordernum=$input['ordernum'];
    $uphone=$input['uphone'];
    $uid=$input['uid'];
    $createuser=$input['createuser'];

    //$uphone=$_POST['uphone'];
    //echo("WFDWEDE".$uphone."rwfw!!!!!!!!!!!!!".$ordernum."++");

//    $uphone='89161243241';
    $sq="select * from clients where phone='".$uphone."'";
    if ($uid!="") $sq='select * from clients where id='.$uid;
    $res=$mysqli->query($sq);
    $suid=0;

    //echo ($res);
    if ($res && $res->num_rows>0) { $rm=$res->fetch_array();
//    echo ();
        $suid=$rm['id'];
    }
    else {
        $sq="insert into clients(regdate, phone) values(now(),'".$uphone."')";
        $mysqli->query($sq);
        $suid=$mysqli->insert_id;
        newClient($suid, $uphone);

//        echo ('nre!!!');

    }

    //echo ($createuser);

    if ($createuser=="1") {
        newClient($suid, $uphone);
   //echo ($suid." ");
     //   echo ($uphone);

    }
    //echo ($inputfile." ".$ordernum);
    $resstr='';

    if ($ordernum>0)
    {
        //    echo('!!!1!!!');
        $priceoforder=0;
        $sq="insert into orders(uid)values(".$suid.")";
        $mysqli->query($sq);
        $orderid=$mysqli->insert_id;

        for($i=0;$i<=$ordernum;$i++)
        {
            /*
         $fio=$_POST['fio-l_'.$i];
         $phone=$_POST['phones-l_'.$i];
         $passport=$_POST['fio-p_'.$i];
         $tourid=$_POST['tourid_'.$i];
         $tourdate=$_POST['tourdate_'.$i];
         $appindex=$_POST['index_'.$i];
         $options=$_POST['options_'.$i];
         $totalprice=$_POST['totalprice_'.$i];*/

            $fio=$input['fio-l_'.$i];
            $phone=$input['phones-l_'.$i];
            $passport=$input['fio-p_'.$i];
            $tourid=$input['tourid_'.$i];
            $tourdate=$input['tourdate_'.$i];
            $appindex=$input['index_'.$i];
            $options=$input['options_'.$i];
            $totalprice=$input['totalprice_'.$i];

            if ($totalprice=="") $totalprice=0;


            $priceoforder=$priceoforder.$totalprice;

            $dealerid=0; //ПОКА(!)
            $comment="";



            $sq="insert into u_reserves (reservedate, orderid, price, fio, phone, passport, turid, turdate, comment, uid, dealerid)values(now(),".$orderid.",".$totalprice.", '".$fio."','".$phone."','".$passport."','".$tourid."','".$tourdate."','".$comment."', ".$suid.",".$dealerid.")";
            //echo($sq);
            /*  localStorage.setItem("fio-l_"+ordernum, fiol);                localStorage.setItem("fio-p_"+ordernum, fiop);                localStorage.setItem("phones-l_"+ordernum, phonesl);
                localStorage.setItem("tourid_"+ordernum, tourid);               localStorage.setItem("tourdate_"+ordernum, tdate);
                //localStorage.setItem("opt_"+ordernum, addservices);                localStorage.setItem("options_"+ordernum, checkedservices);
                console.log(totalprice);                localStorage.setItem("totalprice_"+ordernum, totalprice);
             */
            //echo ($sq.$i."test");

            $res = $mysqli->query($sq);
//            if (!$res) {echo ($sq." ".$mysqli->error);}

            if (!$res) {
                echo($mysqli->error);
            } else {
                $ok = $mysqli->insert_id;
                //echo ('swww');




                $arr = explode(",", $options);
                if ($appindex!="") $resstr = $resstr . ',"' . $appindex . '":' . $ok;
                //Добавляем информацию о доп. опциях заказа
                for ($q = 0; $q < count($arr); $q++) {
                    $sql1 = 'insert into add_u_reserves(reserve_id,service_id, value) values(' . $ok . ',' . $arr[$q] . ',1)';
                    $rm = $mysqli->query($sql1);
                    //   echo ($q.$sql1);

                }


            }

        }




        //Выставляем счет:
        $sq='insert into bills(orderid, sum, status) values('.$orderid.",".$priceoforder." ,1)";
        $mysqli->query($sq);
        $bid=$mysqli->insert_id;

        //Формируем ответ: уид пользователя
        $resstr=$resstr.',"uid":'.$suid;
        $resstr=$resstr.',"orderid":'.$orderid;
        $resstr=$resstr.',"bid":'.$bid;



    }


    $resstr="{".substr($resstr, 1)."}";
    $a=0;
    echo ($resstr);
}





function showTour()
{
    global $mysqli;
    $sq='select * from tours where id='.$_GET['tournumber'];

    $res=$mysqli->query($sq);
    //$rs=$res->fetch_assoc();

    $allres=array();
    while($rs = $res->fetch_assoc()) {
        $result = array();
        $result['id'] = $rs['id'];
        $result['title'] = $rs['title'];
        $result['description'] = $rs['description'];
        $result['price'] = $rs['baseprice'];
        $result['mainfoto']=$rs['mainfoto'];
        if (checkMr() == "1") $result['price'] = $rs['price1'];
        if (checkAction() == "1") $result['price'] = $rs['price2'];

        if ($_GET['dealer'] == "1") $result['price'] = $rs['price3'];
        if ($_GET['inaction'] == "1") $result['price'] = $rs['price2'];
        array_push($allres,$result);

    }
    echo (json_encode($allres));
}


function checkAuth($login, $pwd)
{

    session_start();
    //dlog('new sio'.SID);
    global $mysqli;

    $resstr="error";
    $retv=array();
    if (strpos($login,"@")>0) $sq="select * from clients where logemail= '".$login."' and pwd='".$pwd."'";
    else $sq="select * from clients where phone= '".$login."' and pwd='".$pwd."'";
    // echo($sq);
    $rs=$mysqli->query($sq);
    $uid=0;

    if ($rs->num_rows>0) {
        $rm = $rs->fetch_assoc();
        $uid = $rm['id'];
        $retv['phone']=$rm['phone'];
        $retv['name']=$rm['name'];
        $retv['type']=$rm['type'];
        $retv['sid']=session_id();
        $resstr="ok".$rm['phone']." ".$rm['name'];
        //   $retv['sid']=$_SESSION['id'];
        $_SESSION['uid']=$uid;
        $_SESSION['phone']=$rm['phone'];
        $_SESSION['type']=$rm['type'];

        $sq='update clients set lastenterdate=now() where id='.$uid;
        $mysqli->query($sq);
    }
    else {$resstr="Пользователь не найден";}
    $retv['uid']=$uid;



    mail("zzeeee@gmail.com","checkauth".$login." ".$pwd,$resstr);

    echo(json_encode($retv));
    /*
        $rtest['uid']=123;
        $rtest['phone']='89161234567';
        $rtest['name']='Вася';
        echo(json_encode($rtest));
    */

}


function dod()
{

    global $mysqli;
    $sq='select id from clients where bulknew=0 and pwd="" limit 1000';
    echo ($sq);
    $rm=$mysqli->query($sq);
    echo($mysqli->num_rows);
    while($r=$rm->fetch_assoc())
    {
        resetPassword($r['id']);
        echo ($r['id']." ".$r['logemail']);


    }

}


function logout()
{

    setcookie(session_name(), session_id(), time()-60*60*24);
    // и уничтожаем сессию
    session_unset();
    session_destroy();

    echo("1");


}

function checkCoupon($code, $syst)
{
    global $mysqli;
    //echo ($code);
    $newlen=strlen($code)-strpos($code,"---")-1;
   // echo(strlen($code)." ".$newlen." ".substr($code,0,5) );
    $code=substr($code, 0, $newlen);
  //  echo ("<br />".$code);
    $sq="select * from u_reserves where replace(codes,'-','') like '".$code."%'";

    if ($syst!="") $sq=$sq." and sourcesyst='".$syst."'";
    //echo($sq." ");
    $rt=$mysqli->query($sq);
    $rm=$rt->num_rows;
    $res=$rm;
    echo(json_encode($res));

}


function sendRPassword($lin)
{
    global $mysqli;
    $retv=array();
    $lin=$mysqli->real_escape_string($lin);
    $uid=0;
    if ($lin!="") {
        $sq="select id,phone,pwd from clients where phone= '".$lin."'";
        // echo($sq);
        $rs=$mysqli->query($sq);

        if ($rs->num_rows>0) {$rm=$rs->fetch_assoc();
            $uid=$rm['id'];
            $phone=$rm['phone'];
            $pwd=$rm['pwd'];
            $ml=$rm['logemail'];
            if ($ml=="") $ml=$rm['email'];

            if ($pwd=="") $pwd=resetPassword($uid);
            $text='Ваш пароль:'.$pwd;
            sendSms($phone,$text);
            Mail($rm['email'],"Напоминание пароля", $text);
        }
    }

    $retv['result']=$uid;


    $sql="insert into retrieve_requests(line, detected)values('".$lin."',".$uid.")";

    $mysqli->query($sql);
    echo(json_encode($retv));
}


function showCTypes($tourid)
{

    global $mysqli;
    $res=array();
    $sq='select * from ctypes where tourid='.$tourid;
    $rt=$mysqli->query($sq);
    //echo ($sq);

    $i=0;

    if ($rt->num_rows>0) while($rm=$rt->fetch_assoc())
    {
     array_push($res, $rm);

    }else $res=0;

    echo(json_encode($res));
}



//echo ($_SERVER['REQUEST_METHOD']);

//echo ($_GET['action']);
switch($_GET['action']):
    case 'showaddservices': showAddServices(); break;
    case 'showtour': showTour(); break;
    case 'sendtoserver2': sendToServer2(); break;
    case 'sendtoserver': sendToServer(); break;
    case 'showures': showUserReserves($_GET['uid'], $_GET['type'], $_GET['tourid']); break;
    case 'logout': logout();break;
    case 'checkcoupon': checkCoupon($_GET['code'], $_GET['system']);break;
    case 'retrieve': sendRPassword($_GET['line']);break;
    case 'showctype': showCTypes($_GET['tourid']); break;
    case 'isexist': isExist($_GET['line']);break;
    case 'register': doReg($_GET['login'],$_GET['password'],$_GET['email']);break;
    case 'checkauth': checkAuth($_GET['p1'], $_GET['p2']); break;
    case 'do': dod();break;


endswitch;


?>

