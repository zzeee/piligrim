<?php
define("IN_ADMIN", TRUE);
require "sqli.php";
require "newconnect-head.php";

function makeReserve ($dealerid, $tourid, $dateid, $phone, $fio, $passport, $cline)
{
    global $mysqli;
    $sq="insert into u_reserves(reservedate, dealerid, turid, turdate, phone, fio, passport) values(now(), ".$dealerid.",".$tourid.",".$dateid.",'".$phone."','".$fio."','".$passport."')";

    $mysqli->query($sq);
    $ok=$mysqli->insert_id;
    if ($ok!="")
    {
        if ($cline!="")
        {
            $rt=explode(",", $cline);
            $sq='delete from add_u_reserves where reserve_id='.$ok;
            $mysqli->query($sq);


            for ($i=0;$i<=count($rt)-1;$i++)
            {

                $sq1="insert into add_u_reserves(reserve_id, service_id, value) values(".$ok.','.$rt[$i].',1)';
                $mysqli->query($sq1);


            }


        }


    } else echo('Произошла ошибка'.$sq);
  //  echo ($mysqli->status);
    


}

/**

 * НУЖНО РЕАЛИЗОВАТЬ
 * НА СЕРВЕРЕ JSON
 * Получить список броней дилера. Все+
 * Получить список броней дилера. Только по выбранному туру
 * Получить список броней дилера. Только по выбранной дате.
 * Получить подробную информацию по каждой брони
 * УДАЛИТЬ БРОНЬ С СЕРВЕРА+
 * отправить заявку на бронь (с учетом опций)

 * Created by PhpStorm.
 * User: zzeee
 * Date: 20.08.2016
 * Time: 0:37
 */

function deletePos($dealerid, $lid)
{
    global $mysqli;
    $sq='delete from u_reserves where id='.$lid;
    $mysqli->query($sq);
    //echo ($sq);

}

function showReserves($dealerid, $typ, $tourid, $dateid)
{
    global $mysqli;
    switch ($typ):

        case "all":    $sq='select id, reservedate, fio, phone,  passport,  turid, turdate, baseprice, aprice from all_reserve_info where dealerid='.$dealerid; break;
        case "tour":   $sq='select * from u_reserves where dealerid='.$dealerid.' and turid='.$tourid;break;
        case "date":   $sq='select * from u_reserves where dealerid='.$dealerid.' and turdate='.$dateid;break;
        default: $sq='select * from u_reserves  where dealerid='.$dealerid; break;
endswitch;
   //echo ($sq);
    $result=$mysqli->query($sq);

$myArray[]="";
    while($row = $result->fetch_array()) {
        //$tempArray = $row;
        array_push($myArray, $row);
    }
    echo json_encode($myArray, 1);


}

switch ($_GET['action']):

    case "all": showReserves($_GET['dealerid'], "all", $_GET['tourid'], $_GET['dateid']); break;
    case "add": makeReserve($_GET['dealerid'],$_GET['tourid'], $_GET['tourdate'], $_GET['phone'], $_GET['fio'], $_GET['passport'], $_GET['cline'] ); break;
    case "delete": deletePos ($_GET['dealerid'], $_GET['lid']);break;
                endswitch;



?>