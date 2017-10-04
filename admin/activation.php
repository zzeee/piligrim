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


        //alert(errorPhone+" "+errorMail+" "+errorCode);
        if (errorPhone || errorMail || errorCode) {
            $(this).notify("Ошибка при заполнении формы. Проверьте:"+(errorPhone?"телефон;":"")+(errorMail?"мейл;":"")+(errorCode?"номер купона":""), "error");
            $(this).addClass("errtextbox");

            return false;
        }
        else return true;


    };



    //    $("#add").mousedown(res);

    $( document ).ready(function() {
        $("#addnewbutton").click(res);
        $("#submitbutton").click(check);



    });
 var errorMail=true;
    var errorPhone=true;
    var errorCode=true;
    var system="";

    $(document).on('focusout',"#r-email",function(){
        var value = $(this).val().trim();
        //   alert('echeck');
        console.log('echeck');
        /* Для этого используем регулярное выражение  */

        if (value.search(/^[-a-z0-9~!$%^&*_=+}{\'?]+(\.[-a-z0-9~!$%^&*_=+}{\'?]+)*@([a-z0-9_][-a-z0-9_]*(\.[-a-z0-9_]+)*\.(aero|arpa|biz|com|coop|edu|gov|info|int|mil|museum|name|net|org|pro|travel|mobi|[a-z][a-z])|([0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}))(:[0-9]{1,5})?$/i) != 0) {
            $(this).notify("E-mail введён не корректно", "error");
            $(this).addClass("errtextbox");
            errorMail = true;
        } else {
            $(this).removeClass("errtextbox");
            errorMail = false;
        }
    });

    $(document).on('focusout',"#r-phone",function(){
        var value = $(this).val().trim();
        //   alert('echeck');
        console.log('pcheck');
        /* Для этого используем регулярное выражение  */
        value=value.replace(/ /g,"");
        value=value.replace(/-/g,"");
        value=value.replace(/,/g,"");

        if (String(value).length!=11  && value.substr(1)!=8) {
            $(this).notify("Номер телефона должен состоять из 11 цифр и начинаться с 8", "error");
            $(this).addClass("errtextbox");
            errorPhone = true;
        } else {
            $(this).removeClass("errtextbox");
            errorPhone = false;
        }
    });

   // $(document).on('onsubmit',"#aform",function() {
   //         alert('ol2');
   // });

    $(document).on('focusout',"#r-code",function(){
        var value = $(this).val().trim();
        //   alert('echeck');
        console.log($("#ctypeid").val());
        console.log('ccheck');
        /* Для этого используем регулярное выражение  */

        lline="backdata.php?action=checkcoupon&code="+value+(system!=""?"&system="+system:"");
        console.log(lline);
            value=value.replace(/ /g,"");
        value=value.replace(/-/g,"");
        console.log(value);
        if (String(value).length!=14  && system=='biglion') {
            $("#r-code").notify("Некорректный номер купона", "error");
            $("#r-code").addClass("errtextbox");
            errorCode=true;


        }


            $.ajax({
            url:lline ,
            cache: false,
            type: "GET",
            async: true,
            processData: true,
            dataType: 'JSON',
            data: "",
            success: function (data) {
            console.log(data);
                if (data!=0)  {
                    $("#r-code").notify("Некорректный номер купона или он уже отмечен в нашей базе", "error");
                    $("#r-code").addClass("errtextbox");
                    errorCode=true;
                }else {
                    $("#r-code").removeClass("errtextbox");
                                    errorCode=false;
                }
            }
        });

        }
    );


    function showCTypes(id)
    {
        $("#ctype").html("");

        lline="backdata.php?action=showctype&tourid="+id;
        console.log(lline);
        res="";

        $.ajax({
            url:lline ,
            cache: false,
            type: "GET",
            async: true,
            processData: true,
            dataType: 'JSON',
            data: "",
            success: function (data) {
                if (data==0) res="";
               else {
                    $.each(data, function (key, val) {
                        id=val['id'];
                        value=val['name'];
                        res+='<option value="'+id+'" >'+value+'</option>';
                         //console.log(key+" "+val+" "+val['id']);
                        //console.log(res);
                        //dataarr=val;
                    });

                }
                if (res!="") {

                    //res+='<input type="text" class="form-control" required  id="r-code" name="client_code[]" >';



                    res='<span class="input-group-addon" id="b4">Тип купона:</span><select name=ctype id="ctypeid" class="form-control" reqired aria-describedby="b4">'+res+"</select>";
                    $("#ctype").html(res);
                    console.log(res);

                }

            }



            });}


</script>
<?
showMiddle();
showBody();



function activateCoupon()
{

    global $mysqli;
    $type = $_POST['system'];
    $dateid = $_POST['tour'];
    $ctype=$_POST['ctype'];

    if ($ctype=='undefined') $ctype=0;
    if ($ctype=="") $ctype=0;

//    echo ('1111');
    $control=$_GET['control'];

  //  echo ($control."!".$type." ".$dateid);
    //if ($control!=1) die();

    $tid = getTourIdByDateId($dateid);
    $tourdata = getTourDataById($tid);
    $needtopay="no";
    $uid = addCheckUser($_POST['client_phones'], $_POST['client_name']);
    $ok = 0;
    $num=$_POST['bnum'];
    $phones=$_POST['client_phones'];
    $phones=str_replace(",","", $phones);
    $phones=str_replace(" ","", $phones);
    $phones=str_replace("-","", $phones);


    if ($type == 'gilmon') {
        $pr=$tourdata['price4'];
        if ($pr!=0) {

            $total = $pr * $num;
            echo('<br /><h3>Для активации купонов системы Gilmon необходима оплата <br />' . $pr . '*' . $num . '=' . $total . " руб. </h3><br />Оплата возможна через интернет карточкой, электронными деньгами, а также в Евросети, Связном, Сбербанке, <a target='_blank' href='https://money.yandex.ru/doc.xml?id=526209'> полный список на сайте Яндекс.Кассы</a>. Если у вас сложности с оплатой - звоните 8 499 390 18 08");
      //      $comment = "Оплата тура \n " . $tourdata['title'] . " \n кол-во:" . $num . " \n дата:" . getDateById($dateid);
      //      saveBill($uid, $_POST['client_phones'], $_POST['email'], $total, $comment);
      //      showPayment($uid, $_POST['name'], $_POST['client_phones'], $_POST['email'], $total, $comment);
            $needtopay = "yes";

        }
        $sq = "insert into u_reserves(ctype, uid, fio,phone, email, codes,turdate, turid, reservedate, sourcesyst) values(" .$ctype.",".$uid . ",'" . $_POST['client_name'] . "','" . $phones. "','".$_POST['email']."','" . implode($_POST['client_code']) . "'," . $_POST['tour'] . "," . $tid . ", now(),'gilmon')";
    }

    if ($type == 'skidkaboom') {
        $sq = "insert into u_reserves(ctype,uid, fio,phone,email, codes,turdate,turid, reservedate, sourcesyst) values(".$ctype."," . $uid . ",'" . $_POST['client_name'] . "','" . $phones ."','" . $_POST['email']. "','" . implode($_POST['client_code']) . "'," . $_POST['tour'] . "," . $tid . ", now(),'skidkaboom')";
//$needtopay='yes';
    }


    if ($type == 'pk') {
        $sq = "insert into u_reserves(ctype,uid, fio,phone,email, codes,turdate,turid, reservedate, sourcesyst) values(".$ctype."," . $uid . ",'" . $_POST['client_name'] . "','" . $phones ."','" . $_POST['email']. "','" . implode($_POST['client_code']) . "'," . $_POST['tour'] . "," . $tid . ", now(),'pk')";
  //      $needtopay='yes';
    }

    if ($type == 'mr') {
        $sq = "insert into u_reserves(ctype,uid, fio,phone,email, codes,turdate,turid, reservedate, sourcesyst) values(".$ctype."," . $uid . ",'" . $_POST['client_name'] . "','" . $phones ."','" . $_POST['email']. "',''," . $_POST['tour'] . "," . $tid . ", now(),'molrus')";

        $pr=$tourdata['price1'];//ЦЕНА для Молодой Руси в колонке 1(!!!!)
        if ($pr!=0) {

            $total=$pr;
            echo ('<br /><h3>Для бронирования места по указанной цене необходима оплата поездки<br />'.$total." руб. </h3><br />Оплата возможна через интернет карточкой, электронными деньгами, а также в евросети");
       //     $comment="Оплата тура \n ".$tourdata['title']." \n \n дата:".getDateById($dateid);
       //     saveBill($uid, $_POST['client_phones'], $_POST['email'],  $total, $comment);

       //     showPayment($uid, $_POST['name'], $_POST['client_phones'], $_POST['email'],  $total, $comment);
            $needtopay="yes";
        }

    }

    if ($type == 'sres') {
        $sq = "insert into u_reserves(ctype,uid, fio,phone,email, codes,turdate,turid, reservedate, sourcesyst) values(".$ctype."," . $uid . ",'" . $_POST['client_name'] . "','" . $phones ."','" . $_POST['email']. "',''," . $_POST['tour'] . "," . $tid . ", now(),'sres')";

        $pr=$tourdata['price5'];//ЦЕНА БРОНИ (!!!!)
        if ($pr!=0) {

            $total=$pr;
            echo ('<br /><h3>Внимание, вы оплачиваете сейчас лишь часть суммы. Эта оплата необходима для бронирования<br />'.$total." руб. </h3><br />Оплата возможна через интернет карточкой, электронными деньгами, а также в евросети");
            //     $comment="Оплата тура \n ".$tourdata['title']." \n \n дата:".getDateById($dateid);
            //     saveBill($uid, $_POST['client_phones'], $_POST['email'],  $total, $comment);

            //     showPayment($uid, $_POST['name'], $_POST['client_phones'], $_POST['email'],  $total, $comment);
            $needtopay="yes";
        }

    }



    if ($type == 'biglion') {
        $sq = "insert into u_reserves(ctype,uid, fio,phone, email, codes,turdate,turid, reservedate, payed,sourcesyst) values(".$ctype."," . $uid . ",'" . $_POST['client_name'] . "','" . $phones ."','" . $_POST['email']. "','" . implode($_POST['client_code']) . "----" . $_POST['activation'] . "'," . $_POST['tour'] . "," . $tid . ", now(),1,'biglion')";
    }

    if ($type == 'kupikupon') {
        $sq = "insert into u_reserves(ctype,uid, fio,phone,email, codes,turdate,turid, reservedate,payed, sourcesyst) values(".$ctype."," . $uid . ",'" . $_POST['client_name'] . "','" . $phones ."','" . $_POST['email']. "','" . implode($_POST['client_code'] ). "----" . $_POST['activation'] . "'," . $_POST['tour'] . "," . $tid . ", now(),1,'kupikupon')";
    }

    if ($type == 'groupon') {
        $sq = "insert into u_reserves(ctype,uid, fio,phone,email,codes,turdate,turid, reservedate, payed,sourcesyst) values(".$ctype."," . $uid . ",'" . $_POST['client_name'] . "','" . $phones ."','" . $_POST['email']. "','" . implode($_POST['client_code']) . "----" . $_POST['activation'] . "'," . $_POST['tour'] . "," . $tid . ", now(),1,'groupon')";
    }

  //
    // if ($needtopay=="yes") echo('бронь будет подтверждена после оплаты счета');
    //echo ($sq." ".$num);
   // try {

    //if (!
    $mysqli->query($sq);
    $ok = $mysqli->insert_id;

    if ($ok==0)mail("zzeeee@gmail.com", "error"." ".$_POST['client_phones'].$sq, $sq." ".$num);
    //   $mysqli->query($sq);
    //}
    //catch (Exception $e){ echo ($e->getText());}

    //  if ($mysqli->insert_id != 0)
    $rso="";
    //echo ("<h1>".$num."</h1>");
    for($i=1;$i<=$num-1;$i++) {
       // echo ("!!!".$i."<br />");
        $mysqli->query($sq);
        $ok = $mysqli->insert_id;

        if ($ok==0)mail("zzeeee@gmail.com", "error"." ".$sq, $sq." ".$num);

        $rso=$rso.$ok;
    }

//    if ($ok==0)mail("zzeeee@gmail.com", "error", $sq." ".$num);

    $mok = md5($rso);


    if ($needtopay == 'yes') {
        if ($total!=0) {

            $comment = "Оплата тура \n " . $tourdata['title'] . " \n кол-во:" . $num . " \n дата:" . getDateById($dateid);
            saveBill($uid, $ok,  $_POST['client_phones'], $_POST['email'], $total, $comment);
            showPayment($uid,$ok,  $_POST['name'], $_POST['client_phones'], $_POST['email'], $total, $comment);
            $needtopay = "yes";

        }
    }


    $smstext = "Бронь:" . $tourdata['title'] . ",дата:" . getDateById($dateid) . ",код:" . $ok . ", 84993901808";

mail("zzeeee@gmail.com", "info"." ".$smstext,"");

    //if ($needtopay=='no') sendSms($_POST['client_phones'], $smstext);
    //echo ($smstext);

    if ($ok==0)  {echo ("<h1>При бронировании произошла ошибка(!). Тур не забронирован. Звоните 8 499 390 18 08");}
    else
    {

    echo('Тур '.($needtopay=="yes"?" предварительно забронирован, окончательное бронирование происходит после оплаты ":"успешно забронирован ").'(' . $ok . '). Код бронирования отправлен вам в виде sms-сообщения. В случае если вы не получили сообщение - свяжитесь с нами<br>Внимание за 2 дня до тура вам будет отправлено смс-оповещение с номером автобуса. <br />Если вы не получили смс - обязательно свяжитесь с нами <h3>8-499-390-18-08</h3>');
    echo('<a href="/">Вернуться на главную страницу</a>');}
    if ($ok!=0)     echo ('<a href="http://www.nov-rus.ru/showpdftour.php?bnum='.$ok.'"><h1>Скачать посадочный купон</h1></a>');


}


function showAct($system)
{
    global $mysqli;
    //echo ($system);
    ?><script type="text/javascript">system="<? echo($system);?>";</script>
    <div class="row">
        <div class="col-md-12 " >
            <form class="form col-md-12" name="aform" id="aform" method="POST" action="<? echo ($_SERVER['PHP_SELF']);?>?action=activate&control=1">
                <input type="hidden" name="bnum" value="1" id="bnum"/>
                <input type="hidden" name="system" value="<? echo ($system);?>" />

                <h3><? if ($system!="mr") echo ('Активация купона'); else echo ('Запись в поездку'); ?> </h3>

                <div class="input-group">
                    <span class="input-group-addon" id="b1">Как вас зовут, укажите ФИО</span>
                    <input type="text" class="form-control" width="40" name="client_name" aria-describedby="b1">
                </div>


                <div class="input-group">
                    <span class="input-group-addon" id="rb3">Укажите номер телефона (с восьмеркой, например 89161234567)</span>
                    <input type="text" class="form-control" required value="" id="r-phone"  name="client_phones" aria-describedby="rb3">
                </div>

                <div class="input-group">
                    <span class="input-group-addon" id="b2">Введите е-мейл (не обязательно, но желательно) </span>
                    <input type="text" class="form-control" style="z-index:0" alue="" id="r-email" name="email" aria-describedby="b2">
                </div>

                <span  id="tourdate" />
                <div class="col-lg-12">
                    <?
                    $dsq="";
                    if ($_GET['tid']!="") $dsq=" and tourid=".$_GET['tid']." ";
                    $sq='select day(date) as day, month(date) as month,id,comment, realmaxlimit, tourid from dates where date>now() '.$dsq.' and tourid in (select id from tours where visible=1 and type in (1,2))order by date limit 20';
                    //echo ($sq);
                    $rm=$mysqli->query($sq);
                    while ($rtl = $rm->fetch_assoc()) {
                        //echo ('1');
                        $tid=$rtl['tourid'];
                        $tourdata=getTourDataById($tid);
                        ?>
                        <div class="input-group" >
                   <span class="input-group-addon"><? echo ($rtl['day']." ".showTextMonth($rtl['month'])); ?>
                       <input type="radio" onclick="javascript:showCTypes(<? echo ($rtl['tourid']); ?>)" required name="tour"  value="<? echo ($rtl['id']);?>" aria-label="" >
      </span><h4><? echo ($tourdata['title']." (".getTourFreeSpaceText($rtl['id']));?>)</h4>
                        </div>

                        <?


                    }



                    ?>

                </div>

                <div class="input-group" id="ctype">
                </div>


                <div class="col-lg-12" id="coupons">
                    <?
                    if ($system<>"mr" and $system!="sres") {
                    ?>
                    <div class="input-group" id="inputbut">
                        <span class="input-group-addon" id="b3">Введите номер купона</span>
                        <input type="text" class="form-control" required  id="r-code" value="" name="client_code[]" aria-describedby="b3"><span  id="coupon/" />
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
                    <input type="text" style="z-index:0" class="form-control" value="" required  name="activation" aria-describedby="b3">
                </div>
            </div>


            <?php
        }
        ?>
        <div>&nbsp;&nbsp;&nbsp;&nbsp;Активируя купон вы соглашаетесь <a href="http://nov-rus.ru/aboutus.php?showrules=1" target="_blank">с условиями</a></div>

        <button type="submit" id="submitbutton" class="btn btn-info col-md-6"><?  if ($system!="mr" and $system!="sres") echo ("Активировать купон"); else echo ("Забронировать место"); ?></button>
        </form>
    </div>

    </div>

    <?php

}


//echo ('1');

//echo ('<script language="javascript>console.log("1");</script>');
//if ($control!=1) die();

switch ($_GET['action']):
    case "showgilmon": showAct("gilmon"); break;
    case "sres": showAct("sres"); break;

    case "showgroupon": showAct("groupon"); break;
    case "showbiglion": showAct("biglion"); break;
    case "showkupikupon": showAct("kupikupon"); break;
    case "showpk": showAct("pk"); break;
    case "showskidkaboom": showAct("skidkaboom"); break;
    case "showmr": showAct("mr"); break;
    case "activate": activateCoupon(); break;
endswitch;

?>

