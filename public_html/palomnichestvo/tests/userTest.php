<?php
require_once "classes/db.php";
require_once "classes/main.php";

/**
 * Created by PhpStorm.
 * User: леново
 * Date: 14.07.2017
 * Time: 22:58
 */

use PHPUnit\Framework\TestCase;

class usersTest extends TestCase
{


    public function testElUser()
    {
        $order=new users();
        $rm=$order->regElUser(46564);



        $this->assertEquals('1788',intVal($rm["id"]));
        $this->assertEquals('46564',intVal($rm["eluser"]));

    }


}
