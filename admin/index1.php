<?php
//echo ($_SESSION['id']."wfw".$_COOKIE['id']);
ini_set('display_errors','On');
error_reporting(E_STRICT|E_ALL);

define("IN_ADMIN", TRUE);
require "sqli.php";

require "newconnect-head.php";
require "products.php";

$allproducts=new products($mysqli);


global $mysqli;

//

showTopTop();
?>
<title><?php
    if ($_GET["tournumber"]!=""){

        $sq="select * from tours where id=".$_GET["tournumber"];
        if ($res=$mysqli->query($sq))
        {
            $row = $res->fetch_assoc();
            echo ($row['title']);
        }
    }
    ?></title>
<style>
    .delete     {cursor:pointer}
    #logbutton {cursor: default; text-decoration: none}
    .logoutbutton {cursor:pointer; font-weight:bold}
    .loginbutton {cursor:pointer; font-weight:bold }
    .hideallwindow{position:absolute; left:0; width:100%; top:0; height:100%; color:white; z-index:100}
    #r-process, #r-tickets, #r-history, #r-bonus, #r-waitpay, #r-options{cursor:pointer;}
    .b-popup{
        width:400px;
        overflow:hidden;
        position:fixed;
        top:20%;
        left:40%;
        z-index:3;
        margin:40px auto 0px auto;
        height: 250px;
        padding:10px;
        background-color: white;
        border-radius:5px;
        box-shadow: 0px 0px 10px #000;
    }
    .reg-popup{
        width:400px;
        overflow:hidden;
        position:fixed;
        top:20%;
        left:40%;
        z-index:3;
        margin:40px auto 0px auto;
        height: 400px;
        padding:10px;
        background-color: white;
        border-radius:5px;
        box-shadow: 0px 0px 10px #000;
    }

    .close-dialog{
        position: absolute;
        right: 0;
        top: 0;
        text-align: center;
        color: #fff;
        background-color: #2574A9;
        height: 30px;
        width: 30px;
        border:0;
        text-decoration: none;
    }

    .close-dialog:before{
        font-family: Arial;
        color: rgba(255, 255, 255, 0.9);
        content: "x";
        font-size: 20px;
        text-shadow: 0 -1px rgba(0, 0, 0, 0.5);
        outline: none;
    }

    .b-container
    {

        width:100%;
        height:1500px;
        opacity: 0.5; /* Значение прозрачности */
        filter: alpha(Opacity=50);
        top:0px;
        left:0px;
        position:fixed;
        background-color: #ccc;
        z-index:2;
        margin:0px auto;
        padding:10px;
        font-size:30px;
        color: #fff;
    }
    .r-popup{
        width:400px;
        overflow:hidden;
        position:fixed;
        top:20%;
        left:40%;
        z-index:3;
        margin:40px auto 0px auto;
        height: 220px;
        padding:10px;
        background-color: white;
        border-radius:5px;
        box-shadow: 0px 0px 10px #000;
    }
</style>
<script type="text/javascript">


    var aloaded=0;
    var bloaded=0;
    var userid=0;
    var uid=0;
    var errorMail=0;
    var goodname=false;
    var errorPass=0;
    var type=0;



    //    console.log('test1');

    var vars=getUrlVars();
    var checkedservices=Array();
    var totalprice;
    // var localVars=localStorage.getItem("add_services");
    var tourid=vars['tournumber'];
    var molodrus=vars['molodrus'];

    var dealer=vars['dealer'];
    var inaction=vars['inaction'];
    var action=vars['action'];
    //alert(jsline);

    <?

    if ($_SESSION['type']!="") {?>

    if (typeof(Storage) !== "undefined") {
        localStorage.setItem("type", <? echo($_SESSION['type']);?>);
    }

    <?}
    ?>


    if (typeof(Storage) !== "undefined") {
        type=localStorage.getItem("type");
        if (type==1) {
            molodrus=1;
        }
        console.log(type+"ttype!"+molodrus);
        sid=localStorage.setItem("sid",'"<? echo(session_id());?>"');

    }


    var add_services=Array();
    var toursdata=Array();

    $(function() {
        $(".photoclass").jCarouselLite({
            btnNext: ".left",
            btnPrev: ".right"
        });
    });


    function loadServices()
    {
        jsline = "backdata.php?action=showaddservices" + "&tournumber=" + tourid + ((!isNaN(molodrus) ? "&molodrus=1" : "")) + ((!isNaN(dealer) ? "&dealer=1" : "")) + ((!isNaN(inaction) ? "&inaction=1" : ""));
        $.getJSON(jsline, function (data) {
            var items = [];
            aloaded = 1;
            data.forEach(function (item, i, arr) {
                pq = arr[i];
                ind = pq['id'];
                add_services[ind] = pq;
                aloaded = 1;
                console.log('массив addservices загружен ' + ind + " " + aloaded);
            });
        });


    }

    function showBronLK(type)
    {
//alert('d');
        //$(document).ready(function() {

        console.log('showbron');
        rt = document.getElementById("panelbaraction");
        rt=$("#panelbaraction");
        uid = localStorage.getItem('uid');
        showServer(uid, 2, type);
        //console.log(rt.outerHTML);

        // rt.innerHTML = resl;

//        });

    }

    function loadTours()
    {

        jsline2 = "backdata.php?action=showtour" + "&tournumber=" + tourid + ((!isNaN(molodrus) ? "&molodrus=1" : "")) + ((!isNaN(dealer) ? "&dealer=1" : "")) + ((!isNaN(inaction) ? "&inaction=1" : ""));
        $.getJSON(jsline2, function (data) {
            var items = [];
            data.forEach(function (item, i, arr) {
                pq = arr[i];
                ind = pq['id'];
                toursdata[ind] = pq;
                toursdata['1'] = pq;
                console.log(ind+" "+pq['price']+" "+i);
                bloaded = 1;
            });
            console.log('массив toursdata загружен ' + ind + " " + bloaded);

            totalprice=parseInt(toursdata['1']['price']);
            tpriceplace=document.getElementById("totalprice");
            console.log(tpriceplace);
            if (String(tpriceplace)!="null")  tpriceplace.innerHTML=totalprice;
        });



    }

    function showButton()
    {
        if (typeof(Storage) !== "undefined") {

            var uid=localStorage.getItem("uid");
            if (String(uid)=="null") {//Отрабатываем "не авторизован"
                console.log('inner');
                console.log("o"+document.getElementById("logbutton").outerHTML);
                console.log("o"+document.getElementById("logbutton").innerHTML);

                document.getElementById("logbutton").innerHTML='<a class="loginbutton" >Вход</a>';
                console.log('lbn');
                console.log("o"+document.getElementById("logbutton").outerHTML);
                console.log("o"+document.getElementById("logbutton").innerHTML);


            } else
            {
                //   console.log("i"+document.getElementById("logbutton").innerHTML);
                console.log("i"+document.getElementById("logbutton").innerHTML);
                console.log("i"+document.getElementById("logbutton").outerHTML);

                document.getElementById("logbutton").innerHTML='<a class="logoutbutton" >Выход</a>&nbsp;&nbsp;&nbsp;<a href="index.php?action=my">ЛК</a>';


                console.log("i"+document.getElementById("logbutton").innerHTML);
                console.log("i"+document.getElementById("logbutton").outerHTML);

                //              console.log("o2"+document.getElementById("logbutton").outerHTML);
//       console.log('lbo');

            }

        }


    }

    function checkFio(value)
    {

        return (String(value).length<3);

    }

    function checkPhone1(val)
    {
        value=String(val);
        console.log(value+"-проверяймый телефон");

        value=value.replace(/ /g,"");
        value=value.replace(/-/g,"");
        value=value.replace(/,/g,"");

        var re = /^\d[\d\(\)\ -]{4,14}\d$/;
        var valid = re.test(value);
        var clength=(value.length==11);
        res=(valid && clength);

        console.log('Результат проверки:'+valid+clength+res);

        return res;
    }




    //alert()



    $(document).ready(function() {
        errorOrderPhone=!checkPhone1($("#phones-l").val());
        errorOrderFio=false; //checkFio($("#fio-l"));
        $("a.fancyimage").fancybox();
        console.log($("#phones-l").val()+"phone");

        //if (rt!="") {}
        loadServices();
        loadTours();
        showButton();


        $(document).on('focusout',"#fio-l",function() {
            var value = $(this).val().trim();
            console.log(value);


            if (checkFio(value) ) {
                $(this).notify("Укажите фамилию и имя для формирования посадочного купона", "error");
                $(this).addClass("errtextbox");
                errorOrderFio = true;
            } else {
                $(this).removeClass("errtextbox");
                errorOrderFio = false;
            }


        });



        $(document).on('focusout',"#phones-l",function() {
            var value = $(this).val().trim();
            console.log(value);


            if (!checkPhone1(value)) {
                $(this).notify("Номер телефона должен состоять из 11 цифр и начинаться с 8", "error");
                $(this).addClass("errtextbox");
                errorOrderPhone = true;
            } else {
                $(this).removeClass("errtextbox");
                errorOrderPhone = false;
            }

        });


        $(".loginbutton").click(function(event) {
            //    alert('вход');
            console.log('loginclick');
            showAuth2();
            return false;
        });


        $("#sendCtoserver").submit(function(event){

            event.preventDefault();
            alert('no');
            return false;

        });





        $(".logoutbutton").click(function(event) {
//            alert('выход');
            if (typeof(Storage) !== "undefined") {
                localStorage.removeItem("uid");
                localStorage.removeItem("phone");
                sendLogout();

                //     localStorage.clear(); //???
                showButton();
                if (String(window.location.href).indexOf("index.php?action=my")!=0) window.location.href="http://nov-rus.ru/index.php?action=logout";

            }
            return false;
        });

        $("#registration").click(function(event) {
            console.log('reg');

            showReg();
            return false;
        });


        $("#rpassword").click(function(event) {
            console.log('rp');
            showRPassword();
            return false;
        });


        $(document).on('onclick',"#entersite",function() {
            // alert('ol');
        });


        $(document).on('onsubmit',"#logform",function() {
//            alert('ol2');
        });



        console.log(vars['action']);
        //if (vars['action']!='showatour')
        sshow();

        $(document).on('onclick',"#addafriend",function() {
            // alert('ol');

            console.log("addafriend-1");

        });


        $("#addafriend").click(function(){

            console.log("addafriend-0");
            saveToStorage(2);
            sshow();


        });




        $("#addall2").click(function(event) {
            console.log('addbutton---vvv');
            // alert('ww111');
            id=saveToStorage();
            sendToServer(2);
//            sshow();
            $("#addall").css("visibility","hidden");
            document.getElementById('doreserve').reset();
        });

        $("#addall").click(function(event) {
            console.log('addbutton--one-vvv');
            tdate=$("input[name=tourdate]:checked").val();
            if (tdate=='undefined')  {
                $("input[name=tourdate]").notify("Выберите дату(!)");
                $("input[name=tourdate]").addClass("errtextbox");

                return false;


            }

            console.log("!");


            console.log(errorOrderPhone+" "+errorOrderFio+$("#fio-l").val())
            // alert('ww111');
            //showAuth2(1,"Пароль для входа вам был отправлен по смс");
            //  showPreAuth();
            if (errorOrderPhone ||errorOrderFio) {

                $(this).notify("Ошибка при заполнении формы. Проверьте:"+(errorOrderPhone?"телефон;":"")+(errorOrderFio?"ФИО;":""));
                $(this).addClass("errtextbox");

                return false;


            }
            sendOnetoServer();
            //id=saveToStorage();
            //sendToServer(2);
//            sshow();
            //$("#addall").css("visibility","hidden");
            //document.getElementById('doreserve').reset();
        });




        $("#addbutton").click(function(event){
            console.log('addbutton');
            // alert('ww');
            saveToStorage();
            sshow();
            $("#addbutton").css("visibility","hidden");
            $("#addall").css("visibility","hidden");

            $("#addbutton").css("display","none");
            $("#addall").css("display","none");
            $("#addoptions").css("display","none");
            $("#allprices").css("display","none");







            document.getElementById('doreserve').reset();
        });


        $("form input:checkbox").click(function(event) {
            if (aloaded==1 ) {
                var price=0;
                rt=$("input:checkbox:checked").each(function(i, val) {
                    checkedservices[i]=val.value;
                    //   var text = $(this).value;
                    id=val.value;
                    p1=add_services[id];
                    console.log(p1['title']+" "+p1['price']);
                    if (val.checked) price+=parseInt(add_services[val.value]['price']);
                    console.log(val.value+" "+val.checked);
                });
                console.log(price);
                console.log(String(checkedservices));

                //totalprice=toursdata[]
                // $('#extprice').innerHTML=price;
                priceplace=document.getElementById("extprice");
                priceplace.innerHTML=price;
                setPrice=parseInt(($("#setprice").text()));
                console.log(setPrice+" "+totalprice+" "+price);
                totalprice=setPrice+price;
                tpriceplace=document.getElementById("totalprice");
                tpriceplace.innerHTML=totalprice;

                console.log(totalprice);


                // console.log(rt);

                // console.log();
                id = event.target.value;
                //    console.log(event.target.value);
                id1 = event.target.checked;


                //rt=document.getElementsByName('pars[]');
                ///console.log(rt.outerHTML);

                var aprice = add_services[id];
                console.log(aprice['title']);

//                console.log('пересчет цены');
            }
            // console.log( $( this ).serialize() );}

            /*            $( "form" ).submit(function( event ) {
             console.log( $( this ).serializeArray() );
             event.preventDefault();
             });*/

//            alert('ch');
        });


    });





</script>
<?php
showMiddle();
showBody();


function dologout()
{

    setcookie(session_name(), session_id(), time()-60*60*24);
    // и уничтожаем сессию
    session_unset();
    session_destroy();
    showAllTours();


}


function showLineArr($arr)
{
    global $mysqli;
    global $companyinfo;

    while ($row = $arr->fetch_assoc()) {

        ?>
        <div class="col-sm-6 col-md-4" >
            <div class="thumbnail"  >
            <span class="label label-success"><?
                if ($row['type']==1) echo ('Паломничество');
                if ($row['type']==2) echo ('Экскурсия');
                if ($row['type']==3) echo ('Прогулка');
                if ($row['type']==5) echo ('Потрудиться');

                $addline="";

                if (checkMr()=="1") { $addline="&molodrus=1"; }
                if (checkAction()=="1") { $addline="&actionprice=1"; }

                if ($_GET['dealer']!="") {$addline="&dealer=1";}


                ?></span>
                <img height=200 src='img/<? echo($row['mainfoto']); ?>'>
                <div class="caption">
                    <h3>
                        <a href='index.php?action=showatour<? if($_GET['nomenu']) echo ("&nomenu=32"); echo($addline);?>&tournumber=<? echo($row['id']); ?>'><? echo($row['title']); ?></a>
                    </h3>
                    <h2><?
                        $bprice=$row['baseprice'];

                        if (checkMr()=="1") $bprice=$row['price1'];
                        if (checkAction()=="1") $bprice=$row['price2'];

                        if ($_GET['dealer']!="") $bprice=$row['price3'];


                        if ($row['type']==1 or $row['type']==2 )echo($row['blength'] . " " . dday($row['blength']) . " за " . $bprice. " руб");
                        if ($row['type']==3 or $row['type']==4 )echo($row['baseprice']." руб");
                        if ($row['type']==5)echo($row['blength'] . " " . dday($row['blength']));

                        ?></h2>
                    <p>Даты отправлений:
                        <?
                        $sq = "SELECT id,day(date) as day, month(date) as month, year(date) as year FROM `dates` where  date>now() and year(date)=2016 and tourid=" . $row['id'];
                        $rmq = $mysqli->query($sq);
                        while ($rmdate = $rmq->fetch_assoc()) {
                            echo($rmdate['day'] . "." . $rmdate['month'] . "." . $rmdate['year'] . "; ");
                        }
                        ?>
                    </p>

                    <p><a href="index.php?action=showatour&tournumber=<? echo($row['id']);?>" class="btn btn-primary"  role="button">Забронировать!</a> </p>
                </div>
            </div>

        </div>

        <?php
    }


}


function showClosest($typ)
{

global $mysqli;
//echo('');

//echo("<h1>Ближайшие</h1>");

?>
<div class="row" >
    <div class="panel panel-default col-md-12">
        <h2>Ближайшие <?  $dat="поездки";
            if ($typ==3) $dat="прогулки";
            if ($typ==4) $dat="мастер-классы";
            echo($dat);

            ?></h2>
        <!-- Default panel contents -->
        <?
        $sql="select * from tours where visible=1 ";
        if ($typ=="") $sql=$sql." and id in (select tourid from top3)";
        if ($typ==1) $sql="select * from tours where visible=1 and id in (select tourid from top3piligrim) ";
        if ($typ==2) $sql="select * from tours where visible=1 and id in (select tourid from top3ex) ";
        if ($typ==3) $sql="select * from tours where visible=1 and id in (select tourid from top3walk) ";
        if ($typ==4) $sql="select * from tours where visible=1 and id in (select tourid from top3masters) ";
        if ($typ==5) $sql="select * from tours where visible=1 and id in (select tourid from top3trud) ";
        //echo ($sql);
        try
        {
            $resU = $mysqli->query($sql);
            // echo($resU->num_rows."-строк!!!!");
            showLineArr($resU);
        }
        catch (Exception $e) {
            echo($e->getMessage()); //выведет \\\"Exception message\\\"
        }
        ?>
    </div>
    <?
    }


    function makeAuth()
    {
        global $mysqli;
        $login=$_POST['login'];
        if (strpos($login,'@')>0) $type=1; else $type=2;
        if ($type==1) {
            $sq = "select * from main_users where user_login='" . $_POST['login'] . "' and user_password='" . $_POST['pwd'] . "'";
            $res = $mysqli->query($sq);
            $rmm = $res->fetch_assoc();
            //   echo ($rmm);
            if (count($rmm) > 0) {
                //     echo ("авторизация ок".$rmm['user_id']);
                $_SESSION['id'] = $rmm['user_id'];
                // $id=$_SESSION['id'];
                //  return $id;
                // setCookie("id", $rmm['user_id'],time()+50000000);
                $host = $_SERVER['HTTP_HOST'];
                $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                $extra = '/index.php';
                $redirstr = "Location: http://" . $host . $uri . $extra;

                header($redirstr);
                // echo("<div class='alert alert-warning'>".$redirstr."</div>");

                echo("<div class='alert alert-success'>Вы успешно вошли в систему. <a href='index.php'>Перейти к списку туров</a></div>");

                exit;


//        echo (time()+5000000);
                //       setCookie("autorized","yes");
                //   echo ('.!!!'.$_SESSION['userid']);

            } else {
                echo('<div class="alert alert-danger" role="alert">Вы ошиблись при вводе логина и пароля, проверьте не нажат ли у вас Caps Lock и  <a href=login.php>попробуйте еще раз</a></div>');
                die();
            }
        }

        if ($type==2) {

            //      echo ('user cabinet');
            $sq = "select * from clients where phone='" . $login . "' and pwd='" . $_POST['pwd'] . "'";


            //        $sq=$mysqli->escape_string($sq);
            //echo ($sq);
            $res = $mysqli->query($sq);
            $rmm = $res->fetch_assoc();

//          echo ($rmm);
            if (count($rmm) > 0) {

                ?>
                <script type="text/javascript">
                    localStorage.setItem("uid",<? echo($rmm['id']);?>);

                    $(document).ready(function()
                    {
                        console.log('<? echo($rmm['id']);?>');

                        //
                    });

                </script>
                <?
                showLK();
                //echo ('успешно');
                $id = $rmm['id'];
                $_SESSION['id'] = $id;
            }


        }

    }

    function showAllTours()
    {
    global $mysqli;
    showClosest($_GET['cat']);
    $sql="select * from tours where id in (select tourid from dates where date>now())  and visible=1 ";
    if ($_GET['cat']!="") $sql=$sql." and type=".$_GET['cat'];
    if ($_GET['place']!="") $sql=$sql." and id in ((select tourid from tours_places where placeid=".$_GET['place']."))";
    //select * from tours where id in
    //echo($sql);
    //if (!$res2) echo("err");
    /*
        echo('<div class="row"><h1>Все поездки</h1>');
        try
        {
            $res3 = $mysqli->query($sql);
            showLineArr($res3);
        }
        catch (Exception $e) {
            echo($e->getMessage()); //выведет \\\"Exception message\\\"
        }
    echo('</div>');
    */

    ?>
    <div class="panel panel-default">
        <!-- Default panel contents -->
        <div class="panel-heading">Расписание поездок из Москвы</div>


        <!--    <tr><Td>Направление</Td><td>Цена</td><td>Дней</td></td><td width="100"><? echo(mName(date("m")-1)); ?> </td><td width="100"><? echo(mName(date("m"))); ?></td><td width="100"><? echo(mName(date("m")+1)); ?></td></tr>-->
        <table class="table table-striped" valign="top" border="0">
            <thead>
            <tr>
                <th></th>
                <th>Цена</th>
                <th>Дней</th>
                <th><? echo(mName(date("m")-1)); ?></th>
                <th><? echo(mName(date("m"))); ?></th>
                <th><? echo(mName(date("m")+1)); ?></th>
            </tr>
            </thead>
            <tbody>

            <!--

<div>
    <div class="row">
    <div class="col-md-6"></div>
    <div class="col-md-1">Цена</div>
    <div class="col-md-1">Дней</div>
    <div class="col-md-1"></div>
    <div class="col-md-1"><? echo(mName(date("m"))); ?></div>
    <div class="col-md-1"><? echo(mName(date("m")+1)); ?></div>
    </div>
-->

            <?



            //    echo('<div class="row"><h1>Все поездки в будущие 3 месяца</h1>');
            $sq="SELECT distinct (id) FROM alltours_3 where visible=1  and id in (select tourid from dates where date>now())";

            if ($_GET['cat']!="") $sq=$sq." and type= ".$_GET['cat'];
            if ($_GET['place']!="") $sq=$sq." and id in ((select tourid from tours_places where placeid=".$_GET['place']."))";
            //echo($sq);
            try
            {
                $res_tour_list = $mysqli->query($sq);
                //      echo("<table border='1'>");
                $i=0;
                while($res_tour_ln=$res_tour_list->fetch_assoc())
                {

                    // echo("<br />".$i."<br />");
                    //$i++;
                    //echo (getTourTitleById($res_tour_ln['id']));
                    showTourLine($res_tour_ln['id'], getTourDataById($res_tour_ln['id']));
//$res_tour_ln['title']
                }
                echo("</tbody></table>");

            }
            catch (Exception $e) {
                echo($e->getMessage()); //выведет \\\"Exception message\\\"
            }

            //echo('</div>');
            echo('</div></div>');
            }





            function showATour($id)
            {

            global $mysqli;
            global $companyinfo;
            $sq="select * from tours where id=".$id;
            if ($res=$mysqli->query($sq))
            {
            $rm = $res->fetch_assoc();
            if (!$_GET['nomenu']) {
                ?>

                <ol class="breadcrumb">
                    <li><a href="index.php">Главная</a></li>
                    <li><a href="index.php?cat=<? echo($rm['type']); ?>">Список туров</a></li>
                    <li class="active">Страница тура</li>
                </ol>
                <?php
            }
            ?><?

            $photo[0]=$rm['mainfoto'];
            $i=1;
            if ($rm['foto1']!="") {$photo[$i]=$rm["foto1"];$i++;}
            if ($rm['foto2']!="") {$photo[$i]=$rm["foto2"];$i++;}
            if ($rm['foto3']!="") {$photo[$i]=$rm["foto3"];$i++;}



            ?>

            <div class="jumbotron">
                <img src="img/<? echo($rm['mainfoto']); ?>" vspace=5 class="pull-right" height="300"/>
                <h1 style="color:darkred"><? echo($rm['title']); ?></h1>
                <p class="lead"><? echo($rm['main_descr']); ?></p><?
                $addline="";
                $price = $rm['baseprice'];
                if ((checkMr()=="1") and $rm['price1'] <> "") { $price = $rm['price1']; $addline='<p style="font-size:14px">цена для участников <a href="https://vk.com/mr_tours_education_volounteer">группы</a> и постоянных клиентов</p>';}
                if ((checkAction()=="1") and $rm['price2'] <> "") { $price = $rm['price2']; $addline='<p style="font-size:14px">цена только по акции(!) и для участников <a href="https://vk.com/mr_tours_education_volounteer">группы</a> </p>';}

                if (($_GET['dealer'] != "") and $rm['price3'] <> "") { $price = $rm['price3']; $addline='<p style="font-size:14px">цена только при заказе через агенства';}


                ?>

                <h2><?
                    //Если прогулка, то не выводим длительность
                    switch ($rm['type']):
                        case 3: echo($price);break;
                        case 5:     echo($rm['blength'] . " " . dday($rm['blength']) ); break;
                        default:
                            echo($rm['blength'] . " " . dday($rm['blength']) . " за " . $price);break;

                    endswitch;

                    if ($rm['type']!=5) echo(' руб.');
                    ?></h2>
                <?php
                echo ($addline);

                ?>
                <p><a class="btn btn-lg btn-info" href="#reserve" role="button">Присоединиться!</a></p>
                <p class="lead">Даты выездов: <? echo (showDatesOsList($id)) ?>
                    <?
                  //  echo ('<div class="col-md-8" class="row">');
                    //echo("</div>");
                    ?>

                </p>



            </div>

            <div class="panel panel-info">
                <div class="panel-heading">О туре:</div>
                <ul class="list-group">
                    <li class="list-group-item"><? echo($rm['description']); ?></li>
                    <li class="list-group-item"><div class="row">
                    <?

                    $sq="select * from photos where tid=".$id;


                    $rpic=$mysqli->query($sq);

                    while($rt=$rpic->fetch_assoc()){
                    for ($i=0;$i<count($photo);$i++) {
//                        echo ("<span> ");
                        echo('<a   title="'.$rt['comment'].'" class="col-md-3 fancyimage" data-fancybox-group="group" href="img/' . $rt['name'] . '" ><img  alt="'.$rt['comment'].'" class="img-responsive"  src="img/' . $rt['name'] . '" vspace=5 class="pull-right" width="300" height="200"/></a>');
  //                      echo("</span>");


                    }
                    }




                    ?><br /><br /><Br />
                        </div></li>
                </ul>

                <div class="panel-body"><b>Программа</b>:<br/>
                    <pre><? echo($rm['program']); ?></pre>
                    <?
                    //showDatesOsList($id);

                    }

                    ?>
                </div><?
                showTourPlaces($id, $rm['type']);
                ?>
                <ul class="list-group">
                    <li class="list-group-item">
                        <div><h4><b>Дополнительная информация</b></h4>
                            <p><?php if ($rm['include'] != "") echo("Включено: " . $rm['include']);
                                if ($rm['exclude'] != "") echo("<br /> Не входит:" . $rm['exclude']); ?></p><br/>
                            <?
                            /*
                            $sq = "select * from add_services where tourid=" . $id;
                            $rma = $mysqli->query($sq);
                            $dat="";
                            while ($rms = $rma->fetch_assoc()) {
                                $prc=$rms['price'];
                                if ($_GET['molodrus']!='') $prc=$rms['price1'];
                                if ($_GET['dealer']!='') $prc=$rms['price3'];
                                $dat.="<tr><Td>".$rms['title']."</Td><td>".$prc." руб.</td></tr>";

                                // echo ('<h1>'.$prc." ".$rms['price2']."</h1>");
                              //  echo('<input  type="checkbox" name="add[]" value="' . $rms['id'] . '" />&nbsp;' . $rms['title'] . "-> " . ($prc>0?"+":"").$prc . " руб. <br />");

                            }
                            if ($dat!="")$dat='<h3>Дополнительные опции</h3><table class="table"><thead><td>Возможность</td><td>Цена</td></thead>'.$dat.'</table>';
                            echo($dat);
*/
                            ?>
                        </div></li>
                    <li class="list-group-item">
                        <?

                        //  dtype('start');


                        showReserve2($id,2);
                        ?><a name="reserve"></a>
                        <div class="panel panel-primary" id="allnewreserves" style="visibility:hidden">
                            <div class="panel-heading">Бронирование для группы</div>
                            <div class="panel-body">
                                <div ><span id="reservespace">Здесь будут показаны редактируемые вами брони</span></div></div></div>
                        <div class="panel panel-primary" id="allmytickets" style="visibility:hidden">
                            <div class="panel-heading">Мои билеты</div>
                            <div class="panel-body" id="ticketlist"></div></div>





                    </li>
                    <!--
                        <li class="list-group-item">
                                <div class=caption>Стоимость:
                                    <h3><? echo("<h4 id='showprice'>" . $price . " руб. за " . $rm['blength'] . " " . dday($rm['blength']));
                    if ($rm['nights'] > 0) echo("/" . $rm['nights'] . " " . dnight($rm['nights']));
                    echo("</h4>"); ?></h3></div>

                                        <a class="btn btn-primary"
                                           href="index.php?action=reserve<? if ($companyinfo['type'] == 10) echo("&dealer=yes"); ?>&tournumber=<? echo($rm['id']); ?><?

                    if (checkMr()=="1") echo ("&molodrus=1");
                    if (checkAction()=="1") echo ("&actionprice=1");

                    ?>"
                                           role="button">Присоединиться!</a>
                                    </p><br/>
                                    <br/>
                            </li>
                        <li class="list-group-item">
                            <div class=caption>Стоимость:</div>

-->
                    <?
                    $addline="";
                    if (checkMr()=="1") {  $addline="&molodrus=1"; }
                    if (checkAction()=="1") {  $addline="&actionprice=1"; }



                    if ($_GET['dealer']!="") {$addline="&dealer=1";}
                    ?>
                    <p><a target=_blank href="showpdftour.php?tournumber=<? echo ($id.$addline); ?>">Листовка тура</a></p>



            </div>


            </li>
            </ul>

    </div>


<?php


}

function showDatesOsList($id)
{
    global $mysqli;
    $sq = "SELECT id, comment, day(date) as day, month(date) as month, year(date) as year FROM `dates` where   tourid=" . $id;
    //echo ($sq);
    $rline = "";
    $rml = $mysqli->query($sq);
    if ($rml->num_rows >= 1) {
        //echo('<br />Даты поездок и особенности:');
        while ($rms = $rml->fetch_assoc()) {
            $cm = $rms['comment'];
            $rline = $rline . $rms['day'] . "." . $rms['month'] . ($cm != "" ? " - " . $cm : "") . "; ";

        }
        return $rline;
    }
}


function showBUser($id)
{

    global $mysqli;
    $uid=$_SESSION['uid'];
    dlog("aith:".$uid);
    $fiovalue1="";
    $phonevalue1="";
    if ($uid!="")
    {
        $rt=getClientData($uid);
        $fiovalue1=' value="'.$rt['name'].'" ';
        $phonevalue1=' value="'.$rt['phone'].'"';
    }
    if ($fiovalue1=="")    $fiovalue1=' placeholder="пример: Иванов Иван Иванович" ';
    if ($phonevalue1=="")    $phonevalue1=' placeholder="пример: 89134567890" ';

    if ($uid=="") {


        ?>
        <input type="hidden" name="typ" value="1"/>
        <span><span style="cursor:pointer" onclick="showReg();return false;" >Хотите бронировать поездку в 1 клик? Зарегистрируйтесь!</span><br/><br/>Укажите имя и номера телефонов участников поездки</span>
        <div class="input-group">
            <!--<label for="fio-l">ФИО</label>-->
            <span class="input-group-addon" id="basic-addon1">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ФИО</span>
            <input type="text" class="form-control" aria-describedby="basic-addon1" name="name" id="fio-l"
                <? echo($fiovalue1); ?> />
        </div>
        <div class="input-group">
        <span class="input-group-addon" id="basic-addon2">Телефон</span>
        <input type="text" class="form-control" style="z-index:0" name="phones" id="phones-l" <? echo($phonevalue1); ?>
               aria-describedby="basic-addon2">
        </div><?php


    } else {
        echo ('<input type="hidden" name="name" id="fio-l"  '.$fiovalue1.'/>');
        echo ('<input type="hidden" name="name" id="phones-l"  '.$phonevalue1.'/>');


    }
    $sq = "select * from add_services where type=0 and visible=1 and tourid=" . $id."  union (select * from add_services where type=2 and visible=1 ) order by type";
    //echo ($sq);
    $rma = $mysqli->query($sq);
    $sq2 = "select * from add_services where type=1 and visible=1 ";
    //echo ($sq2);


    $rma2=$mysqli->query($sq2);
    $dat="";
    $ii=0;
    while ($rms = $rma->fetch_assoc()) {
        $prc=$rms['price'];
        if (checkMr()=="1") $prc=$rms['price1'];
        if (checkAction()=="1") $prc=$rms['price2'];

        if ($_GET['dealer']!='') $prc=$rms['price3'];
        $dat.="<tr><td><input id=testdat type='checkbox' class='checkpar' name=pars[] value='".$rms['id']."' /></td><Td>".$rms['title']."</Td><td>".$prc." руб.</td></tr>";
        // echo ('<h1>'.$prc." ".$rms['price2']."</h1>");
        //  echo('<input  type="checkbox" name="add[]" value="' . $rms['id'] . '" />&nbsp;' . $rms['title'] . "-> " . ($prc>0?"+":"").$prc . " руб. <br />");
        $ii++;
    }
    $dat2="";

    while ($rms2 = $rma2->fetch_assoc()) {
        $prc2=$rms2['price'];
        if (checkMr()=="1") $prc2=$rms2['price1'];
        if (checkAction()=="1") $prc2=$rms2['price2'];
        if ($_GET['dealer']!='') $prc2=$rms2['price3'];
        $dat2.="<tr><td><input id=testdat type='checkbox' class='checkpar' name=pars[] value='".$rms2['id']."' /></td><Td>".$rms2['title']."</Td><td>".$prc2." руб.</td></tr>";
        // echo ('<h1>'.$prc." ".$rms['price2']."</h1>");
        //  echo('<input  type="checkbox" name="add[]" value="' . $rms['id'] . '" />&nbsp;' . $rms['title'] . "-> " . ($prc>0?"+":"").$prc . " руб. <br />");
        $ii++;
    }



    if ($dat!="")$dat='<span id="addoptions"><h3>Дополнительные опции</h3><table class="table"><thead><td></td><td>Возможность</td><td>Цена</td></thead>'.$dat.'</table></span>';
    echo($dat);

    if ($dat2!="")$dat2='<span id="products"><h3>Товары в дорогу</h3><table class="table"><thead><td ></td><td  ></td><td>Цена</td></thead>'.$dat2.'</table></span>';
    echo($dat2);


}



function showLK()
{
    //  echo('refw2fr2r23rr3');

    ?><br />
    <div class="container">

        <div class="row">
                        <span class="col-md-3" id="instruments" ><div class="panel panel-primary">
                <div class="panel-heading">Инструменты</div>
                <div class="panel-body"><div id="r-waitpay" onclick="showBronLK(4);">Ожидают оплаты</div><div  id="r-process" onclick="showBronLK(1);">Незавершенные</div>
                    <div id="r-tickets" onclick="showBronLK(2);">Мои билеты</div><div id="r-history" onclick="showBronLK(3);">История поездок</div><div onclick="showOptions();" id="r-options">Настройки</div>
                       </div> </div></span>
            <span class="col-md-8" id="cpanel">
            <div class="panel panel-primary">
                <div class="panel-heading" id="chead"><a href="index.php?action=my">Добро пожаловать в личный кабинет!</a></div>
                <div class="panel-body"><span id="cpanelbaraction"><span id="panelbaraction">Здесь будут показаны редактируемые вами брони</span></span></div>
            </div>
         </span></div></div>
    <script type="text/javascript">
        showBronLK(4);
    </script>
    <?

}




function showReserve2($id, $typ)//БРОНИРОВАНИЕ
{
    global $allproducts;
    dlog($typ."!!!!");
    global $mysqli;
    global $companyinfo;
    global $companyname;
    global $dealerid;
    global $usertype;

    $rs=getTourDataById($id);


    ?>
    <div class="panel panel-primary" id="reserve-panel">
        <div class="panel-heading">
            <h3 class="panel-title">Бронирование</h3>
        </div> <?
        //                echo ($usertype);
        if (!$_GET['nomenu']) {
        ?>
        <div class="panel-body" style="font-size:18">
            <form id="doreserve" action="reserve.php?action=add&do=1" method="POST" enctype="multipart/form-data" role="form">
                <input type="hidden" name="nomenu" value="1"/>
                <input type="hidden" name="tourid" value="<? echo($id); ?>"/>
                <input type="hidden" name="molodrus" value="<?echo(checkMr());?>" />
                <input type="hidden" name="actionprice" value="<?echo(checkAction());?>" />
                <input type="hidden" name="dealerid" value="<? echo($dealerid); ?>"/>
                <div class="form-group" ><?
                    $sq = "SELECT id,day(date) as day, month(date) as month, year(date) as year FROM `dates` where  date>now() and tourid=" . $id. " order by date ";
                    $rmq = $mysqli->query($sq);
                    if ($rmq->num_rows > 1) {
                        ?>
                        <label for="dat">Выберите дату тура :</label>
                        <?

                        while ($rmdate = $rmq->fetch_assoc()) {
                            $checked="";
                            if ($_GET['tourdate']==$rmdate['id']) $checked=" checked ";
                            echo('<br /><input  required type="radio" '.$checked.' name="tourdate" value="'.$rmdate['id'].'"/>'. $rmdate['day'] . "." . $rmdate['month'] . "." . $rmdate['year']." ".getTourFreeSpaceText($rmdate['id']));
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
                ?><span id="allprices"><h5>Цена (за одного человека*): <span id="setprice"><?
                            $bprice=$rs['baseprice'];
                            if (checkMr()=="1") $bprice=$rs['price1'];
                            if (checkAction()=="1") $bprice=$rs['price2'];

                            if ($_GET['dealer']!="") $bprice=$rs['price3'];
                            echo($bprice);
                            ?></span><br /><span>Цена опций:</span><span id="extprice"></span></h5><br />
                        <span>Итого: </span><span id="totalprice"></span><br />
                    <?

                    //                        $allproducts->showProduct(38);





                    ?><br />
                        <span   id="addall" class="btn btn-primary" role="button"><?
                            if ($rs['type']!=5) echo ('Еду сам. Перейти к оплате');else echo ('Присоединиться');

                            ?></span>
                        <span   id="addbutton" class="btn btn-primary" role="button">Еду с друзьями</span>
</span>
                <!-- <input  id="addbutton" type="submit"  class="btn btn-default" value="Добавить"/>-->

            </form></div></div>
    </span><br />

    <?
}




?>

    <?
    //echo ('ddd'.$_GET["action"]);
    $tn=$_GET['tournumber'];
    switch($_GET['action']):
//    case "showtour": showTour($_GET['tournumber']);break;


        case "showatour": showATour($tn);break;
        //case "reserve":  ShowReserve($tn);break;
        case "my": showLK();break;
        case "logout": dologout(); break;
        case "makeauth": makeAuth();break;
        //  case "addorder": addOrder();break;

        //case "logout": makeLogout();break;
        case "": showAllTours();break;
        default: showAllTours();
    endswitch;


    ?>
</div>
</body>
</html>
