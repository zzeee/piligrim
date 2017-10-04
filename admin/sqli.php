<?php
/*
ini_set('error_reporting', E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
*/
//phpinfo();
if(!defined("IN_ADMIN")) die;
try {
    //$mysqli = new mysqli("localhost", "elitsy", "Tkbws12!", "elitsy");

    $mysqli = new PDO('mysql:host=127.0.0.1;port=3306;dbname=elitsy;charset=UTF8;','elitsy','Tkbws987654!');

}
catch (Exception $e) {echo("Технические проблемы");}

if ($mysqli->connect_error) {

    mail("zzeee@gmail.com", "Connect Error" , $mysqli->connect_errno . " ".$mysqli->connect_error); }

$uploaddir="/var/www/elitsy/public_html/palomnichestvo/img/";
$companyinfo=Array();
//echo('2!!!!!!!!!!32');


session_start(); ?>