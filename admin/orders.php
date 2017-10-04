<?php
/**
 * Created by PhpStorm.
 * User: леново
 * Date: 28.03.2017
 * Time: 1:24
 */
define("IN_ADMIN", TRUE);
require "sqli.php";


function showList($id)
{
global $mysqli;
$sq='select * from u_reserves where turdate='.$id.' and deleted<>1 order by reservedate ';
//echo($sq);
$res=$mysqli->query($sq);
echo ('<table>');

    while ($rm=$res->fetch(PDO::FETCH_ASSOC))
    {
    $uid=$rm['uid'];
    // echo ($uid);
    //  $rms=getUserData($uid);
    // if ($rms!="")         $prepay=$rms['prepay'];else $prepay=0;
    $prepay=0;
    echo ('<tr><td class="fio">'.$rm['fio'].'<td class="fiok">-S:'.$rm['status'].' '.("тип купона:".$rm['ctype'])." ".($rm['payed']?" оплачен":"-").' '.'</td><td  >'.$rm['phone'].'</td><td  class="fiok">'.$rm['email'].'</td><td  class="fiok">'.$rm['comment'].'</td><td  class="fio">'.$rm['codes'].'</td><td  class="fio">'.$rm['reservedate'].'</td><td  class="fio">'.$rm['sourcesyst'].'</td><td  class="fiok">'.$rm['uid'].' '.$prepay.'</td><td  class="fiok"><a href="showres.php?action=fixreserve&lid='.$rm['id'].'">Подтвердить бронь</a></td><td  class="fiok"><a href="showres.php?action=fixpay&lid='.$rm['id'].'">Зафиксировать оплату</a></td><td  class="fiok"><a href="showres.php?action=resend&lid='.$rm['id'].'">Resend sms</a></td><td  class="fiok"><a href="showres.php?action=checkin&lid='.$rm['id'].'">Check-in</a></td><td  class="fiok"><a href="showres.php?action=blacklist&lid='.$rm['id'].'">BlackList</a></td><td  class="fiok"><a href="showres.php?action=deletek&lid='.$rm['id'].'">Отказ</a></td><td  class="fiok"><a target=_blank href="showpdftour.php?bnum='.$rm['id'].'">Посмотреть билет</a></td><td  class="fiok"><a href="showres.php?action=delete&lid='.$rm['id'].'">Удалить</a></td></td></tr>');

    }
    echo('</table><a href="showres.php">Список</a>');
showBottom($id);
}



function showBottom($did)
{

echo ('<br /><a href="showres.php?action=show&did='.$did.'">Экран поездки</a> <a href="showres.php?action=showchecked&did='.$did.'">Зарегистрированные</a> <a href="showres.php?action=shownotchecked&did='.$did.'">Отказники</a>');


}


function showAll()
{
    global $mysqli;


    $sq = 'select day(date) as day, month(date) as month,id,comment, realmaxlimit, tourid from dates where month(date)+1>=month(now()) and tourid in (select id from tours where visible=1 and type in (1,2))order by date limit 100';


    $sq="
select date, tours.title, timestampdiff(month, now(),date), day(date) as day, month(date) as month,dates.id as did, reserves.reserved, comment, realmaxlimit, tourid from dates join tours on
dates.tourid=tours.id
 left join  (select count(*) as reserved, turdate from u_reserves where deleted<>1 and turdate<>0 group by turdate
    ) as reserves on reserves.turdate=dates.id
where timestampdiff(month, now(),date)<20 and tours.type in (1,2)
order by date";
//echo ($sq);
    $rm = $mysqli->query($sq);
    $tourdata = "";
    echo('<div class="row">');
    while ($rtl = $rm->fetch(PDO::FETCH_ASSOC)) {
//echo ('1');
        echo('<div class="col-md-4">');
        $tid = $rtl['tourid'];
        //echo ($tid);

        //$tourdata = getTourDataById($tid);
        //$reserv = getReserveFor($rtl['id']);
        //if ($tid==)

        echo('<a href="orders.php?action=show&did=' . $rtl['did'] . '">' . $tid . ". " . $rtl['day'] . " " . ($rtl['month']) . ". " . $rtl['title'] . ", мест выделено:" . $rtl['realmaxlimit'] . ", из них забронировано:" . $rtl["reserved"] . "</a>");
        echo('</div>');
    }
}

switch ($_GET['action']):
    case "show": showList($_GET['did']); break;
    case "deletek": deletePos($_GET['lid'],1); break;
    case "delete": deletePos($_GET['lid']); break;
    case "checkin": checkIn($_GET['lid']); break;
    case "blacklist": blacklist($_GET['lid']); break;
    case "fixpay": fixpay($_GET['lid']); break;
    case "fixreserve": fixReserve($_GET['lid']); break;
    case "showchecked": showChecked($_GET['did']); break;
    case "resend" : resend($_GET['lid']);break;
    case "shownotchecked": showChecked($_GET['did'],2); break;


    default:
        showAll();
endswitch;

