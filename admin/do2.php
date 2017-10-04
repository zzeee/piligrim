<?php
define("IN_ADMIN", TRUE);
require "sqli.php";
ini_set('display_errors','On');

error_reporting('E_ALL');

require "newconnect-head.php";

//showTopTop();//Внимание(!) ТОЛЬКО TopTop. Middle содержит ЯНДЕКС МЕТРИКУ!
?>
<style type="text/css">
    #editrazm{cursor:pointer}
</style>

<script type="text/javascript">

</script>
</head>
<body>

<?


if ($mysqli->connect_errno) {
    printf("", $mysqli->connect_error);
    exit();
}


function editrazm($id)
{
global $mysqli;

$sq='select * from add_services where id='.$_GET['rid'];


$rws=$mysqli->query($sq);
$res=$rws->fetch(PDO::FETCH_ASSOC);
 ?>
    Добавить опции размещения<br />
<form name="razm" action="do2.php?edit=updaterazm&rid=<? echo($_GET['rid']); ?>&tourid=<? echo($id);?>" method="POST" enctype="multipart/form-data">

    <input type="text" name="razm_name" value="<? echo($res['title']) ?>"/> - Название<br />
    <input type="text" name="razm_price" value="<? echo($res['price']) ?>"/> - Цена  (основной сайт) <br />
    <input type="text" name="razm_price1" value="<? echo($res['price1']) ?>"/> - Цена1 (для пост клиентов) <br />
    <input type="text" name="razm_price2" value="<? echo($res['price2']) ?>"/> - Цена2 (Цена - акционная) <br />
    <input type="text" name="razm_price3" value="<? echo($res['price3']) ?>"/> - Цена3 (Цена - туроператоры) <br />
    <input type="text" name="razm_price4" value="<? echo($res['price4']) ?>"/> - Цена4 <br />
    <input type="text" name="razm_price5" value="<? echo($res['price5']) ?>"/> - Цена5 <br />

    Описание<br/>
    <textarea name="razm_descr" ><? echo($res['description']) ?></textarea><br />
    <img src="img/<? echo($res['foto']) ?>" /><input type="file" name="option1" /><br />
    <img src="img/<? echo($res['foto1']) ?>" /><input type="file" name="option2" /><br />
    <img src="img/<? echo($res['foto2']) ?>" /><input type="file" name="option3" /><br />
    <input type="submit" />


    <?

}

function deleteRazm($tourid,$rid)
{
    $sq="delete from add_services where id=".$rid;
    echo ($sq);
    global $mysqli;
    $res=$mysqli->query($sq);

    if ($res) echo ('Удалено');

    echo('<a href="do2.php?edit=show&id='.$tourid.'">Вернуться к редактированию</a><a target=_blank href="index.php?action=showatour&tournumber='.$tourid.'">На сайт</a>');

}


function addRezervField($tid)
{
    global $mysqli;

?>    <table border="1">
    <tr><td><br /><br /><br /><br />Варианты размещения</td><td>
            <?php
                $sq="select * from add_services where tourid=".$tid;
                //echo ($sq);
                try {

                    $sqr = $mysqli->query($sq);
                    echo ('<table class="table"><thead><td>Название</td><td>Сайт</td><td>Пост</td><td>Куп</td><td>Тур</td></thead>');
                    while ($row=$sqr->fetch(PDO::FETCH_ASSOC))
                    {
                        echo ('<tr>');
                        echo ('<td>'.$row['title'].'</td>');
                        echo ('<td>'.$row['price'].'</td>');
                        echo ('<td>'.$row['price1'].'</td>');
                        echo ('<td>'.$row['price2'].'</td>');
                        echo ('<td>'.$row['price3'].'</td>');
                        echo ('<td>'.$row['price4'].'</td>');
                        echo ('<td>'.$row['price5'].'</td>');
                        echo ('<td><a href="do2.php?edit=editrazm&id='.$tid.'&rid='.$row['id'].'"><span id="editrazm"  class="glyphicon glyphicon-pencil" aria-hidden="true"></span></a><a href="do2.php?edit=deleterazm&id='.$tid.'&rid='.$row['id'].'"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></td>');

                        echo ('</tr>');


                       // echo ($row['title']."-".$row['price']."руб.<img src='img/".$row['foto']."'/><br /><br><img src='img/".$row['foto1']."'/><br /><img src='img/".$row['foto2']."'/><br /><a href='do2.php?edit=editrazm&razm=".$row['id']."'>Редактировать</a><a href='do2.php?edit=deleterazm&id=".$tid."&rid=".$row['id']."'>Удалить</a><br />");


                    }
                    ?></table>
                    Добавить опции размещения<br />
                    <form name="razm" action="do2.php?edit=addrazm" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="tourid" value="<? echo($tid);?>" />
                        <input type="text" name="razm_name" /> - Название<br />
                        <input type="text" name="razm_price" /> - Цена  (основной сайт) <br />
                        <input type="text" name="razm_price1" /> - Цена1 (МР и для пост. клиентов) <br />
                        <input type="text" name="razm_price2" /> - Цена2 () <br />
                        <input type="text" name="razm_price3" /> - Цена3  (ТУРОПЕРАТОРЫ) <br />
                        <input type="text" name="razm_price4" /> - Цена4 <сумма доплаты через сайт />
                        <input type="text" name="razm_price5" /> - Цена5 <предоплата />


                        Описание<br/>
                        <textarea name="razm_descr" ></textarea><br />
                        <input type="file" name="option1" />
                        <input type="file" name="option2" />
                        <input type="file" name="option3" />
                        <input type="submit" />
                    </form>
                    <?
                }
                catch
                (Exception $e) {
                    echo($e->getMessage());
                }
               ?></td></tr></table>
    <?


    }


function addrazm($updateid)
{
    $tourid=$_POST["tourid"];
    $razm=$_POST['razm_name'];
    $razm_price=$_POST['razm_price'];
    $razm_price1=$_POST['razm_price1'];
    $razm_price2=$_POST['razm_price2'];
    $razm_price3=$_POST['razm_price3'];
    $razm_price4=$_POST['razm_price4'];
    $razm_price5=$_POST['razm_price5'];
    if ($razm_price=="") $razm_price=0;
    if ($razm_price1=="") $razm_price1=0;
    if ($razm_price2=="") $razm_price2=0;
    if ($razm_price3=="") $razm_price3=0;
    if ($razm_price4=="") $razm_price4=0;
    if ($razm_price5=="") $razm_price5=0;


    global $mysqli;

 if ($updateid=="")    $sql="insert into add_services (tourid, title, price, price1,price2,price3,price4,price5,  description) values(".$tourid.",'".$razm."',".$razm_price.",".$razm_price1.",".$razm_price2.",".$razm_price3.",".$razm_price4.",".$razm_price5.",'".$_POST['razm_descr']."')";

    else $sql="update add_services set title='".$razm."', price=".$razm_price." , price1=".$razm_price1." ,price2=".$razm_price2." ,price3=".$razm_price3." ,price4=".$razm_price4." ,price5=".$razm_price5." ,description='".$_POST['razm_descr']."' where id=".$updateid;




    echo($sql);
    $res=$mysqli->query($sql);
    if ($updateid=="") {    $ok=$mysqli->insert_id;
    if ($ok!=0) echo ('Добавлено');}
    else $ok=$updateid;


    global $uploaddir;
    if (is_uploaded_file($_FILES["option1"]["tmp_name"]) )     {    $newname="option1".$tourid."_".rand(1,1000000).strrchr($_FILES['option1']['name'], ".");   addOneFoto("option1", $uploaddir.$newname);    }
    if (is_uploaded_file($_FILES["option2"]["tmp_name"]) )     {    $newname2="option2".$tourid."_".rand(1,1000000).strrchr($_FILES['option2']['name'], ".");   addOneFoto("option2", $uploaddir.$newname2);    }
    if (is_uploaded_file($_FILES["option3"]["tmp_name"]) )     {    $newname3="option3".$tourid."_".rand(1,1000000).strrchr($_FILES['option3']['name'], ".");   addOneFoto("option3", $uploaddir.$newname3);    }
    $addstr="";
    $addstr=$addstr.($newname!=""?",foto1='".$newname."' ":"");
    $addstr=$addstr.($newname2!=""?",foto2='".$newname2."' ":"");
    $addstr=$addstr.($newname3!=""?",foto3='".$newname3."' ":"");
    $addstr=substr($addstr,1);

    if ($addstr!="") {

        $sq = 'update add_services set ' . $addstr . " where id=" . $ok;
        echo($sq);
        $mysqli->query($sq);
    }

    if ($updateid!="")$tourid=$_GET['tourid'];


    echo('<a href="do2.php?edit=show&id='.$tourid.'">Вернуться к редактированию</a><br /><a target=_blank href="index.php?action=showatour&tournumber='.$tourid.'">Посмотреть на сайте</a>');

}

function addOneFoto($name, $nnewname)
{
    if (is_uploaded_file($_FILES[$name]["tmp_name"])) {

        if (move_uploaded_file($_FILES[$name]['tmp_name'], $nnewname)) {
            echo "Файл корректен и был успешно загружен.\n".$name." ".$nnewname;

        } else {
            echo "Возможная атака с помощью файловой загрузки!\n";
        }


    }
}

function addFoto()
{
global $uploaddir;
    global $mysqli;
    $tourid=$_POST["tourid"];
//    echo ($_SERVER['REQUEST_METHOD']);
    $uploadfile = $uploaddir .basename($_FILES['foto']['name']);
    $pname=$tourid."_".rand(1,1000000).strrchr($uploadfile, ".");
   // echo("!!!".$pname." === ".basename($_FILES['foto1']['name'])."!!!--!!");
    $newname="";
    $newname1="";
    $newname2="";
    $newname3="";

   // addOneFoto("foto", $newname);
    if (is_uploaded_file($_FILES["foto"]["tmp_name"]) )     {    $newname="main".$tourid."_".rand(1,1000000).strrchr($_FILES['foto']['name'], ".");   addOneFoto("foto", $uploaddir.$newname);    }
    if (is_uploaded_file($_FILES["foto1"]["tmp_name"]) )     {    $newname1="1i".$tourid."_".rand(1,1000000).strrchr($_FILES['foto1']['name'],".");    addOneFoto("foto1",$uploaddir.$newname1);    }
    if (is_uploaded_file($_FILES["foto2"]["tmp_name"]) )     {    $newname2="2".$tourid."_".rand(1,1000000).strrchr($_FILES['foto2']['name'],".");    addOneFoto("foto2", $uploaddir.$newname2);    }
    if (is_uploaded_file($_FILES["foto3"]["tmp_name"]) )     {    $newname3="3".$tourid."_".rand(1,1000000).strrchr($_FILES['foto3']['name'],".");    addOneFoto("foto3", $uploaddir.$newname3);    }

    $addstr="";

    $addstr=($newname!=""?",mainfoto='".$newname."' ":"");
    echo('<br/>!!!+'.$newname1."+!!!<br/>");


    $addstr=$addstr.($newname1!=""?",foto1='".$newname1."' ":"");
    $addstr=$addstr.($newname2!=""?",foto2='".$newname2."' ":"");
    $addstr=$addstr.($newname3!=""?",foto3='".$newname3."' ":"");

    $addstr=substr($addstr,1);
    echo($addstr);



    //echo ($newname." ".$newname1);
    if ($addstr!="") {
        $sql = "update tours set " . $addstr . " where id=" . $tourid;
        echo($sql);
        if (!$mysqli->query($sql)) echo('err');
    }
    echo('<a href="do2.php?edit=show&id='.$tourid.'">Вернутся к редактированию тура</a>');


    ?>
    <img width=200 src="img/<? echo ($newname); ?>" />
    <img width=200 src="img/<? echo ($newname1); ?>" />
    <img width=200 src="img/<? echo ($newname2); ?>" />
    <img width=200 src="img/<? echo ($newname3); ?>" />

    <?
}


function addDate()
{
    global $mysqli;
    $datins=$_POST['date1'];
    $tourid=$_POST['tourid'];
    $comment=$_POST['comment'];

    $sql="insert into dates (tourid,date, comment) values (".$tourid.",'".$datins."','".$comment."')";
    echo($sql);
    if ($mysqli->query($sql)) echo ('<A href="do2.php?edit=show&id='.$tourid.'"</a>Вернуться к редактированию тура</a>');
    //echo()

}

function updateDate()
{
    global $mysqli;

    $sq="update dates set date='".$_POST['date']."', comment='".$_POST['comment']."', realmaxlimit=".$_POST['realmaxlimit'].", limitperagency=".$_POST['limitperagency']." where id=".$_POST['did'];
    $res=$mysqli->query($sq);


 if ($res) echo ('ok') ; else echo ('nok'.$sq);

    echo ("<br /><a href='do2.php?edit=show&id=".$_POST['tid']."'>Редактировать</a>");


}

function editDate()
{
    global $mysqli;

    $did=$_GET['did'];
    $tid=$_GET['id'];
    $sq="select * from dates where id=".$did;
    echo ($sq);
    $rm=$mysqli->query($sq);
    $rl=$rm->fetch(PDO::FETCH_ASSOC);
    ?>
    <form action="do2.php?edit=updatedate" method="POST" enctype="multipart/form-data">

        <input type="hidden" name="did" value="<? echo ($did); ?> " /><br />
        <input type="hidden" name="tid" value="<? echo ($tid); ?> " /><br />
        Дата<br />
        <input type="text" name="date" value="<? echo ($rl['date']);?>" /><br />
        Число мест<br />
        <input type="text" name="realmaxlimit" value="<? echo ($rl['realmaxlimit']);?>" /><br />
        Лимит на агентство<br />
        <input type="text" name="limitperagency" value="<? echo ($rl['limitperagency']);?>" /><br />

        Комментарий к дате<br />

        <textarea name="comment" rows="20" cols="40"><? echo($rl['comment']); ?> </textarea><Br />
        <input type="submit" />



    </form>


    <?



}


function showPlaces($tid)

{
global $mysqli;

    $sq="select places.name, places.descr,places.id as pid from places places, tours_places tours_places where tours_places.placeid=places.id and tours_places.tourid=".$tid;
    $sq="select id, name, descr,0 from places pl1 where id in(select placeid from tours_places where tourid=)".$tid;


    $sq="select id, name, descr,1 as st from places  where id in( select placeid from tours_places where tourid=".$tid.") UNION select id, name, descr,0 as st from places  where id not in(select placeid from tours_places where tourid=".$tid.") ";


    $sq="select pk.id, pk.name, pk.descr,1 as st, pl.name as cname 
from places  pk left join places pl on pk.cityid=pl.id
where pk.id in( select placeid from tours_places where tourid=$tid  ) 
union
select pk.id, pk.name, pk.descr,0 as st, pl.name as cname 
from places  pk left join places pl on pk.cityid=pl.id
where pk.id not in( select placeid from tours_places where tourid=$tid  ) ";

   // echo($sq);
    try {

        $sqr = $mysqli->query($sq);
        //echo('12123');
        //var_dump($sqr);
        //$row=$sqr->fetch(PDO::FETCH_ASSOC);
        //echo('12124');
        while ($row=$sqr->fetch(PDO::FETCH_ASSOC))
        {
           echo ('<input type=checkbox name="place[]" value="'.($row['id']).'" '.($row['st']==1?"checked":"")."/>".$row['name']."(".$row['cname'].")"."<br />");
        }
            }

    catch
        (Exception $e) {
            echo("!!!!!!!!!!!!".$e->getMessage());
        }

}

function deleteDate($did, $id)
{
//echo ($id);
    $sq='delete from dates where id='.$did;
    global $mysqli;
    if ($mysqli->query($sq)) echo ('Удалено'); else echo ('ошибка '.$sq);
    echo ('<a href="do2.php?edit=show&id='.$id.'">Вернуться к редактированию</a>');
}

function show2Update($tid)
{
    global $mysqli;
    $sql="select * from tours where id=".$tid;
   echo($sql);
    try
    {
        $res3 = $mysqli->query($sql);
    }
    catch (Exception $e) {
        echo($e->getMessage()); //выведет \\\"Exception message\\\"
    }
    //if (!$res2) echo("err");
    while ($row = $res3->fetch(PDO::FETCH_ASSOC)) {

        echo("<table><form action=do2.php?edit=update name=tour method=POST enctype=multipart/form-data>");

        ?>

        <input type="hidden" value="<? echo($tid); ?> " name="id"/>
        <tr>
            <td>Название тура</td>
            <td><input type="text" cols=80 width="80" value="<? echo($row['title']); ?>" name="title"/></td>
        </tr>
        <tr>
            <td>Описание тура <br/>(не программа и не краткое описание тура)</td>
            <td><textarea cols=80 rows=20 name="tdescription"><? echo($row['description']); ?></textarea></td>
        </tr>

        <tr>
            <td>Программа. (Перенос строки - &lt;br &gt;</td>
            <td><textarea cols=80 rows=20 name="program"><? echo($row['program']); ?></textarea></td>
        </tr>

        <tr>
            <td>Описание тура для jumbotron</td>
            <td><textarea cols=80 rows=20 name="maindescr"><? echo($row['main_descr']); ?></textarea></td>
        </tr>
        <tr>
            <td>Базовая цена - на  основной сайт</td>
            <td><input type="text" width=40 name="bprice" value="<? echo($row['baseprice']); ?>"/></td>
        </tr>
        <tr>
            <td>Цена1 - цена для постоянных клиентов и партнеров, МР (вызывается &molodrus=1)</td>
            <td><input type="text" width=40 name="price1" value="<? echo($row['price1']); ?>"/></td>
        </tr>

        <tr>
            <td>Цена2 - </td>
            <td><input type="text" width=40 name="price2" value="<? echo($row['price2']); ?>"/></td>
        </tr>

        <tr>
            <td>Цена3 - цена для туроператоров</td>
            <td><input type="text" width=40 name="price3" value="<? echo($row['price3']); ?>"/></td>
        </tr>

        <tr>
            <td>Цена4 - цена оплаты через наш сайт для купонов:гилмон, скидкабум, пк</td>
            <td><input type="text" width=40 name="price4" value="<? echo($row['price4']); ?>"/></td>
        </tr>

        <tr>
            <td>Цена5 - сумма предоплаты за тур где применимо (МР) и аналоги</td>
            <td><input type="text" width=40 name="price5" value="<? echo($row['price5']); ?>"/></td>
        </tr>
        <tr>
            <td>Продолжительность, дней</td>
            <td><input type="text" cols=40 name="blength" value="<? echo($row['blength']); ?>"/></td>
        </tr>
        <tr>
            <td>Продолжительность, ночей</td>
            <td><input type="text" cols=40 name="nights" value="<? echo($row['nights']); ?>"/></td>
        </tr>

        <tr>
            <td>Что входит</td>
            <td><textarea name="binclude" rows=5 cols=80><? echo($row['include']); ?></textarea></td>
        </tr>
        <tr>
            <td>Что не входит</td>
            <td><textarea name="bexclude" rows=5 cols=80><? echo($row['exclude']); ?></textarea></td>
        </tr>
        <tr><td>Категория</td><td>
                <select name="type">
                    <option value="1" <?  echo(($row['type']==1?"selected":""));   ?>>Паломническая поездка</option>
                    <option value="2" <?  echo(($row['type']==2?"selected":""));   ?>>Экскурсионная поездка</option>
                    <option value="3" <?  echo(($row['type']==3?"selected":""));   ?>>Прогулка</option>
                    <option value="4" <?  echo(($row['type']==4?"selected":""));   ?>>Мастер-класс</option>
                    <option value="5" <?  echo(($row['type']==5?"selected":""));   ?>>Трудническая поездка</option>
                </select>
            </td></tr>
        <tr><Td><br />Опубликован<br /></Td><td><input type="checkbox" name="visible" value="1" <? echo($row['visible']==1?"checked":"");?> />

                </td></tr>



        <tr>
            <td>Отметьте места</td><td><?php

                showPlaces($tid);


                ?>
            </td>

        </tr>

        </table>
        <input type="submit"/>
        </form><br/>
        <?php

        addRezervField($tid);

        ?>

        <br/>

        <p>Загрузка фото. Первое - главное</p>
        <img src="img/<? echo ($row['mainfoto']); ?>" />
        <img src="img/<? echo ($row['foto1']); ?>" />
        <img src="img/<? echo ($row['foto2']); ?>" />
        <img src="img/<? echo ($row['foto3']); ?>" />
        <form method="POST" enctype="multipart/form-data" action="do2.php?edit=addfoto">

            <input type="hidden" name="tourid" value="<? echo($tid); ?>"/>
            <input type="file" name="foto"/>
            <input type="file" name="foto1"/>
            <input type="file" name="foto2"/>
            <input type="file" name="foto3"/>

            <input type="submit"/></form>

        <p>Даты туров. Ввод строго в формате ГГГГ-ДД-ММ, например 2016-06-17 - это 17 июня</p>
        <?php

    }
        $sql="SELECT id,comment, day(date) as day, month(date) as month, year(date) as year FROM `dates` where  year(date)=2016 and tourid=".$tid;
  //  echo($sql."<br />");

        $rdate=$mysqli->query($sql);
    //echo($rdate->num_rows);
    for($i=1;$i<=$rdate->num_rows;$i++)
    {
        try {
            $row_date = $rdate->fetch(PDO::FETCH_ASSOC);

        echo($row_date['day'].".".$row_date['month'].".".$row_date['year']." ".$row_date['comment']." <a href='do2.php?edit=editdate&id=".$tid."&did=".$row_date['id']."'>Редактировать</a>"." <a target=_blank href='do2.php?edit=deletedate&id=".$tid."&did=".$row_date['id']."'>Удалить</a><br />");
    }
    catch (Exception $e) {echo($e->getMessage());}
    }

    /*
    while ($row_date = $rdate->fetch(PDO::FETCH_ASSOC)) {
        echo($row_date['date']." <a target=_blank href='do2.php?edit=deletedate&did=".$row_date['id'].">Удалить</a><br />");

    }*/

        ?>

        <p>Добавить дату тура</p>
     <form method="POST" enctype="multipart/form-data"  action="do2.php?edit=adddate">
            <input type="hidden" name="tourid" value="<? echo($tid); ?>" />
Дата: <input type="text" name="date1" /><br />
         <textarea name="comment" ></textarea>
         <input type="submit"/></form>
        <br /><a href="do2.php?edit=addnew">Добавить новый тур</a>&nbsp;<a href="do2.php?edit=showlist">Список туров</a>
        <?


        //$title=$row['title'];
        //echo( strlen($title));
        //echo(isset($row['included']));

        //echo ($title);
       // echo($row['title']."дата");




}

function showAdd()
{
    ?>
    <h1>Добавление туров</h1>
    <form action="do2.php?edit=add" name="tour" method="POST" enctype="multipart/form-data">
        <input type="hidden" value="action" name="add"/>
        <table>
            <tr>
                <td>Название тура</td>
                <td><input type="text" width=40 name="title"/></td>
            </tr>
            <tr>
                <td>Описание тура</td>
                <td><textarea cols=80 rows=20 name="tdescription"></textarea></td>
            </tr>
            <tr>
                <td>Базовая цена</td>
                <td><input type="text" width=40 name="bprice"/></td>
            </tr>
            <tr>
                <td>Продолжительность</td>
                <td><input type="text" cols=40 name="blength"/></td>
            </tr>
            <tr>
                <td>Что входит</td>
                <td><textarea name="binclude" rows=5 cols=80/></textarea></td>
            </tr>
            <tr>
                <td>Что не входит</td>
                <td><textarea name="bexclude" rows=5 cols=80/></textarea></td>
            </tr>
        </table>
        <input type="submit"/>

    </form>
    <a href="do2.php?edit=showlist">Просмотр списка туров</a>

    <?
}


function showTours()
{
global $mysqli;
    
    $sq = "select * from tours";
    try
    {
    $res2 = $mysqli->query($sq);
    }
    catch (Exception $e) {
    echo $e->getMessage(); //выведет \\\"Exception message\\\"
    }
    //if (!$res2) echo("err");

    echo("<table>");
    while ($row = $res2->fetch(PDO::FETCH_ASSOC)) {
        echo("<tr><td><a href='do2.php?edit=show&id=" . $row['id'] . "'>" . $row['type']." ".$row['title'] . "</a></td><td>" . $row['main_descr'] . "</td><td>" . $row['baseprice'] . "</td></tr>");
    }

    echo("</table><a href=\"do2.php?edit=addnew\">Добавить новый тур</a>");
    $res2->close();

}



function AddtoBase()
{
    global $mysqli;
    $sql = "";
        $sql = "insert into tours (title, description, blength, baseprice,include, exclude) values (" . "'" . $_POST['title'] . "','" . $_POST['tdescription'] . "'," . $_POST['blength'] . "," . $_POST['bprice'] .",'".$_POST['winclude']."','".$_POST['wexclude']."')";
       echo($sql);
    try {
        $res = $mysqli->exec($sql);

        if ($res)  echo('<br />Тур добавлен'); else echo ('Ошибка при добавлении тура');
        if ($res) {

            $rs=$mysqli->query("SELECT LAST_INSERT_ID() as lid");
            $rmm=$rs->fetch(PDO::FETCH_ASSOC);
             if ($res) echo('<br /><a href="do2.php?edit=show&id=' .$rmm['lid']. '">Редактировать тур</a><br />');
        }
    }
    catch (Exception $e) {echo ($e.getMessage()."err");

    }
?>
<a href="do2.php?edit=showlist">Просмотр списка туров</a><br /><a href="do2.php?edit=addnew">Добавить новый тур</a>

<?php

}




function UpdateBase($uid)
{
    if (strlen($uid)>0) {
        global $mysqli;
        $sql = "";
        $sql = "update tours ";
        $sql = $sql . " set title='" . $_POST['title'] . "'";
        if (strlen($_POST['tdescription']) > 0) $sql = $sql . " ,description='". $_POST['tdescription'] . "' ";
        if (strlen($_POST['binclude']) > 0) $sql = $sql . " ,include='" . $_POST['binclude'] . "' ";
        if (strlen($_POST['bexclude']) > 0) $sql = $sql . " ,exclude='" . $_POST['bexclude'] . "' ";
        if (strlen($_POST['maindescr']) > 0) $sql = $sql . " ,main_descr='" . $_POST['maindescr'] . "' ";
        if (strlen($_POST['program']) > 0) $sql = $sql . " ,program='" . $_POST['program'] . "' ";
        if (strlen($_POST['bprice']) > 0) $sql = $sql . ' ,baseprice=' . $_POST['bprice'] . ' ';
        if (strlen($_POST['nights']) > 0) $sql = $sql . ' ,nights=' . $_POST['nights'] . ' ';
        if (strlen($_POST['price1']) > 0) $sql = $sql . ' , price1=' . $_POST['price1'] . ' ';
        if (strlen($_POST['price2']) > 0) $sql = $sql . ' , price2=' . $_POST['price2'] . ' ';
        if (strlen($_POST['price3']) > 0) $sql = $sql . ' , price3=' . $_POST['price3'] . ' ';
        if (strlen($_POST['price4']) > 0) $sql = $sql . ' , price4=' . $_POST['price4'] . ' ';
        if (strlen($_POST['price5']) > 0) $sql = $sql . ' , price5=' . $_POST['price5'] . ' ';
        if (strlen($_POST['blength']) > 0) $sql = $sql . ' ,blength=' . $_POST['blength'] . ' ';
        if (strlen($_POST['type']) > 0) $sql = $sql . ' ,type=' . $_POST['type'] . ' ';
        if ($_POST['visible']==1) $sql=$sql.", visible=1"; else $sql=$sql.", visible=0";
        echo($_POST['visible']."visivle");
        $sql = $sql . " where id=" . $uid;
        //$sql = "update tours set title='".$_POST['title'] ."' whe , description, blength, baseprice,include, exclude) values (" . "'" . $_POST['title'] . "','" . $_POST['tdescription'] . "'," . $_POST['blength'] . "," . $_POST['bprice'] ."'".$_POST['winclude']."','".$_POST['wexclude']."')";
      //echo($sql);
        if ($mysqli->query($sql)) echo('Обновлена база');
        $plc=$_POST['place'];
/*        echo($plc[0]."!");
        echo($plc[1]."!!");
        echo($plc[2]);*/
        if (!empty($plc))
        {
//            echo('222');
            $N = count($plc);
            $sq="delete from tours_places where tourid=".$uid;
            $mysqli->query($sq);
            for($i=0; $i < $N; $i++)
            {

                $sq="insert into tours_places (tourid,placeid)values(".$uid.",".$plc[$i].")";
                $mysqli->query($sq);
                echo($sq);
            }

        }

        echo("<a href='do2.php?edit=show&id=$uid'>Редактировать</a><a target=_blank href='http://www.elitsy.ru/palomnichestvo/tours/palomnik/$uid'>На сайт</a>");

    }
}

if (strcmp($_GET['edit'],"add")==0) {
    echo("добавление");
    AddtoBase();
}

if (strcmp($_GET['edit'],"addfoto")==0) {
    echo("загрузка файла");
    AddFoto();
}


if (strcmp($_GET['edit'],"update")==0) {
    echo("обновление");

if (strlen($_POST['id']>0)) {UpdateBase($_POST['id']); }
}

if (strcmp($_GET['edit'],"delete")==0) {
    echo("удаление");

}

if (strcmp($_GET['edit'],"show")==0) {
    echo("показ");
    show2Update($_GET['id']);
}

if (strcmp($_GET['edit'],"addnew")==0) {
    echo("добавление нового");
    showAdd();
}

if (strcmp($_GET['edit'],"showlist")==0) {
    showTours();
}


if (strcmp($_GET['edit'],"adddate")==0) {
    addDate();
}




if (strcmp($_GET['edit'],"updatedate")==0) {
    updateDate();
}


if (strcmp($_GET['edit'],"editdate")==0) {
    editDate();
}



if (strcmp($_GET['edit'],"deletedate")==0) {
    deleteDate($_GET['did'],$_GET['id']);
}

if (strcmp($_GET['edit'],"addrazm")==0) {
    addrazm();
}

        if (strcmp($_GET['edit'],"updaterazm")==0) {
            addrazm($_GET['rid']);
        }




        if (strcmp($_GET['edit'],"editrazm")==0) {
    editrazm($_GET['id']);
}

if (strcmp($_GET['edit'],"deleterazm")==0) {
    deleteRazm($_GET['id'], $_GET['rid']);
}


//showTours();

//showAdd();

$mysqli->close();


?>

<a href="do2.php?edit=showlist">Показать спиcок</a>
