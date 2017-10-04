<?php

//echo ($_SESSION['id']."wfw".$_COOKIE['id']);

define("IN_ADMIN", TRUE);
require "sqli.php";
require "newconnect-head.php";



global $mysqli;
if ($_GET['nomenu']=="") showTop();



function showTicket($nam)
{

}





function updateReserve()
{
    global $mysqli;
    echo ($_POST['tourid']);
    $sq="update reserves set client_name='".$_POST['client_name']."', client_phones='".$_POST['client_phones']."', client_comment='".$_POST['client_comment']."' where id=".$_POST['reserveid'];
    $res=$mysqli->query($sq);
    if ($res) echo ('ok'); else echo('nok');
    echo ('<a href="reserve.php?action=show">Вернуться к списку броней</a>');
//echo ($sq);
}

function makeEdit()
{
    global $mysqli;
    $id=$_GET['reserveid'];
    $sq="select * from reserves where id=".$id;
    $res=$mysqli->query($sq);
    $rm=$res->fetch_array();
//    echo($sq);
  //  echo ($rm['price']."!!!");
    $price=$rm['price'];
    ?>
    <div class="col-md-4  well">
        <form class="form col-md-12" method="POST" enctype="multipart/form-data" action="reserve.php?action=updatereserve">

            <h3>Редактирование брони</h3>
            <div class="input-group">
                <span class="input-group-addon" id="b1">ФИО     клиента</span>
                <input type="text" class="form-control" value="<? echo($rm['client_name']);?>" name="client_name" aria-describedby="b1">
            </div>


            <div class="input-group">
                <span class="input-group-addon" id="b2">Телефоны клиента</span>
                <input type="text" class="form-control" value="<? echo($rm['client_phones']);?>" name="client_phones" aria-describedby="b2">
            </div>

            <div class="input-group">
                <span class="input-group-addon" id="b3">Комментарий</span>
                <input type="text" class="form-control" value="<? echo($rm['client_comment']);?>" name="client_comment" aria-describedby="b3">
            </div>
            <input type="hidden" name="tourid" value="<? echo($rm['tourid']);?>" />
            <input type="hidden" name="reserveid" value="<? echo($id);?>" />


            <div class="input-group">
                <span class="input-group-addon" id="b11">Цена</span>
                <input type="text" class="form-control" value="<? echo($price);?>" name="bprice" aria-describedby="b11">
            </div>



            <button type="submit" style="margin-bottom: 15px;" class="btn btn-info col-md-6">Обновить</button>
        </form>
    </div>


    <?






}

function makeDelete()
{
    global $mysqli;
    $id=$_GET['reserveid'];
    global $companyinfo;

    $cinfo=getCompanyDataById($_SESSION['id']);
    $did=$_SESSION['id'];

    $sq='insert into delete_history (date, who, type, deletedid) values(now(),'.$did.',1,'.$id.')';
 //   echo ($sq);
    $sq2='delete from reserves where id='.$id;
//echo ($sq2);
    try
    {
        $r1 = $mysqli->query($sq);
        $a=$mysqli->affected_rows;
        $r2 = $mysqli->query($sq2);
        $b=$mysqli->affected_rows;
        //if ( && $r2) echo ('deleted');
        echo ('Запись удалена ('.$a.' '.$b.')<br /><a href="reserve.php?action=show">Вернуться к списку броней</a>');

    }
    catch (Exception $e) {
        echo ("err".$e->getMessage()); //выведет \\\"Exception message\\\"
    }






}

function showReserves()
{
    global $mysqli;
    global $dealerid;
    global $companyinfo;
    $sq="select * from reserves where dealerid=".$_SESSION['id']." order by reservedate desc ";
    //echo ($sq);
    ?>
    <h1>Мои бронирования</h1>
    <table class="table">
        <tr>
            <th>Дата бронирования</th>
        <th>Название тура</th>
        <th>Дата тура</th><th>Число билетов</th><th>ФИО клиентов</th><th>Сумма для оплаты клиентом</th><th>Действия</th></tr>

<?
    try
    {
        $res = $mysqli->query($sq);
        while($rm=$res->fetch_assoc())
        {

            $trr=getTourDataById($rm['tourid']);
            $sq = "SELECT id,day(date) as day, month(date) as month, year(date) as year FROM `dates` where  id=" . $rm['turdate']." limit 1";
           // echo $(sq);
            $rmq = $mysqli->query($sq);
            $rmdate = $rmq->fetch_assoc();
            $dat=$rmdate['day'] . "." . $rmdate['month'] . "." . $rmdate['year'] . "; ";

            echo("<Tr><td>".$rm['reservedate']."</td><td>".$trr['title']."</td><td>".$dat."</td><td>".$rm['num']."</td><td>".$rm['client_name']."</td><td>".$rm['price']."</td></td><td><a href='reserve.php?action=edit&reserveid=".$rm['id']."'>Редактировать</a>&nbsp;<a href='reserve.php?action=delete&reserveid=".$rm['id']."'>Аннулировать</a></td></Tr>");
        }
   //     echo($res);
    }
    catch (Exception $e) {
        echo ("5");
        echo ("err".$e->getMessage()); //выведет \\\"Exception message\\\"
    }
    echo('</table>');
}

function doAdd()
{
global $mysqli;

    global $dealerid;
    global $companyname;
    global $companyinfo;


    if ($_SERVER['REQUEST_METHOD']=="POST")
    {

        $typ= $_POST['typ'];
        $tid= $_POST['tourid'];

        //echo ($tid);
        $num=$_POST['number'];




        $rl=getTourDataById($tid);
        $prc=$rl['baseprice'];
        //   echo ($td);
        if ($_POST["molodrus"]!="" & $rl['price1']>0) $prc=$rl['price1'];

        $price=$prc*$num;
        $td=$_POST['tourdate'];
        if ($td=="")  {

            $sq="select id from dates where tourid=".$tid." limit 1";
            $rm=$mysqli->query($sq);
            if ($rm->num_rows>0) {$res=$rm->fetch_array(); $td=$res['id'];}
        }



        if ($typ==1)
        {
             echo("<div>");
            echo("<h1>Приобретение тура:".$rl['title']."</h1>");
            echo("<p><h4>Число билетов:".$num."</h4></p>");
            echo("<p><h4>Цена тура на человека:".$prc."</h4></p>");
            echo("<p><h4>Итого: ".$price."</h4></p>");

            $uid=addCheckUser($_POST['phones'], $_POST['name']);


            //Физическое лицо
            $sq="insert into u_reserves(uid, reservedate,fio, phone, comment, num , price,turdate, turid) values(".$uid.",now(),'".$_POST['name']."','".$_POST['phones']."','".$_POST['comment']."',".$num.",".$price.", ".$td. ", ".$tid.") ";
            //echo ($sq);
            $clidd="1234120";
            try {
                $res = $mysqli->query($sq);
                if ($res) {
                    $rq=$mysqli->insert_id;
                    $nam=rand(100,900);
                    echo ("<p><h4>Тур забронирован, номер брони: ".$nam."-".$rq."</h4></p>");

                    echo ("<p><h4>Вы можете внести предоплату за тур в размере:".$rl['price5']*$num." руб.</h4></p>");
                    ?>

<form method="POST" action="https://money.yandex.ru/eshop.xml">
    <input type="hidden" name="shopId" value="61769">
    <input type="hidden" name="scid" value="60111">
    <input type=hidden name="customerNumber"  value="<? echo ($nam."-".$rq);?>" size="64">
    <input type=hidden name="sum" value="<? echo ($rl['price5']*$num);?>" size="64">
    <input type=hidden name="cps_phone" value="<? echo ($_POST['phones']);?>" size="64">
    <input type=hidden name="custName" value="<? echo ($_POST['name']);?>" size="43"><br><br>
    E-mail:<br>
    <input type=text name="custEmail" required size="43"><br><br>
    Содержание заказа:<br>
    <textarea rows="10" name="orderDetails" cols="34"><? echo ("Оплата тура:".$rl['title'].",дата:".getDateById($td));
    ?></textarea><br><br>

    Способ оплаты:<br><br>
    <input name="paymentType" value="PC" type="radio" checked="checked"/>Со счета в Яндекс.Деньгах (яндекс кошелек)<br/>
    <input name="paymentType" value="AC" type="radio" />С банковской карты<br/>
    <input name="paymentType" value="WQ" type="radio" />Qiwi<br/>
    <input name="paymentType" value="KV" type="radio" />КупиВкредит<br/>
    <input name="paymentType" value="GP" type="radio">Оплата по коду через терминал<br>

    <input type=submit value="Оплатить"><br>

    <!--
    Ниже перечислены доступные формы оплаты.
    Перечисленные методы оплаты могут быть доступны в боевой среде после подписания Договора.
    Какие именно методы доступны для вашего Договора, вы можете уточнить у своего персонального менеджера.

    AB - Альфа-Клик
    AC - банковская карта
    GP - наличные через терминал
    MA - MasterPass
    MC - мобильная коммерция
    PB  -интернет-банк Промсвязьбанка
    PC - кошелек Яндекс.Денег
    SB - Сбербанк Онлайн
    WM - кошелек WebMoney
    WQ - Qiwi
    QP - Куппи.ру
    KV - КупиВкредит

    <input name="paymentType" value="GP" type="radio">Оплата по коду через терминал<br>
    <input name="paymentType" value="WM" type="radio">Оплата cо счета WebMoney<br>
    <input name="paymentType" value="AB" type="radio">Оплата через Альфа-Клик<br>
    <input name="paymentType" value="PB" type="radio">Оплата через Промсвязьбанк<br>
    <input name="paymentType" value="MA" type="radio">Оплата через MasterPass<br>
    <input name="paymentType" value="QW" type="radio">Оплата через Qiwi<br>
    <input name="paymentType" value="QP" type="radio">Куппи.ру<br>
    <input name="paymentType" value="KV" type="radio">КупиВкредит<br>

    Перечисление всех методов оплаты https://tech.yandex.ru/money/doc/payment-solution/reference/payment-type-codes-docpage/
    -->

    <!--
    EPS и PNG файлы яндекс.кошелька
    https://money.yandex.ru/partners/doc.xml?id=522991

    EPS и PNG других платежных методов
    https://money.yandex.ru/doc.xml?id=526421
    -->


</form>

        <?

                    echo ('<a style="font-size:30" href="showpdftour.php?bnum='.$rq.'&nam='.$nam.'">Напечатать билет</a>');
                    echo ('<br /><a style="font-size:30" href="/index.php">Вернуться на главную страницу &gt;&gt;</a>');
                    //echo ("<h2>Оплата</h2>");
}
                else echo("nok");
            }
            catch (Exception $e) {
                echo $e->getMessage(); //выведет \\\"Exception message\\\"
            }

        }
        if ($typ==2 &&  $_SESSION['id']!="")
        {

           //  ";
            echo ('Бронирование агентством:'.$companyname);
            $sq="insert into reserves(reservedate,dealerid, tourid, client_name, client_phones, client_comment,num, price,turdate) values(now(),".$_SESSION['id'].",".$_POST['tourid'].",'".$_POST['name']."','".$_POST['phones']."','".$_POST['comment']."',".$num.",".$price.", ".$td.")";
//echo ($sq);
            try {
                $res = $mysqli->query($sq);
                if ($res) {
                    $rq=$mysqli->insert_id;
                    $nam=rand(100,900);

                    echo ("<p>Тур забронирован, номер брони: A-".$nam."-".$rq."</p><a href='reserve.php?action=show'>Мои брони</a>");

                }
                else echo("nok");
            }
            catch (Exception $e) {
                echo $e->getMessage(); //выведет \\\"Exception message\\\"
            }



        }


    }

}

switch ($_GET['action']):
    case    "add"   : doAdd();break;
    case    "edit"  : makeEdit();break;
    case    "delete": makeDelete();break;
    case    "show"  : showReserves();break;
    case    "updatereserve"  : updateReserve(); break;
endswitch;
//showReserves()

?>