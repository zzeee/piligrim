<?php
require_once "classes/db.php";
require_once "classes/main.php";


use PHPUnit\Framework\TestCase;


class orderTest extends TestCase
{

    public function testgetOrder()
    {
        $order=new order();
        $rm=$order->showOne(324);
        $rq=$rm["order"][0]["id"];
        $this->assertEquals('324',$rq);
    }

    public function testgetOrder2()
    {
        $order=new order();
        $rm=$order->showOne(328);
        $rq=$rm["order"][0]["id"];
        $this->assertEquals('328',$rq);
    }


}
?>