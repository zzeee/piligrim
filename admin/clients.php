<?php
define("IN_ADMIN", TRUE);
require "sqli.php";
require "newconnect-head.php";
$sq='select * from u_reserves where sourcesyst="biglion"';

global $mysqli;

$rs=$mysqli->query($sq);

echo ("<pre>");
while($r=$rs->fetch_assoc())
{

    echo ('"'.$r['fio'].'"'.",".'"'.$r['phone'].'"'.",".'"'.$r['codes'].'"'.",".'"'.$r['email'].",".'"'.$r['turdate'].'"'.",".'"'.$r['turid'].'"'."\n");


}