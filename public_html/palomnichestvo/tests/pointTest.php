<?php
/**
 * Created by PhpStorm.
 * User: леново
 * Date: 15.07.2017
 * Time: 18:04
 */
require_once "classes/db.php";
require_once "classes/main.php";


use PHPUnit\Framework\TestCase;

class pointTest extends TestCase
{

    public function testPointGet()
    {
        $tc=new pointsList();
        $rm=$tc->getOne(1);
        $rty=intVal($rm["id"]);
        $this->assertEquals(1,$rty);
    }

    public function testPointSetGet()
    {
        //Подготовка
        $tc=new pointsList();
        $rty=$tc->newPoint("test", 1);
        $this->assertInternalType("int",$rty);
        $param2='{"id":"'.$rty.'","elitsy_url":"9999","name":"123321QRQ","descr":"qwerty12345","d_author":"a12345","address":"add12345","main_descr":"main12345","lat":"444","lon":"444"}';
        $params=(Array)json_decode($param2,false);
        $req=$tc->updatePoint($params);
        $req1=json_decode($req, true);
        $this->assertInternalType("string",$req);
        $rm=$tc->getOne($rty);
        $tc->delPoint($rty);
//Сравнение
        $rqq=$req1["status"];
        $this->assertEquals("ok",$rqq);
        $this->assertEquals(intVal($rm["id"]),$rty);
        $this->assertEquals($params["main_descr"],$rm["main_descr"]);
        $this->assertEquals($params["name"],$rm["name"]);
        $this->assertEquals($params["descr"],$rm["descr"]);
        $this->assertEquals($params["d_author"],$rm["d_author"]);
        $this->assertEquals($params["address"],$rm["address"]);
        $this->assertEquals($params["lat"],$rm["lat"]);
        $this->assertEquals($params["lon"],$rm["lon"]);
    }


}
