<?php

//echo ($_SESSION['id']."wfw".$_COOKIE['id']);
/*
 *
 *
 */
define("IN_ADMIN", TRUE);
require "sqli.php";
require "newconnect-head.php";

global $mysqli;


showTopTop();
?>
<script type="text/javascript" >

    function deletePos(id)
    {

        line="agencyb.php?dealerid=1&action=delete&lid="+id;
        $.ajax({url:line ,
            success: function(data){
                if (data!="") alert(data);
                loadReserves();}
        });


    }

<?
    if ($_GET['action']=="reserve"){
?>

    function addClient() {
        // alert('добавляю');
        var phone = document.getElementById('ephone').value;
//        alert(phone);
        //      if (phone!=null)        alert(phone.value);
        //alert()
        /*
         console.log('1');
         alert('добавляю2')*/
        var fio = document.getElementById('fio').value;
        var passport = document.getElementById('passport').value;
        var turid =<?  echo($_GET['tournumber']); ?>;
        var turdate =<?echo($_GET['dateid']); ?>;
        var add = document.getElementsByName('add[]');
        var lline = "";
        for (var i = 0; i < add.length; i++) {
            // access to individual element:
            var elem = add[i];
            var k = elem.value;
            var q = elem.checked;
            rq = String(q).toString();
            if (rq == "true") lline = lline + "," + k;
        }
        lline = String(lline).substr(1);
        //  alert(lline);

        var line = "agencyb.php?action=add&phone=" + phone + "&fio=" + fio + "&passport=" + passport + "&tourid=" + turid + "&tourdate=" + turdate + "&cline=" + lline + "&dealerid=1";//ПРОВЕРИТЬ dealerid

//alert(line);
        $.ajax({
            url: line,
            success: function (data) {
                if (data != "") alert(data);
                loadReserves();
            }
        });


    }
    <?

    }
    ?>


    function loadReserves()
    {
        $.getJSON( "agencyb.php?dealerid=1&action=all", function( data ) {

            //alert(r1['phone']);
            var rt=document.getElementById("AllReserves");
           //alert (rt.innerHTML);
            inhtml='<table class="table"><thead><tr><td>ФИО</td><td>Телефон</td><td>Паспорт</td><td></td><td>Базовая цена тура</td><td>Цена доп. услуг</td</tr>';
            do
            {
            r1=data.pop();
                if (r1!="")
            inhtml=inhtml+'<tr><td>'+r1['fio']+'</td><td>'+r1['phone']+'</td><td>'+r1['passport']+'</td><td>'+r1['turid']+'</td><td>'+r1['baseprice']+'</td><td>'+r1['aprice']+'</td><td>'+r1['reservedate']+'</td><td><a style="cursor:pointer" href="agency.php?action=editreserve&id='+r1['id']+'">просмотр</a></td><td><a style="cursor:pointer" onclick="deletePos('+r1['id']+')">удалить</a></td></tr>';}
            while (r1!="");
            inhtml+="</table>";
            rt.innerHTML=inhtml;


            /*
            var items = [];
            $.each( data, function( key, val ) {
                items.push( "<li id='" + key + "'>" + val + "</li>" );*/
            //alert(data);
            });


    }
    $( document ).ready(function() {
       loadReserves();
    });

</script>
<?
showMiddle();
showBody();

if ($companyname=="") {echo ('Данный раздел предназначен только для дилеров <br /><a href="login.php">Вход</a>'); die();}


function reserveTour($turid, $turdate)
{
    global $mysqli;
    echo ('<h1>Резервирование тура агентством</h1>');
    $tourdata=getTourDataById($turid);
    echo('<h2>Тур: '.$tourdata['title']."<br /></h2>");
    echo('<span id="AllReserves"></span>');
?>
<div class="panel panel-default">
    <div class="panel-body">
        <h3>Добавление клиентов</h3>


    <input type="hidden" name="bnum" value="1" id="bnum"/>
    <span id="spanlines">
        <span class="col-md-4"><div class="input-group">
        <span class="input-group-addon" id="b1">ФИО клиента</span>
        <input type="text" class="form-control" width="40" required id="fio" name="efio" aria-describedby="b1">
    </div><div class="input-group">
        <span class="input-group-addon" id="b2">Серия и номер паспорта</span>
        <input type="text" class="form-control" required value="" id="passport" name="epassport" aria-describedby="b2">
    </div><div class="input-group">
        <span class="input-group-addon" id="b2">Телефон</span>
        <input type="text" class="form-control" value="" required id="ephone" aria-describedby="b2">
    </div>
</span>      <span class="col-md-4">Цена тура на человека:
            <?php
            echo ($tourdata['baseprice']);
            echo ('<br /><br />');

            $sq = "select * from add_services where tourid=" . $turid;
            $rma = $mysqli->query($sq);
            while ($rms = $rma->fetch_assoc()) {
                echo('<input type="checkbox" name="add[]" value="' . $rms['id'] . '" />&nbsp;' . $rms['title'] . "-" . $rms['price'] . " руб. <br />");
            }

            ?>
            </span>
   <br /><br />
        <span class="col-md-12" >
<input type="submit" value="Добавить" onclick="addClient();" /></span>
</span></div>
</div>
    <?


}

function showReserves()
{
?><span id="AllReserves" ></span>
    <script type="text/javascript">loadReserves();</script>

    <?


}

function showDocs()
{
    ?>
Скачать договор
<?


}

function editReserve()
{

    echo('Просмотр и редактирование брони. ');

}

function showDealerTour()
{
global $mysqli;
$tours = getToursList();
$tc = count($tours);
//echo ($tc);
$i = 0;
$line = "";
while ($i < $tc) {
    if ($i <> 0) $line = $line . " , ";
    $line = $line . $tours[$i];


    $i++;
}
//echo ($line);
?>
<div class="panel panel-default">
    <!-- Default panel contents -->
    <div class="panel-heading">Расписание поездок из Москвы, доступных для бронирования АГЕНТСТВАМ</div>


    <!--    <tr><Td>Направление</Td><td>Цена</td><td>Дней</td></td><td width="100"><? echo(mName(date("m") - 1)); ?> </td><td width="100"><? echo(mName(date("m"))); ?></td><td width="100"><? echo(mName(date("m") + 1)); ?></td></tr>-->
    <table class="table table-striped" valign="top" border="0">
        <thead>
        <tr>
            <th></th>
            <th>Цена</th>
            <th>Дней</th>
            <th><? echo(mName(date("m") - 1)); ?></th>
            <th><? echo(mName(date("m"))); ?></th>
            <th><? echo(mName(date("m") + 1)); ?></th>
        </tr>
        </thead>
        <tbody>
        <?

        $sq = "select * from tours where visible=1 and id in (" . $line . ")";


        //echo($sq);
        try {
            $res_tour_list = $mysqli->query($sq);
//      echo("<table border='1'>");
            $i = 0;
            while ($res_tour_ln = $res_tour_list->fetch_assoc()) {

                // echo("<br />".$i."<br />");
                //$i++;
                //echo (getTourTitleById($res_tour_ln['id']));
                showTourLine($res_tour_ln['id'], getTourDataById($res_tour_ln['id']), 1);
//$res_tour_ln['title']
            }
            echo("</table>");

        } catch (Exception $e) {
            echo($e->getMessage());
        }
        }
        ?>

        <ul class="nav nav-pills">
            <li role="presentation" <? if ($_GET['action']=='showtours' or $_GET['action']=='') echo (' class="active" ')?>><a href="agency.php?action=showtours">Туры</a></li>
            <li role="presentation" <? if ($_GET['action']=='showreserves' or $_GET['action']=='editreserve') echo (' class="active" ')?>><a href="agency.php?action=showreserves">Брони</a></li>
            <li role="presentation" <? if ($_GET['action']=='showdocs') echo (' class="active" ')?>><a href="agency.php?action=showdocs">Документы</a></li>
        </ul>

        <?

        switch($_GET['action']):
        case "reserve": reserveTour($_GET['tournumber'], $_GET['dateid']); break;
            case "showtours":showDealerTour(); break;
            case "showreserves":showReserves(); break;
            case "editreserve":editReserve(); break;

            case "showdocs":showDocs(); break;

            case "": showDealerTour(); break;
        endswitch;


        ?>

</div>