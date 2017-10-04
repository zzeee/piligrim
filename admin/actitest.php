<?php
define("IN_ADMIN", TRUE);
require "sqli.php";
require "newconnect-head.php";

global $mysqli;

showTopTop();

?>
<script language="JavaScript">
    couponnum=1;
    var res=function()
    {
        couponnum++;
        $("#coupons").append('<div class="input-group" id="inputbut'+couponnum+'"><span class="input-group-addon" id="bq'+couponnum+'">Введите номер купона</span><input type="text" class="form-control" value="" name="client_code[]" aria-describedby="bq'+couponnum+'"></div>');
        $("#bnum").val(couponnum);
//        alert($("#bnum").val());

        //alert(couponnum);
    };

    var check=function()
    {


    };



//    $("#add").mousedown(res);

    $( document ).ready(function() {
        $("#addnewbutton").click(res);
        $("#submitbutton").click(check);
    });


</script>
<?
showMiddle();
showBody();



function activateCoupon()
{
    global $mysqli;
    $type = $_POST['system'];
    $dateid = $_POST['tour'];
    $tid = getTourIdByDateId($dateid);
    $tourdata = getTourDataById($tid);
    $needtopay="no";
    $uid = addCheckUser($_POST['client_phones'], $_POST['client_name']);
    $ok = 0;
    if ($type == 'gilmon') {
        $num=$_POST['bnum'];
        $pr=$tourdata['price4'];
        if ($pr!=0) {

            $total = $pr * $num;
            echo('<br /><h3>Для активации купонов системы Gilmon необходима оплата <br />' . $pr . '*' . $num . '=' . $total . " руб. </h3><br />Оплата возможна через интернет карточкой, электронными деньгами, а также в Евросети, Связном, Сбербанке, <a target='_blank' href='https://money.yandex.ru/doc.xml?id=526209'> полный список на сайте Яндекс.Кассы</a>. Если у вас сложности с оплатой - звоните 8 499 390 18 08");
            $comment = "Оплата тура \n " . $tourdata['title'] . " \n кол-во:" . $num . " \n дата:" . getDateById($dateid);
            saveBill($uid, $_POST['client_phones'], $_POST['email'], $total, $comment);
            showPayment($uid, $_POST['name'], $_POST['client_phones'], $_POST['email'], $total, $comment);
            $needtopay = "yes";

        }
        $sq = "insert into u_reserves(uid, fio,phone, email, codes,turdate, turid, reservedate, sourcesyst) values(" . $uid . ",'" . $_POST['client_name'] . "','" . $_POST['client_phones'] . "','".$_POST['email']."','" . implode($_POST['client_code']) . "'," . $_POST['tour'] . "," . $tid . ", now(),'gilmon')";
    }

    if ($type == 'skidkaboom') {
        $sq = "insert into u_reserves(uid, fio,phone,email, codes,turdate,turid, reservedate, sourcesyst) values(" . $uid . ",'" . $_POST['client_name'] . "','" . $_POST['client_phones'] ."','" . $_POST['email']. "','" . implode($_POST['client_code']) . "'," . $_POST['tour'] . "," . $tid . ", now(),'skidkaboom')";
    }


    if ($type == 'pk') {
        $sq = "insert into u_reserves(uid, fio,phone,email, codes,turdate,turid, reservedate, sourcesyst) values(" . $uid . ",'" . $_POST['client_name'] . "','" . $_POST['client_phones'] ."','" . $_POST['email']. "','" . implode($_POST['client_code']) . "'," . $_POST['tour'] . "," . $tid . ", now(),'pk')";
    }

    if ($type == 'mr') {
        $sq = "insert into u_reserves(uid, fio,phone,email, codes,turdate,turid, reservedate, sourcesyst) values(" . $uid . ",'" . $_POST['client_name'] . "','" . $_POST['client_phones'] ."','" . $_POST['email']. "',''," . $_POST['tour'] . "," . $tid . ", now(),'molrus')";

        $pr=$tourdata['price1'];//ЦЕНА для Молодой Руси в колонке 1(!!!!)
        if ($pr!=0) {

        $total=$pr;
        echo ('<br /><h3>Для бронирования места по цене Молодой Руси необходима оплата поездки<br />'.$total." руб. </h3><br />Оплата возможна через интернет карточкой, электронными деньгами, а также в евросети");
        $comment="Оплата тура \n ".$tourdata['title']." \n \n дата:".getDateById($dateid);
        saveBill($uid, $_POST['client_phones'], $_POST['email'],  $total, $comment);

        showPayment($uid, $_POST['name'], $_POST['client_phones'], $_POST['email'],  $total, $comment);
        $needtopay="yes";
        }

    }


    if ($type == 'biglion') {
        $sq = "insert into u_reserves(uid, fio,phone, email, codes,turdate,turid, reservedate, sourcesyst) values(" . $uid . ",'" . $_POST['client_name'] . "','" . $_POST['client_phones'] ."','" . $_POST['email']. "','" . implode($_POST['client_code']) . "----" . $_POST['activation'] . "'," . $_POST['tour'] . "," . $tid . ", now(),'biglion')";
    }

    if ($type == 'kupikupon') {
        $sq = "insert into u_reserves(uid, fio,phone,email, codes,turdate,turid, reservedate, sourcesyst) values(" . $uid . ",'" . $_POST['client_name'] . "','" . $_POST['client_phones'] ."','" . $_POST['email']. "','" . implode($_POST['client_code'] ). "----" . $_POST['activation'] . "'," . $_POST['tour'] . "," . $tid . ", now(),'kupikupon')";
    }

    if ($type == 'groupon') {
        $sq = "insert into u_reserves(uid, fio,phone,email,codes,turdate,turid, reservedate, sourcesyst) values(" . $uid . ",'" . $_POST['client_name'] . "','" . $_POST['client_phones'] ."','" . $_POST['email']. "','" . implode($_POST['client_code']) . "----" . $_POST['activation'] . "'," . $_POST['tour'] . "," . $tid . ", now(),'groupon')";
    }

//    echo ($sq."!!!");
   // if ($needtopay=="yes") echo('бронь будет подтверждена после оплаты счета');

    $mysqli->query($sq);
  //  if ($mysqli->insert_id != 0)
        $ok = $mysqli->insert_id;
        $mok = md5($ok);
            $smstext = "Бронь:" . $tourdata['title'] . ",дата:" . getDateById($dateid) . ",код:" . $ok . ", 84993901808";
          sendSms($_POST['client_phones'], $smstext);
            //echo ($smstext);
           
        echo('Тур '.($needtopay=="yes"?" предварительно забронирован, окончательное бронирование происходит после оплаты ":"успешно забронирован ").'(' . $ok . '). Код бронирования отправлен вам в виде sms-сообщения. В случае если вы не получили сообщение - свяжитесь с нами<br>Внимание за 2 дня до тура вам будет отправлено смс-оповещение с номером автобуса. <br />Если вы не получили смс - обязательно свяжитесь с нами <h3>8-499-390-18-08</h3>');
echo('<a href="/">Вернуться на главную страницу</a>');


}


function showAct($system)
{
    global $mysqli;
    ?>


    <div class="row">
        <div class="col-md-12 " >
            <form class="form col-md-12" method="POST" action="<? echo ($_SERVER['PHP_SELF']);?>?action=activate">
                <input type="hidden" name="bnum" value="1" id="bnum"/>
                <input type="hidden" name="system" value="<? echo ($system);?>" />

                <h3><? if ($system!="mr") echo ('Активация купона'); else echo ('Запись в поездку'); ?> </h3>

                <div class="input-group">
                    <span class="input-group-addon" id="b1">Как вас зовут, укажите ФИО</span>
                    <input type="text" class="form-control" width="40" name="client_name" aria-describedby="b1">
                </div>


                <div class="input-group">
                    <span class="input-group-addon" id="b2">Укажите номер телефона (с восьмеркой, например 89161234567)</span>
                    <input type="text" class="form-control" required value="" name="client_phones" aria-describedby="b2">
                </div>

                <div class="input-group">
                    <span class="input-group-addon" id="b2">Введите е-мейл (не обязательно, но желательно) </span>
                    <input type="text" class="form-control" value="" name="email" aria-describedby="b2">
                </div>

                <span  id="tourdate" />
                <div class="col-lg-12">
                    <?
                    $dsq="";
                    if ($_GET['tid']!="") $dsq=" and tourid=".$_GET['tid']." ";
                    $sq='select day(date) as day, month(date) as month,id,comment, realmaxlimit, tourid from dates where date>now() '.$dsq.' and tourid in (select id from tours where visible=1 and type in (1,2))order by date limit 10';
                    //echo ($sq);
                    $rm=$mysqli->query($sq);
                    while ($rtl = $rm->fetch_assoc()) {
                        //echo ('1');
                        $tid=$rtl['tourid'];
                        $tourdata=getTourDataById($tid);
                        ?>
                        <div class="input-group" >
                   <span class="input-group-addon"><? echo ($rtl['day']." ".showTextMonth($rtl['month'])); ?>
                       <input type="radio" required name="tour"  value="<? echo ($rtl['id']);?>" aria-label="" >
      </span><h4><? echo ($tourdata['title']." (".getTourFreeSpaceText($rtl['id']));?>)</h4>
                        </div>

                        <?


                    }



                    ?>

                </div>

                <div class="col-lg-12" id="coupons">
                    <?
                    if ($system<>"mr") {
                    ?>
                    <div class="input-group" id="inputbut">
                        <span class="input-group-addon" id="b3">Введите номер купона</span>
                        <input type="text" class="form-control" required  value="" name="client_code[]" aria-describedby="b3"><span  id="coupon/" />
                    </div>


                </div><? if ($system=="gilmon") {?><div class=""col-lg-12"><button  type="button" id="addnewbutton" class="btn btn-primary">Добавить еще купон</button></div> <? } ?>
                <?php
                }

                if ($system=="groupon" or $system=="biglion" or $system=="kupikupon" ) {

                    ?>
                    <div class="col-lg-12">
                        <div class="input-group">
                            <span class="input-group-addon" id="b3"><?
                                if ($system=="groupon") echo ("security code");
                                if ($system=="biglion") echo ("код бронирования");
                                if ($system=="kupikupon") echo ("код ");
                                ?></span>
                            <input type="text" class="form-control" value="" required  name="activation" aria-describedby="b3">
                        </div>
                    </div>



                    <?php
                }
                ?>

                <button type="submit" id="submitbutton" class="btn btn-info col-md-6"><?  if ($system!="mr") echo ("Активировать купон"); else echo ("Записаться!"); ?></button>
            </form>
        </div>

    </div>

    <?php

}

switch ($_GET['action']):
    case "showgilmon": showAct("gilmon"); break;
    case "showgroupon": showAct("groupon"); break;
    case "showbiglion": showAct("biglion"); break;
    case "showkupikupon": showAct("kupikupon"); break;
    case "showpk": showAct("pk"); break;
    case "showskidkaboom": showAct("skidkaboom"); break;
    case "showmr": showAct("mr"); break;
    case "activate": activateCoupon(); break;
endswitch;

?>

