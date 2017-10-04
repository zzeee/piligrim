<?php

/**
 * Created by PhpStorm.
 * User: zzeee
 * Date: 31.01.2017
 * Time: 0:31
 */

//require_once 'PHPUnit/Framework.php';
require_once "../public_html/classes/db.php";
require_once "../public_html/classes/main.php";
require_once "../public_html/classes/saints.php";
require_once "../public_html/classes/monastery.php";
require_once "../public_html/classes/pointsList.php";
require_once "../public_html/classes/tours.php";
require_once "../public_html/classes/users.php";
require_once "../public_html/classes/orders.php";
require_once "../public_html/classes/turspace.php";
require_once "../public_html/classes/products.php";
require_once "../public_html/classes/configurator.php";
require_once "../public_html/classes/oneOrder.php";
require_once "../public_html/classes/price.php";
require_once "../public_html/classes/toptours.php";
require_once "../public_html/classes/seo.php";
require_once "../public_html/classes/databuilder.php";

class databuilderTest extends PHPUnit_Framework_TestCase
{
public function testQuery()
{
    $rt = databuilder::getQuery("main",1);
    $this->assertEquals("mainok", $rt["testdata"]);
}

}
