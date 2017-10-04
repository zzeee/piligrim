<?
require_once "classes/db.php";
require_once "classes/main.php";
require_once "classes/saints.php";
require_once "classes/monastery.php";
require_once "classes/pointsList.php";
require_once "classes/redirector.php";
require_once "classes/auth.php";
require_once "classes/tours.php";
require_once "classes/users.php";
require_once "classes/orders.php";
require_once "classes/order.php";
require_once "classes/turspace.php";
require_once "classes/products.php";
require_once "classes/configurator.php";
require_once "classes/oneOrder.php";
require_once "classes/price.php";
require_once "classes/toptours.php";
require_once "classes/seo.php";
require_once "classes/databuilder.php";
require_once "tests/orderTest.php";
use PHPUnit\Framework\TestCase;


class orderTest2 extends TestCase
{

    public function testgetOrder()
    {
        /*
                $this->assertEquals(
                    'Auser@example.com',
                    order::getOrder('user@example.com')
                );
        */
        $this->assertEquals(
            'Auser@example.com',
            order::showOne(324)
        );
    }


}

