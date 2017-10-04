<?php
//require_once "../classes/db.php";
//require_once "../classes/main.php";
echo "SEND";
/**
 * Created by PhpStorm.
 * User: леново
 * Date: 20.08.2017
 * Time: 13:33
 */


//echo "\n".$argv[1]."\n";
//echo $argv[2]."\n";

echo (count($argv));
if (count($argv==3))
{

$phones=$argv[1];
$smstext=$argv[2];

if (function_exists("curl_init")) {
    //var_dump($phones);

    $ch = curl_init("http://sms.ru/sms/send");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);
    $pararr=array(
        "api_id" => "daf70479-e2bf-32c4-518b-9f3e7fadd7c2",
        "to" => $phones,
        "from" => "nov-rus.ru",
        "text" => $smstext
        //  "text"		=>	iconv("windows-1251","utf-8","Привет!")
    );
    echo(json_encode($pararr));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $pararr);
    $body = curl_exec($ch);
    //$res = substr($body, 4, 14);
    //$sq = "insert into smshistory(phones, smstext, date, status) values('" . $phones . "','" . $smstext . "',now(),'" . $res . "' )";
    //db::query2($sq);
    //echo ($body);
    curl_close($ch);
} else {
    return 0;
}

} else die(22);