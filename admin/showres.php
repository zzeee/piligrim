<?php
define("IN_ADMIN", TRUE);

require_once "../public_html/palomnichestvo/classes/db.php";
require_once "../public_html/palomnichestvo/classes/main.php";

/*
 * TODO
 * недоделано
 * отправка sms:
 * resend
 * подтверждение оплаты
 * 
 *
 * */

class zcell
{
//var $res;
    var $arrdata;

    function __construct($arr)
    {
        $this->arrdata = $arr;
    }

    function sl($arrname)
    {
        $rs = "";
        for ($i = 0; $i < count($arrname); $i++) {
            $rs = $rs . $this->sc($arrname[$i]);
        }
        return $rs;
    }

    function sc($name)
    {
        //if ($name=='ostatus') echo('!!!!1');
        if (isset($this->arrdata[$name])) {
            $rl = "<td class='$name'>" . $this->arrdata[$name] . "</td>";
            return $rl;
        } else return "<td>-</td>";
    }

    function cll($title, $hrefstr, $name)
    {

        if (isset($this->arrdata[$name])) {

            $rl = "<td class=aa'$name'><a href='".str_replace('%'.$name, $this->arrdata[$name],$hrefstr) ."'>".str_replace('%'.$name, $this->arrdata[$name],$title)."</a></td>";
            return $rl;
        } else return "<td>-</td>";

    }

    function cl($title, $action, $name)
    {
//var_dump($this->arrdata);
        if (isset($this->arrdata[$name])) {

//str_replace('%'.$name, $this->arrdata[$name],$hrefstr).'"'.
            $rl = "<td class=aa'$name'><a onclick=\"eventSc("."'$action','".$this->arrdata[$name]."')\" href='#'>".str_replace('%'.$name, $this->arrdata[$name],$title)."</a></td>";

            return $rl;
        } else return "<td>-</td>";

    }

}


function resend()
{

    $lid=$_GET["params"];
    $sq = 'select * from u_reserves where id=' . $lid;

    $sq="select * from u_reserves ur  
join  dates dt on ur.turdate=dt.id 
join tours tr on dt.tourid=tr.id;
where ur.id=$lid
";
    $res=db::query2($sq);

    $rm = $res->fetch(PDO::FETCH_ASSOC);
    $phone = $rm['phone'];
    $tid = $rm['turdate'];
    $turid = $rm['turid'];
    //  echo ($turid);
    //$tourdata = getTourDataById($turid);

    if ($phone != '') {
        $smstext = "Бронь:" . $rm['title'] . ",дата:" . $rm["date"] . ",код:" . $lid . ", 84993901808";
        //echo($smstext);
        main::sendSms($phone, $smstext);

    }
}


function showList($id)
{
    if ($id!=0) echo('<a href="showres.php?action=show&did=0">Брони всех поездок</a>');
    global $mysqli;
    $sq = 'select * from u_reserves where turdate=' . $id . ' and ifnull(deleted,0)<>1 order by reservedate ';

    $sq = "select * from u_reserves ur
join orders  on ur.orderid=orders.id
where turdate=$id and ifnull(ur.deleted,0)<>1 order by ur.reservedate;";


    $sq="select orders.id,clients.name as uname,ur.id as urrid, orders.status as ostatus,ur.turdate as urid, ur.turid as turid, ur.price as price, orders.phone as ophone, clients.phone as uphone, clients.regdate2, clients.logemail, clients.eluser, ur.fio, ur.phone, comment, orderid, reservedate,turdate, orders.status as ostatus, ur.status,orders.uid from u_reserves ur
join orders  on ur.orderid=orders.id
join clients on orders.uid=clients.id
where  ".($id>0?"turdate=$id and":""). " ifnull(ur.deleted,0)<>1 order by ur.reservedate desc;";
//echo($sq);
//echo($sq);
    $res = db::query2($sq);
    echo('<table border="2">');
    echo ('<td>Заказ</td><td>ФИО</td><td>Комментарий</td><td>Указал номер</td><td>Основной номер</td><td>Дата время резерва</td><td>Статус</td></td><td>Цена</td><td>DID</td><td>Тур</td>');

    while ($rm = $res->fetch(PDO::FETCH_ASSOC)) {
        $cl = new zcell($rm);
        $uid = $rm['uid'];
        // echo ($uid);
        //  $rms=getUserData($uid);
        // if ($rms!="")         $prepay=$rms['prepay'];else $prepay=0;
        $prepay = 0;
        echo("<tr>");

        echo($cl->sl(array("id", "fio","comment", "ophone","uphone","reservedate","ostatus","price","urid","turid")));
        echo($cl->cl("Подтвердить", "accept", "orderid"));
        echo($cl->cl("Resend", "resend","urid"));
        echo($cl->cl("Отказ", "delete_cl","urid"));
        echo($cl->cl("Уд", "delete","urrid"));
        echo($cl->cl("Ред", "edit","urid"));
        echo($cl->cl("Checkin", "checkin","urid"));
        echo($cl->cl("Отметить НЕТ", "checkmiss","urid"));
        echo($cl->cl("Оп-фикс", "fixpay","orderid"));

        echo($cl->cl("Профиль", "profile","uid"));
        echo('</tr>');
        //$rline=$cl->sc("fio").


        //echo ('<tr><td class="fio">'.$rm['fio'].'<td class="fiok">-S:'.$rm['status'].' '.("тип купона:".$rm['ctype'])." ".($rm['payed']?" оплачен":"-").' '.'</td><td  >'.$rm['phone'].'</td><td  class="fiok">'.$rm['email'].'</td><td  class="fiok">'.$rm['comment'].'</td><td  class="fio">'.$rm['codes'].'</td><td  class="fio">'.$rm['reservedate'].'</td><td  class="fio">'.$rm['sourcesyst'].'</td><td  class="fiok">'.$rm['uid'].' '.$prepay.'</td><td  class="fiok"><a href="showres.php?action=fixreserve&lid='.$rm['id'].'">Подтвердить бронь</a></td><td  class="fiok"><a href="showres.php?action=fixpay&lid='.$rm['id'].'">Зафиксировать оплату</a></td><td  class="fiok"><a href="showres.php?action=resend&lid='.$rm['id'].'">Resend sms</a></td><td  class="fiok"><a href="showres.php?action=checkin&lid='.$rm['id'].'">Check-in</a></td><td  class="fiok"><a href="showres.php?action=blacklist&lid='.$rm['id'].'">BlackList</a></td><td  class="fiok"><a href="showres.php?action=deletek&lid='.$rm['id'].'">Отказ</a></td><td  class="fiok"><a target=_blank href="showpdftour.php?bnum='.$rm['id'].'">Посмотреть билет</a></td><td  class="fiok"><a href="showres.php?action=delete&lid='.$rm['id'].'">Удалить</a></td></td></tr>');
    }
    echo('</table><a href="showres.php">Список</a>');
    ///showBottom($id);
}


function showBottom($did)
{
    echo('<br /><a href="showres.php?action=show&did=' . $did . '">Экран поездки</a> <a href="showres.php?action=showchecked&did=' . $did . '">Зарегистрированные</a> <a href="showres.php?action=shownotchecked&did=' . $did . '">Отказники</a>');
}

function showChecked($id, $type)
{
    global $mysqli;
    $sq = 'select * from u_reserves where turdate=' . $id . ' and checkedin=1 and deleted<>1 order by reservedate ';

    if ($type == 2) $sq = 'select * from u_reserves where turdate=' . $id . ' and checkedin<>1 and deleted<>1  order by reservedate';

    //echo($sq);
    $res = $mysqli->query($sq);
    echo('<table>');

    while ($rm = $res->fetch_assoc()) {
        //$uid=$rm['uid'];
        // echo ($uid);
        //$rms=getUserData($uid);
        //if ($rms!="")         $prepay=$rms['prepay'];else $prepay=0;
        $prepay = 0;
        echo('<tr><td><span id="fio">' . $rm['fio'] . '</span></td><td>' . $rm['phone'] . '</td><td>' . $rm['comment'] . '</td><td>' . $rm['sourcesyst'] . '</td><td><a href="showres.php?action=blacklist&lid=' . $rm['id'] . '">BlackList</a></td></tr>');

    }
    echo('</table><a href="showres.php">Список</a>');
    showBottom($id);
}


function blackList($lid)
{
    global $mysqli;
    $sq = 'select uid from u_reserves where id=' . $lid;
    //echo ($sq);
    $res = $mysqli->query($sq);
    $rs = $res->fetch_assoc();
    $sq = 'update clients set prepay=1 where id=' . $rs['uid'];
    $mysqli->query($sq);
    //echo ($sq);
    echo('<a href="showres.php">Список</a>');
}

function getUserData($uid)
{
    if ($uid != 0) {
        global $mysqli;
        $sq = 'select * from clients where id=' . $uid;
        //  echo($sq);
        $res = $mysqli->connect($sq);
        if ($res != "") {
            $rs = $res->fetch_assoc();
            return $rs;
        }
    }

}

function getReserveByPid($id)
{
    global $mysqli;
    // echo ('s');
    $sq = 'select * from u_reserves where id=' . $id;
    $res = $mysqli->query($sq);
    $rm = $res->fetch_assoc();
    return $rm;

}


function uDeletePos($type)
{
    $rt=$_GET["params"];

 if ($type==1)   $sq = "update u_reserves set deleted=1, deletereason=0 where id=$rt";
    if ($type==2)   $sq = "update u_reserves set deleted=1, deletereason=100 where id=$rt";
    $rs=db::query2($sq);
    echo (json_encode($rs));

}

function deletePos()
{
uDeletePos(1);
}

function deleteCl()
{
    uDeletePos(2);
}

function checkIn()
{
$lid=$_GET["params"];
     $sq = 'update u_reserves set checkedin=1 where id=' . $lid;//  echo ($sq);
    $rs=db::query2($sq);
    echo (json_encode($rs));
}

function checkMiss()
{
    $lid=$_GET["params"];
    $sq = 'update u_reserves set checkedin=999 where id=' . $lid;//  echo ($sq);
    $rs=db::query2($sq);
    echo (json_encode($rs));
}



function fixPay()
{
//echo ('w');
    $lid=$_GET["params"];

    $qt="select uid, prepaysum from orders where id=".$lid;
    $rty=db::query2($qt);
    if ($rty) $urid=$rty->fetchAll();
    {
        if (isset ($urid[0]) && isset ($urid[0]["uid"]))
        {
         $uid=$urid[0]["uid"];
         $psum=0;
         if (isset($urid[0]["prepaysum"])) $psum=$urid[0]["prepaysum"];


            $sq="insert into payments(userid, orderid, comment, sum) values($uid,$lid, 'Частичный платеж внесен вручную', $psum)";
            $rs=db::query2($sq);
            $sq = 'update orders set  status=3 where id=' . $lid;
            $rs2=db::query2($sq);


            if ($rs || $rs2) {
                /*
                $tid = $reserve['turid'];
                $tourdate = $reserve['turdate'];
                $tourdata = getTourDataById($tid);
                $smstext = "Поступила оплата брони:" . $tourdata['title'] . ",дата:" . getDateById($tourdate) . ",код:" . $lid . ", 84993901808";
                echo('Оплата подтверждена, клиенту выслано смс');
                //sendSms($reserve['phone'], $smstext);
                echo('<a href="javascript:history.back()">вернуться</a> <a href="showres.php">Общий список</a>');
                Сделать смс оповещение
                */
                echo (json_encode($rs)+json_encode($rs2));


            }
        }


    }
}


function fixReserve($lid)
{
//echo ('w');
    global $mysqli;

    $reserve = getMyReserveData($lid);
    //$client=getClientData($reserve['uid']);
    $sq = 'update u_reserves set  status=1 where id=' . $lid;

    $text = 'Подтверждение брони:' . $reserve['title'] . " на " . $reserve['date'] . ", ваш посадочный купон: http://www.nov-rus.ru/showpdftour.php?bnum=" . $lid;

    //   echo($sq);
    $res = $mysqli->query($sq);

    sendSms($reserve['phone'], $text);
    Mail($reserve['email'], 'Подтверждение брони', $text);

    // echo ($res."!");
    //if ($res)echo ('t1');
    // if (!$res)echo ('t2');

    echo('<a href="javascript:history.back()">вернуться</a> <a href="showres.php">Общий список</a>');
}


function acceptOrder()
{
 $rt=$_GET["params"];
$sq="update orders set status=2 where id=$rt";
$rs=db::query2($sq);
echo (json_encode($rs));
}

function showAll()
{
    global $mysqli;


    $sq = 'select day(date) as day, month(date) as month,id,comment, realmaxlimit, tourid from dates where month(date)+1>=month(now()) and tourid in (select id from tours where visible=1 and type in (1,2))order by date limit 100';

    $sq = "select day(dates.date) as day, tours.title, month(dates.date) as month,dates.id,comment, realmaxlimit, tourid from 
dates join tours on 
dates.tourid=tours.id 
where abs(timestampdiff(month, now(), dates.date))<3 and 
tours.visible=1 and tours.type in (1,2) order by dates.date;
";

    $sq = "select day(dates.date) as day, dates.id as turdate,  ifnull(ur.cn,0) as cn,  tours.title, month_name(month(dates.date)) as month, month(dates.date) as mn,dates.id,dates.comment, realmaxlimit, tourid from 
dates join tours on 
dates.tourid=tours.id 
left join (select count(*) as cn, turdate from u_reserves where ifnull(deleted,0)=0 and turdate>0 group by turdate)  ur on ur.turdate=dates.id
where abs(timestampdiff(month, now(), dates.date))<3 and 
tours.visible=1 and tours.type in (1,2) order by dates.date;";

    $rm = db::query2($sq);
//    echo ($sq);
    $tourdata = "";
    echo('<table>');
    echo('<tr><td>tid</td><td>Направление</td><Td>Дата</Td><td>Всего</td><td>Бронь</td></tr>');
    while ($rtl = $rm->fetch(PDO::FETCH_ASSOC)) {

        ?>
        <tr>
            <td><?= $rtl['tourid'] . "-" . $rtl['turdate'] ?></td>
            <td><a href="showres.php?action=show&did=<?= $rtl['turdate'] ?>"><?= $rtl['title'] ?></td>
            <td><?= $rtl['day'] . " " . $rtl['month'] ?></td>

            <td><?= $rtl['realmaxlimit'] ?></td>
            <td><?= $rtl['cn'] ?></td>
        </tr>


        <?
//echo ('1');
        $tid = $rtl['tourid'];
        //echo ($tid);

        //$tourdata = getTourDataById($tid);
        //$reserv = getReserveFor($rtl['id']);
        //if ($tid==)

        //echo('<a href="showres.php?action=show&did=' . $rtl['id'] . '">' . $tid . ". " . $rtl['day'] . " " . showTextMonth($rtl['month']) . ". " . $tourdata['title'] . ", мест выделено:" . $rtl['realmaxlimit'] . ", из них забронировано:" . $reserv . "</a>");
        //echo($rtl["id"]);
        //echo('</div>');
        //echo("<div class='col-md-4'>".$rtl['title']."</div>");
        //echo('</div>');
    }
}

if ($_GET["type"]!="hidden")
{
?>
<script>
    $(document).ready(function () {

        $("#showphones").click(function () {

            $(".fio").css("visibility", "hidden");
            $(".fiok").css("visibility", "hidden");

        });


        $("#showlist").click(function () {

            $(".fiok").css("visibility", "hidden");
        });


        $("#showall").click(function () {

            $(".fio").css("visibility", "visible");
            $(".fiok").css("visibility", "visible");

        });


    });
    function eventSc(action, params) {
        console.log(action);
        console.log(params);
//        alert(action);
        url = "showres.php?type=hidden&action=" + action + "&params=" + params;
        okFunc = function (res) {
            console.log(res);
            console.log('ok');
            alert("Ответ сервера:"+res);
            window.location.reload();

        }

        nokFunc = function (res) {
            console.log(res);
            console.log("nok");
            //alert('nok');

        };
        console.log(url);
        var rt = fetch(url, {
            method: "GET", credentials: "include",
            headers: {
                'Accept': 'application/json'
            }
        }).then(response => response.text())
            .then(json => okFunc(json), njson => nokFunc(njson));


    }


</script>
</head>
<body>
<a id="showphones">Только телефоны</a> &nbsp;<a id="showlist">Список на посадку</a> <a id="showall">Все</a>
<?
}
switch ($_GET['action']):
    case "all": showAll(); break;
    case "show":
        showList($_GET['did']);
        break;
    case "accept":  acceptOrder(); break;

    case "resend":
        resend();
        break;


    case "delete":
        deletePos();
        break;
    case "delete_cl":
        deleteCl();
        break;

    case "checkin":
        checkIn();
        break;
    case "checkmiss":
        checkmiss();
        break;
    case "blacklist":
        blacklist();
        break;
    case "fixpay":
        fixpay();
        break;
    case "fixreserve":
        fixReserve();
        break;

    default:
echo(json_encode(array("arr"=>"ok")));
endswitch;

if ($_GET["type"]!="hidden") {
?>
</div>
</body>
    </html>
    <?php
}