<?php
require "classlib.php";


/**
 * Created by PhpStorm.
 * User: Zienko
 * Date: 29.11.2016
 * Time: 17:14
 */


$rmq = new main($mysqli);
$rs=$rmq->getPoint(1);
/*
    echo($rs->getTitle()."<br />");
    echo($rs->getDescription()."<br />");
    echo($rs->getLat()."<br />");
    echo($rs->getLon()."<br />");
    echo($rs->getAddress()."121<br />");
    echo($rs->getType()."<br />");
*/
$rs1=$rmq->getSaint(1);
echo($rs1->getTitle()."<br />");
echo($rs1->getDescription()."<br />");


$rs3=$rmq->getSaintsIds();
echo ($rs3[1]);
$rs1=$rmq->getSaint($rs[0]);
echo($rs1->getTitle()."<br />");
echo($rs1->getDescription()."<br />");


//$rmq->addUser('89161243243','23423');