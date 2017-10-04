<?php
define("IN_ADMIN", TRUE);
require "sqli.php";
require "newconnect-head.php";

global $mysqli;
$sq="insert into emails(email) values('".$_GET['email']."')";
$mysqli->query($sq);

//echo "<pre>";
//	print_r($_POST);
//echo "</pre>";

?>Вы были успешно подписаны на новости!