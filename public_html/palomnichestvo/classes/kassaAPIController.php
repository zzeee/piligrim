<?php

/**
 * Created by PhpStorm.
 * User: леново
 * Date: 12.08.2017
 * Time: 13:36
 */

require_once 'YaMoneyCommonHttpProtocol.php';

class Settings {

    public $SHOP_PASSWORD = "trampam";
    public $SECURITY_TYPE;
    public $LOG_FILE;
    public $SHOP_ID = 141183;
    public $CURRENCY = 10643;
    public $request_source;
    public $mws_cert;
    public $mws_private_key;
    public $mws_cert_password = "123456";

    function __construct($SECURITY_TYPE = "MD5" /* MD5 | PKCS7 */, $request_source = "php://input") {
        $this->SECURITY_TYPE = $SECURITY_TYPE;
        $this->request_source = $request_source;
        $this->LOG_FILE = dirname(__FILE__)."/mws/log.txt";
        $this->mws_cert = dirname(__FILE__)."/mws/shop.cer";
        $this->mws_private_key = dirname(__FILE__)."/mws/private.key";
    }
}


class kassaAPIController
{


    public function __construct(Slim\Container  $container) {
        $this->container = $container;
    }

        public function checkOrder($request, $response, $args)
    {
        //echo('!!!!');
        $settings = new Settings();
        $yaMoneyCommonHttpProtocol = new YaMoneyCommonHttpProtocol("checkOrder", $settings,0);
        $yaMoneyCommonHttpProtocol->processRequest($_REQUEST);

    }

    public function paymentAviso($request, $response, $args)
    {
        $settings = new Settings();
        $yaMoneyCommonHttpProtocol = new YaMoneyCommonHttpProtocol("paymentAviso", $settings,0);
        $yaMoneyCommonHttpProtocol->processRequest($_REQUEST);


    }
       public function test_checkOrder($request, $response, $args)
    {
        //echo('!!!!');
        $settings = new Settings();
        $yaMoneyCommonHttpProtocol = new YaMoneyCommonHttpProtocol("checkOrder", $settings,1);
        $yaMoneyCommonHttpProtocol->processRequest($_REQUEST);

    }

    public function test_paymentAviso($request, $response, $args)
    {
        $settings = new Settings();
        $yaMoneyCommonHttpProtocol = new YaMoneyCommonHttpProtocol("paymentAviso", $settings,1);
        $yaMoneyCommonHttpProtocol->processRequest($_REQUEST);


    }


}