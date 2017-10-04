<?php

if(!defined("IN_ADMIN")) die;
//$mysqli = new mysqli("localhost", "zzeeee_tours", "cZ6AIhnV", "zzeeee_tours");
global $dealerid;
global $usertype;
global $companyinfo;
global $companyname;
ini_set('display_errors','On');
error_reporting('E_ALL');


// outputs image directly into browser, as PNG stream

//require "mpdf-development/mpdf.php";
require "commlib.php";


$addline="";
if (checkMr()=="1") {  $addline="&molodrus=1"; }
if ($_GET['dealer']!="") {$addline="&dealer=1";}
if (checkAction()==1) {$addline="&actionprice=1";}


//session_start();
//

function showTourLine($id, $rl, $dealer)
{
    global $mysqli;
    $sq = "SELECT *, month(now()) as cm FROM `alltours_months` WHERE id=" . $id;
    $sq="select *, id, day(date) as day, year(date) as year, month(date) as month, month(now()) as cm from dates where tourid=".$id;
//    echo ($sq);
    //dlog($sq);
    $rtlist = $mysqli->query($sq);
    $col1="";
    $col2="";
    $col3="";
    //$resline="<table>";
$dl="";
    $rlprice=$rl['baseprice'];
    $addline="";
    //dlog(checkMr()."checkmr");
    if (checkMr()==1) { $rlprice=$rl['price1']; $addline="&molodrus=1"; }
    if (checkAction()==1) { $rlprice=$rl['price2']; $addline="&actionprice=1"; }
    if ($_GET['dealer']!="") {$rlprice=$rl['price3'];$addline="&dealer=1";}

    if ($dealer=="1") $dl="&dealer=yes";

    $resline="<div class='row'>";
    while ($rtl = $rtlist->fetch_assoc()) {

        $currmonth=$rtl['cm'];
        if ($dealer<>"1")
        $col='<a href="index.php?action=showatour'.$dl.$addline.'&tournumber='.$rtl['tourid']."&tourdate=".$rtl['id'].'">'.$rtl["day"]."</a><br />";
        else
            $col='<a href="agency.php?action=reserve&dateid='.$rtl['id'].'&tournumber='.$rtl['tourid'].'">'.$rtl["day"]."</a><br />";
        if ($rtl['month']==$currmonth) {
            $col1 = $col1 . $col;
        }
        if ($rtl['month']==$currmonth+1) {$col2=$col2.$col;}
        if ($rtl['month']==$currmonth+2) {$col3=$col3.$col;}

    }
    $resline=$resline."<tr><td><a href='index.php?action=showatour".$addline.$dl."&tournumber=".$id."'><h2>".$rl['title']."</h2></a>".$rl['main_descr']."</td>"."<td style='vertical-align:center' valign=center><h3><br />".$rlprice."</h3></td><td valign=center style='vertical-align:center'><h3><br />".$rl['blength']."</h3></td><td valign='bottom'><h3><br />".$col1."</h3></td><td><h3><br />".$col2."</h3></td><td><h3><br />".$col3."</h3></td></tr>";
    $resline=$resline."</div>";
    echo($resline);

}

function mName($id)
{
    switch ($id):
        case 0: return "Январь";
        case 1: return "Февраль";
        case 2: return "Март";
        case 3: return "Апрель";
        case 4: return "Май";
        case 5: return "Июнь";
        case 6: return "Июль";
        case 7: return "Август";
        case 8: return "Сентябрь";
        case 9: return "Октябрь";
        case 10: return "Ноябрь";
        case 11: return "Декабрь";
    endswitch;
}



function getToursList()
{
    global $mysqli;
    $sq="select distinct(tourid) as tid from dates where limitperagency>0 and id not in (select tid from stops) and date>now()";
    $rq=$mysqli->query($sq);
$result=array();

    if ($rq->num_rows>0) {
        $i=0;
        while ($row = $rq->fetch_assoc())
         {
         $result[$i]=$row['tid'];
            $i++;
         }
        }

return $result;
        }




function showReserve($id, $typ)//БРОНИРОВАНИЕ
{
    dlog($typ."!!!!");
    global $mysqli;
    global $companyinfo;
    global $companyname;
    global $dealerid;
    global $usertype;

    $rs=getTourDataById($id);


    ?>
    <span class="col-md-6" >
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Бронирование</h3>
                </div> <?
                //                echo ($usertype);
                if (!$_GET['nomenu']) {
                ?>
                <div class="panel-body" style="font-size:18"><h3>Тур: <a
                            href="index.php?action=showatour&tournumber=<? echo($rs['id']); ?>"><? echo($rs['title']); ?></a></h3><? echo($rs['main_descr']); ?>
                    <br/>
                    <form id="doreserve" action="reserve.php?action=add&do=1" method="POST" enctype="multipart/form-data" role="form">
                        <input type="hidden" name="nomenu" value="1"/>
                        <input type="hidden" name="tourid" value="<? echo($id); ?>"/>
                        <input type="hidden" name="molodrus" value="<?echo(checkMr()) ;?>" />
                        <input type="hidden" name="dealerid" value="<? echo($dealerid); ?>"/>
                        <input type="hidden" name="actionprice" value="<?echo(checkAction()) ;?>"/>

                        <div class="form-group" >
                            <?
                            $sq = "SELECT id,day(date) as day, month(date) as month, year(date) as year FROM `dates` where  date>now() and tourid=" . $id. " order by date desc";
                            $rmq = $mysqli->query($sq);
                            if ($rmq->num_rows > 1) {
                                ?>
                                <label for="dat">Выберите дату тура :</label>
                                <?

                                while ($rmdate = $rmq->fetch_assoc()) {
                                    echo('<br /><input  required type="radio" name="tourdate" value="'.$rmdate['id'].'"/>'. $rmdate['day'] . "." . $rmdate['month'] . "." . $rmdate['year']);
                                }

                            } else {
                                $rmdate = $rmq->fetch_assoc();
                                echo("Дата:".$rmdate['day'] . "." . $rmdate['month'] . "." . $rmdate['year']);
                                echo('<input type="hidden" name=tourdate value="' . $rmdate['id'] . '" />');
                            }
                            }

                            ?>
                        </div>
                        <?php

                        showBUser($id);
                        ?><h5>Цена (за одного человека*): <span id="setprice"><?
                                $bprice=$rs['baseprice'];
                                if (checkMr()==1) $bprice=$rs['price1'];
                                if (checkAction()==1) $bprice=$rs['price2'];
                                if ($_GET['dealer']!="") $bprice=$rs['price3'];
                                echo($bprice);
                                ?></span><br /><span>Цена опций1:</span><span id="extprice"></span></h5><br />
                        <span>Итого: </span><span id="totalprice"></span><br />
                        <?php


                        ?>
                        <span   id="addall"  class="btn btn-primary" role="button">Забронировать!</span>
                        <span   id="addbutton" class="btn btn-primary" role="button">Добавить еще участника поездки</span>

                        <!-- <input  id="addbutton" type="submit"  class="btn btn-default" value="Добавить"/>-->

                    </form></div></div>
        </span><span class="col-md-6" id="allnewreserves" style="visibility:visible">
            <div class="panel panel-primary">
                <div class="panel-heading">Мои неподтвержденные брони</div>
                <div class="panel-body"><span id="reservespace">Здесь будут показаны редактируемые вами брони</span></div>

            </div>

         </span>
    <span class="col-md-6" id="cserverspace" style="visibility:hidd" >
            <div class="panel panel-primary">
                <div class="panel-heading">Мои подтвержденные брони </div>
                <div class="panel-body"><span id="serverspace">Здесь будут показаны редактируемые вами брони</span></div>

            </div>

         </span>
    </div>
    <?
}




function showBron($id)
{

global $mysqli;
?>

                                <h1>Бронирование</h1>
                                Даты отправлений:<?
                                $sq = "SELECT id,day(date) as day, month(date) as month, year(date) as year FROM `dates` where  date>now() and year(date)=2016 and tourid=" . $id;
                                $rmq = $mysqli->query($sq);
                                if ($rmq->num_rows > 1) {
                                    echo('<select name="tourdate">');
                                    while ($rmdate = $rmq->fetch_assoc()) {
                                        echo("<option value='" . $rmdate['id'] . "'>" . $rmdate['day'] . "." . $rmdate['month'] . "." . $rmdate['year'] . "</option>");
                                    }
                                    echo('</select>');
                                } else {
                                    $rmdate = $rmq->fetch_assoc();
                                    echo($rmdate['day'] . "." . $rmdate['month'] . "." . $rmdate['year']);

                                }


}



function getClientData($id)
{
    global $mysqli;
    $sq='select * from clients where id='.$id;
//echo ($sq);
    $r=$mysqli->query($sq);
    if ($r)  {
        $m=$r->fetch_assoc();

        //      echo $m['name'].$m['phone'];
        return $m;
    }

    return false;
}

function getReserveData($lid)
{
    global $mysqli;

    $sq='select * from u_reserves where id='.$lid;

    $res=$mysqli->query($sq);
    $rm=$res->fetch_array();
    return $rm;

}


function getMyReserveData($lid)
{
    global $mysqli;

    $sq='select * from my_reserves where id='.$lid;

    $res=$mysqli->query($sq);
    $rm=$res->fetch_array();
    return $rm;

}






function dlog($text)
{
    echo('<script type="text/javascript">console.log("'.$text.'")</script>');
}


function showTourPlaces($id, $type)
{

global $mysqli;
 $sql="select * from places where id in (select placeid from tours_places where tourid=".$id.")and type in (1,2)";
 $qres=$mysqli->query($sql);
if ($type<>3)
           {
                if ($qres) {
                echo ('<ul class="list-group">');
                echo ('<li class="list-group-item"><b>Места</b>: <br />');
                    while ($qr = $qres->fetch_array()) {
                        echo($qr['name'] . "<br/>");
                    }
                echo ('</li>');
                echo ('</ul>');
                }
               $sq2="select * from places where id in (select placeid from tours_places where tourid=".$id.")and type in (4,5) ";
               $sq3="select * from photos where pid in (select id from places where id in (select placeid from tours_places where tourid=".$id.") and type in (4,5) )";


               dlog($sq2);
               $qres2=$mysqli->query($sq2);
               $qres3=$mysqli->query($sq3);
               if ($qres2) {
                   echo ('<ul class="list-group">');
                   echo ('<li class="list-group-item"><b>Святые и святыни</b>: <br />');
                   while ($qr = $qres2->fetch_array()) {
                       echo($qr['name'] . "<br/>");
                   }
                   echo("<br /><div class='row'>");
                   while ($qr3 = $qres3->fetch_array()) {
                       echo('<a title="'.$qr3['comment'].'" class="col-md-3 fancyimage thumbnail" data-fancybox-group="group" href="img/'.$qr3['name'] .'"><div class="caption"><p>'.$qr3['comment'].'</p></div><img valign=top src="img/'.$qr3['name'] .'" class="img-responsive"/></a><br/>');

                   }

                   echo ('</div></li>');
                   echo ('</ul>');
               }


                }
                else {echo ('&nbsp;');
                }

}




function firstSend($id)
{
    global $mysqli;
    //$pwd=generate_password(6);
    $sq="update clients set invsent=1 where id=".$id;
    $mysqli->query($sq);
}


function addCheckUser($phone, $name)
{
global $mysqli;
$sq="select *, year(regdate) as year from clients where phone='".$phone."'";
//echo($sq);
    $rs=$mysqli->query($sq);
//$ress=0;
$resss=0;
    if ($rs->num_rows==0) //Если новый клиент
    {
        $sq="insert into clients(regdate, name, phone)values(now(),'".$name."','".$phone."')"; $rm=$mysqli->query($sq);
$resss=$mysqli->insert_id;


$pwd=resetPassword($resss);;
    $sq="update clients set pwd='".$pwd."', invsent=1 where id=".$resss;
//echo($sq);
    $mysqli->query($sq);
    $smstext="Пароль для входа в ваш личный кабинет на сайте nov-rus.ru: ".$pwd." телефон:".$phone;
        echo ('<h1>Вам создан личный кабинет. Пароль отправлен по смс</h1>');
   //echo ($smstext);
     sendSms($phone, $smstext);
}
else
    {
    //    echo('no');
$rm=$rs->fetch_array();
$resss=$rm['id'];
$sent=($rm['invsent']);
        //echo($sent." ".$rm['year']);
    if ($sent==0 && $rm['year']==0)//Оповестить клиента о создании ему личного кабинета
    {
        echo('<h1>Вам создан личный кабинет. Пароль отправлен по смс</h1>');
        firstSend($resss);
    $pwd=resetPassword($resss);
    $smstext="Пароль для входа в ваш личный кабинет на сайте nov-rus.ru: ".$pwd." телефон:".$phone;
   // echo ($smstext);
    sendSms($phone, $smstext);}
}
//echo('2111'.$resss);
return $resss;

}


function getReserveFor($dat)
{
global $mysqli;
    $sq="select * from reserved where turdateid=".$dat;
//echo ($sq);
  //  return;
    $res=$mysqli->query($sq);

    if ($res->num_rows>0)  {$rm=$res->fetch_array();
        return $rm['num'];}
    return 0;

}


function getTourIdByDateId($datid)
{
global $mysqli;
    $sq="select tourid from dates where id=".$datid;
    $res=$mysqli->query($sq);
$tid=0;
    if ($res->num_rows>0)
    {
        $rs=$res->fetch_assoc();
       $tid=$rs['tourid'];
}

return $tid;
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

}

function getTourFreeSpace($did)
{
global $mysqli;
$sq="select * from spaces where did=".$did;
//echo ($sq);
$resnum=0;
$rs=$mysqli->query($sq);
if ($rs->num_rows>0)
{
$res=$rs->fetch_array();
$resnum=$res['ncount'];
if ($resnum=="") $resnum=$res['maxlim'];
//echo ($resnum."!!!");
if ($res['maxlim']==0) $resnum=0;
}

return $resnum;

}

function getTourFreeSpaceText($did)
{
//return "Места есть";
$num=getTourFreeSpace($did);
//echo ($did." ".$num);
if ($num>4) return ("Места есть");else if ($num>0) return "Мест мало. Обязателен звонок";
if ($num<=0) return "Мест нет. Активация купонов происходит в лист ожидания";
    
}


function getDateById($id)
{
global $mysqli;

$sq="SELECT id,day(date) as day, month(date) as month, year(date) as year FROM `dates` where  id=".$id;
    $res=$mysqli->query($sq);
    if ($mysqli->errno) {
        die('Select Error (' . $mysqli->errno . ') ' . $mysqli->error);
    }

    $rm=$res->fetch_array();

    return $rm['day'].".".$rm['month'].".".$rm['year'];

}

function getCompanyDataById($id)
{
global $mysqli;
global $companyinfo;
global $companyname;
global $dealerid;
global $usertype;

if ($id!="") {

    $sq = "select * from main_users where user_id=" . $id;
    $res=$mysqli->query($sq);
    if ($mysqli->errno) {
        die('Select Error (' . $mysqli->errno . ') ' . $mysqli->error);
    }
    $rm=$res->fetch_array();
    $companyinfo=$rm;
    if ($rm!="") {

        $companyname=$rm['company'];
        $dealerid=$rm['id'];
        $usertype=$rm['type'];
    }
    return $rm;
    /*else {
       // echo("нужна авторизация");
        $host  = $_SERVER['HTTP_HOST'];
        $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
        $extra = 'login.php';
        header("Location: http://$host$uri/$extra");
        exit;
    }*/
}


}


$companyinfo=getCompanyDataById($_SESSION['id']);


function showTop()
{
    showTopTop();
    showMiddle();
    showBody();
}


function showTopTop()
{
global $companyinfo;

?>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <!--


    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <!-- Bootstrap core CSS
    <link href="bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap theme
    <link href="bootstrap/css/bootstrap-theme.min.css" rel="stylesheet">
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug
    <link href="bootstrap/css/ie10-viewport-bug-workaround.css" rel="stylesheet">

    <!-- Custom styles for this template
    <link href="bootstrap/css/theme.css" rel="stylesheet">-->
    <script type="text/javascript" src="http://code.jquery.com/jquery-1.11.2.js "></script>
    <script type="text/javascript"src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
    <link rel="stylesheet" href="fancybox/jquery.fancybox.css" type="text/css" media="screen" />
    <!-- Подключение JS файла Fancybox -->
    <script type="text/javascript" src="fancybox/jquery.fancybox.pack.js"></script>




    <script type="text/javascript"src="js/notify.min.js"></script>
    <script type="text/javascript" >
    function getUrlVars() {
    var vars = {};
    var parts = window.location.href.replace(/[?&]+([^=&]+)=([^&]*)/gi, function(m,key,value) {
    vars[key] = value;
    });
    return vars;
    }
    </script>
<?
}
function showMiddle()
{
?>
    <?     require "nvlib.php"; ?>

    <script type="text/javascript">


        (function($){
            $(document).on('click', 'a[href^=#]', function () {
                $('html, body').animate({ scrollTop: $('a[name="'+this.hash.slice(1)+'"]').offset().top }, 2000 );
                return false;
            });
        })(jQuery);



        /*!
         * jCarouselLite - v1.1 - 2014-09-28
         * http://www.gmarwaha.com/jquery/jcarousellite/
         * Copyright (c) 2014 Ganeshji Marwaha
         * Licensed MIT (https://github.com/ganeshmax/jcarousellite/blob/master/LICENSE)
         */

        !function(a){a.jCarouselLite={version:"1.1"},a.fn.jCarouselLite=function(b){return b=a.extend({},a.fn.jCarouselLite.options,b||{}),this.each(function(){function c(a){return n||(clearTimeout(A),z=a,b.beforeStart&&b.beforeStart.call(this,i()),b.circular?j(a):k(a),m({start:function(){n=!0},done:function(){b.afterEnd&&b.afterEnd.call(this,i()),b.auto&&h(),n=!1}}),b.circular||l()),!1}function d(){if(n=!1,o=b.vertical?"top":"left",p=b.vertical?"height":"width",q=B.find(">ul"),r=q.find(">li"),x=r.size(),w=x<b.visible?x:b.visible,b.circular){var c=r.slice(x-w).clone(),d=r.slice(0,w).clone();q.prepend(c).append(d),b.start+=w}s=a("li",q),y=s.size(),z=b.start}function e(){B.css("visibility","visible"),s.css({overflow:"hidden","float":b.vertical?"none":"left"}),q.css({margin:"0",padding:"0",position:"relative","list-style":"none","z-index":"1"}),B.css({overflow:"hidden",position:"relative","z-index":"2",left:"0px"}),!b.circular&&b.btnPrev&&0==b.start&&a(b.btnPrev).addClass("disabled")}function f(){t=b.vertical?s.outerHeight(!0):s.outerWidth(!0),u=t*y,v=t*w,s.css({width:s.width(),height:s.height()}),q.css(p,u+"px").css(o,-(z*t)),B.css(p,v+"px")}function g(){b.btnPrev&&a(b.btnPrev).click(function(){return c(z-b.scroll)}),b.btnNext&&a(b.btnNext).click(function(){return c(z+b.scroll)}),b.btnGo&&a.each(b.btnGo,function(d,e){a(e).click(function(){return c(b.circular?w+d:d)})}),b.mouseWheel&&B.mousewheel&&B.mousewheel(function(a,d){return c(d>0?z-b.scroll:z+b.scroll)}),b.auto&&h()}function h(){A=setTimeout(function(){c(z+b.scroll)},b.auto)}function i(){return s.slice(z).slice(0,w)}function j(a){var c;a<=b.start-w-1?(c=a+x+b.scroll,q.css(o,-(c*t)+"px"),z=c-b.scroll):a>=y-w+1&&(c=a-x-b.scroll,q.css(o,-(c*t)+"px"),z=c+b.scroll)}function k(a){0>a?z=0:a>y-w&&(z=y-w)}function l(){a(b.btnPrev+","+b.btnNext).removeClass("disabled"),a(z-b.scroll<0&&b.btnPrev||z+b.scroll>y-w&&b.btnNext||[]).addClass("disabled")}function m(c){n=!0,q.animate("left"==o?{left:-(z*t)}:{top:-(z*t)},a.extend({duration:b.speed,easing:b.easing},c))}var n,o,p,q,r,s,t,u,v,w,x,y,z,A,B=a(this);d(),e(),f(),g()})},a.fn.jCarouselLite.options={btnPrev:null,btnNext:null,btnGo:null,mouseWheel:!1,auto:null,speed:200,easing:null,vertical:!1,circular:!0,visible:3,start:0,scroll:1,beforeStart:null,afterEnd:null}}(jQuery);



        function sendEMForm()
        {
            //alert('e');
            var r=document.getElementById("emailf");
            emailtosend=r.value;
            $.ajax({url:"subscribe.php?email="+emailtosend,
            success: function(data){alert(data);}
            });

        }



    </script>

    <!-- Yandex.Metrika counter -->
    <script type="text/javascript">
        (function (d, w, c) {
            (w[c] = w[c] || []).push(function() {
                try {
                    w.yaCounter38964480 = new Ya.Metrika({
                        id:38964480,
                        clickmap:true,
                        trackLinks:true,
                        accurateTrackBounce:true,
                        webvisor:true,
                        trackHash:true
                    });
                } catch(e) { }
            });

            var n = d.getElementsByTagName("script")[0],
                s = d.createElement("script"),
                f = function () { n.parentNode.insertBefore(s, n); };
            s.type = "text/javascript";
            s.async = true;
            s.src = "https://mc.yandex.ru/metrika/watch.js";

            if (w.opera == "[object Opera]") {
                d.addEventListener("DOMContentLoaded", f, false);
            } else { f(); }
        })(document, window, "yandex_metrika_callbacks");
    </script>
    <noscript><div><img src="https://mc.yandex.ru/watch/38964480" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
    <!-- /Yandex.Metrika counter -->
<?php
    echo ('</head><body>');
}


function saveBill($customerNum, $ok,  $phone,$email, $price, $comment )
{
    global $mysqli;
    $sq="insert into bills(uid, rid, phone, email, sum, comment) values(".$customerNum.",".$ok.",'".$phone."','".$email."',".$price.",'".$comment."')";
  //echo ($sq);
    $rs=$mysqli->query($sq);
    if ($rs) return "ok";  else return ('nok');
}

function showPayment($customerNum, $ok, $customerName, $phone,$email, $price, $comment )
{    ?>
    <form method="POST" action="https://money.yandex.ru/eshop.xml">
        <input type="hidden" name="shopId" value="66217" />
        <input type="hidden" name="scid" value="63082" />
        <input type=hidden name="customerNumber"  value="<? echo ($customerNum."---".$ok);?>" size="64">
        <input type=hidden name="sum" value="<? echo ($price);?>" size="64">
        <input type=hidden name="cps_phone" value="<? echo ($phone);?>" size="64">
        <input type=hidden name="custName" value="<? echo ($customerName);?>" size="43"><?php
        if ($email=="") {     ?>
            E-mail:<br>
            <input type=text name="custEmail" size="43"><br> <? } else echo ('<input type="hidden" name="custEmail" value="'.$email.'" />');

        ?>
        Содержание заказа:<br>
    <textarea rows="10" name="orderDetails" cols="34"><? echo ($comment);
        ?></textarea><br><br>

        Способ оплаты:<br><br>
        <input name="paymentType" value="PC" type="radio" checked="checked"/>Со счета в Яндекс.Деньгах (яндекс кошелек)<br/>
        <input name="paymentType" value="AC" type="radio" />С банковской карты<br/>
        <input name="paymentType" value="WQ" type="radio" />Qiwi<br/>
        <input name="paymentType" value="KV" type="radio" />КупиВкредит<br/>
        <input name="paymentType" value="GP" type="radio">Оплата по коду через терминал, включая Евросеть, Связной, Сбербанк <br>
        <input type=submit value="Оплатить через Яндекс.Кассу "><br>
        <!--
        EPS и PNG файлы яндекс.кошелька
        https://money.yandex.ru/partners/doc.xml?id=522991

        EPS и PNG других платежных методов
        https://money.yandex.ru/doc.xml?id=526421
        -->
    </form>
    <?
}
    function showBody(){

    global $addline;
?>
<span id="start" ></span>
<div class="container">

    <!-- The justified navigation menu is meant for single line per list item.
         Multiple lines will require custom code not provided by Bootstrap. -->
    <div class="row"><span class="col-md-5 col-xs-12" id="hdrt"><h5><span class="hidden-xs">Контактные телефоны: </span>8-499-390-18-08, 8-916-124-32-43.</h4></span><span class="col-md-5 hidden-xs" > Подписка на новости: <input type="text" id="emailf" name="email"/><button onclick="sendEMForm()">Подписаться</button></h5></span><span class="col-md-2 col-xs-12" id="toplogin"><a id='logbutton'>&nbsp;</a>&nbsp;&nbsp;<a id="registration" style="cursor:pointer"  onclick="showReg()">Регистрация&nbsp;</a><br class="hidden-xs"/><a href="agency.php">Агентствам</a></span></div>
    <? if (!$_GET['nomenu']) { ?>
        <div class="masthead">
            <nav class="navbar navbar-default">

                <div class="container">
                    <div class="navbar-header">

                        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                                data-target=".navbar-collapse">
                             <span class="icon-bar"></span>
                            <span class="icon-bar"></span>
                            <span class="icon-bar"></span>

                        </button>
                        <a class="navbar-brand" href="index.php?a=1<? echo ($addline); ?>"><img src="/imgi/novrus-new-30.png" /><?php


                            //echo ($_SERVER['SERVER_NAME']);
                            ?></a>
                    </div>
                    <div class="navbar-collapse collapse" id="collapse">
                        <ul class="nav navbar-nav">
                            <li class="dropdown">
                            <a href="index.php?type=palom&dir=0" class="dropdown-toggle" data-toggle="dropdown"
                               role="button" aria-haspopup="true" aria-expanded="false">Паломничество<span
                                    class="caret"></span></a>
                            <ul class="dropdown-menu">
                                <li class="dropdown-header">Туры из Москвы</li>
                                <li><a href="index.php?cat=1<? echo ($addline); ?>">Все туры</a></li>
                                <li><a href="index.php?place=7<? echo ($addline); ?>">Дивеево</a></li>
                                <li><a href="index.php?place=1<? echo ($addline); ?>">Оптина Пустынь</a></li>
                                <li><a href="index.php?place=8<? echo ($addline); ?>">Годеново</a></li>
                                <li><a href="index.php?place=9<? echo ($addline); ?>">Псково-Печерский монастырь</a></li>
                                <li><a href="index.php?place=6<? echo ($addline); ?>">Муром. К Петру и Февронии</a></li><!--
                            <li role="separator" class="divider"></li>
                            <li class="dropdown-header">В Москве</li>
                            <li><a href="index.php?type=palom&dir=1">Паломнические прогулки</a></li>
                            <li><a href="index.php?type=palom&dir=1">Прием паломников</a></li>
                            <li class="dropdown-header">В Санкт Петербурге</li>
                            <li><a href="index.php?type=palom&dir=1">Прием паломников</a></li>-->

                            </ul>

                            </li>

                            <li><a href="index.php?cat=2<? echo ($addline); ?>">Экскурсионные туры</a></li>
                       <li ><a href="index.php?cat=3">Прогулки</a></li>
                        <li ><a href="index.php?cat=5">Волонтерам</a></li>

                        <li ><a href="index.php?cat=4">Мастер-классы</a></li>


                            <!--<li><a href="agency.php">Агентствам</a></li>-->
                       <!--     <li><a href="aviasales.php?<? echo ($addline); ?>">Авиабилеты</a></li>-->

                            <li><a href="aboutus.php?<? echo ($addline); ?>">О проекте</a></li>
                            <li><a href="https://vk.com/mr_tours_education_volounteer"><img src="imgi/VK128.png" height="20"/></a></li>

                               <!--<li><a id='logbutton'>&nbsp;</a></li>
                            <script type="text/javascript">
                             if (typeof(Storage) !== "undefined") {
                                 var rt=localStorage.getItem("uid");
                                 console.log(rt);
                           //      if (!isNaN(rt) &&  parseInt(rt)!=0) document.write("*<? echo ($_SESSION['id'] );?> "); else document.write('!');
                             }


</script>-->


                            <?

                            //if ($_SESSION['id']== "") echo("<li><span id='logbutton1'><a id='loginbutton' href=\"login.php\">Вход</a></span></li>");
                            ?>
                            <?
                          //  if ($_SESSION['id'] != "") echo("<li><span id='logbutton1'><a id='logoutbutton' href=\"login.php?action=exit\">Выйти</a></span></li>");
                            ?>


                            <?php
                            if ($companyname != "") {
                                echo('<p class="navbar-text navbar-right">Вы вошли как ' . $companyname . ' </p>');

                            }
                            ?>


                        </ul>
                    </div><!--/.nav-collapse -->
                </div>
            </nav>


        </div> <span id="authwindow"  class="authwin" style="position:absolute; visibility:hidden; left:100px ">AAAA</span><? }
    }


    ?>