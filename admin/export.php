<?php
define("IN_ADMIN", TRUE);
require "sqli.php";
require "commlib.php";

global $mysqli;

//$user=$_GET['uid'];


function makeXmlTours()
{

    global $mysqli;
//$sq="select id, tourid from dates where date>now()  and tourid in (select id from tours where visible=1 and type in (1,2))";
$sq="select * from tours where visible=1 and type in (1,2) and id in (select tourid from dates where date>now())";
    $res=$mysqli->query($sq);
    $xml='<?xml version="1.0" encoding="utf-8"?>';





    $xml.="<tours>";
    while($tour=$res->fetch_assoc())
    {
//        $tour=getTourDataById($rs['tourid']);


        $xml.='<tour id="'.$tour['id'].'">';
        $xml.="<title>".$tour['title'].'</title>';
        $xml.="<program><![CDATA[".$tour['program'].']]></program>';
        $xml.="<main_foto>".$tour['mainfoto'].'</main_foto>';
        $xml.="<description>".$tour['description'].'</description>';
        $xml.="<sdescription>".$tour['main_descr'].'</sdescription>';

        $sq1='select * from dates where date>now() and tourid='.$tour['id'];

        echo($sq1);
        $rmi=$mysqli->query($sq1);
        $dates="";
        while($rm=$rmi->fetch_assoc())
        {
            //echo('1');
            $dates.=$dates.",".$rm['date'];
        }
        $dates=substr($dates,1);
        $xml.="<dates>".$dates."</dates>";
        //$rmi->close();


        $xml.="<price>".$tour['baseprice'].'</price>';
        $xml.="<include>".$tour['include'].'</include>';
        $xml.="<exclude>".$tour['exclude'].'</exclude>';

        $xml.="</tour>";
     //   echo ($rs['day']." ".$rs['month'].' '.$tour['title']);

    }
$xml.="</tours>";
    return $xml;
}


function getTourArr($id)
{
//echo('1');
    global $mysqli;
    $result=array();
    $sq="select * from tours where visible=1 and id=".$id;

    $res=$mysqli->query($sq);
    if ($res->num_rows>0){
        $rm=$res->fetch_array();
        $result["title"]=$rm['title'];
        $result["days"]=$rm['blength'];
        $result["foto"]='http://www.nov-rus.ru/img/'.$rm['mainfoto'];

        $result["price"]=$rm['baseprice'];
        $result["description"]=$rm['description'];
        $result["short_description"]=$rm['main_desc'];
        $result["program"]=$rm['program'];
        $result["include"]=$rm['include'];
        $result["exclude"]=$rm['exclude'];
        $dates=array();
        $date="";

        $sq2="SELECT id,comment, day(date) as day, month(date) as month, year(date) as year FROM `dates` where  id not in (select tid from stops) and date>now() and tourid=".$id;
        $rq=$mysqli->query($sq2);

        if ($rq->num_rows>0) {
            $i=0;
            while ($row = $rq->fetch_assoc()) {
                $date[$i]=$row['day'].".".$row['month'].".".$row['year'];
                //echo ($date['data']);
                $i++;


            }

        }
        $result["dates"]=$date;

    }
    return $result;
}

$user=str_replace("'","",$user);

//if($user=='') die();
//$sq="select * from main_users where md5='".$user."'";
//echo ($sq);

//$res=$mysqli->query($sq);

//if ($res->field_count>0)
//{

//    $rm=$res->fetch_array();
    //   echo ($rm['company']);
    // echo (json_encode($rm));
  //  $dataa="";
    $dataa=$_GET['action'];
    switch ($dataa):
        case "getJtour":  $dat=getTourArr($_GET['tid']); echo (json_encode($dat));    break;
        case "getJactualtours": echo (json_encode(getToursList()));break;
        case "getxmltours": echo (makeXmlTours()) ; break;
    endswitch;





//}
//else echo ('Ошибка авторизации. Обратитесь к менеджеру');

?>