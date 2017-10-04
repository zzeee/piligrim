<?php
/**
 * Created by PhpStorm.
 * User: леново
 * Date: 08.09.2016
 * Time: 22:42
 */




/*
function sendSms($phones, $smstext)
{
    global $mysqli;
    $ch = curl_init("http://sms.ru/sms/send");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    curl_setopt($ch, CURLOPT_POSTFIELDS, array(
        "api_id" => "daf70479-e2bf-32c4-518b-9f3e7fadd7c2",
        "to" => $phones,
        "from"=>"nov-rus.ru",
        "text" => $smstext
        //  "text"		=>	iconv("windows-1251","utf-8","Привет!")
    ));
    $body = curl_exec($ch);
    $res=substr($body,4, 14);
    //   echo ("!".$res."!");

    $sq="insert into smshistory(phones, smstext, date, status) values('".$phones."','".$smstext."',now(),'".$res."' )";
    $mysqli->query($sq);


    //   echo ($body);
    curl_close($ch);

}



function generate_password1($number)
{
    $arr = array('a','b','c','d','e','f',
        'g','h','i','j','k','l',
        'm','n','o','p','r','s',
        't','u','v','x','y','z',
        'A','B','C','D','E','F',
        'G','H','I','J','K','L',
        'M','N','O','P','R','S',
        'T','U','V','X','Y','Z',
        '1','2','3','4','5','6',
        '7','8','9','0');
    // Генерируем пароль
    $pass = "";
    for($i = 0; $i < $number; $i++)
    {
        // Вычисляем случайный индекс массива
        $index = rand(0, count($arr) - 1);
        $pass .= $arr[$index];
    }
    return $pass;
}


function resetPassword($id)
{
    global $mysqli;


    $pwd=generate_password1(6);
    $sq="update clients set pwd='".$pwd."' where id=".$id;
    $mysqli->query($sq);
    return $pwd;
}
*/
function getTourDataById($id)
{
    global $mysqli;
    $sq="select * from tours where id=".$id;
    $rs=$mysqli->query($sq);

    $rl=$rs->fetch_assoc();


    return $rl;
}


function checkMr()
{

    global $molodrus;
    $ttype=$_SESSION['type'];
    if ($ttype=="1") return 1;
    return( $_GET['molodrus']);
}

function checkAction()
{

    return( $_GET['actionprice']);
}



?>