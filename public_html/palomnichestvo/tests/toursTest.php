<?php
require_once "classes/db.php";
require_once "classes/main.php";

use PHPUnit\Framework\TestCase;

/**
 * Created by PhpStorm.
 * User: леново
 * Date: 16.07.2017
 * Time: 12:58
 */
class toursTest extends TestCase
{

    public function testGetPoints()
    {
        $tc=new tours();
        $rm=$tc->getTourLocations(4);
        //$rty=intVal($rm["id"]);
        //$this->assertArraySubset(["tourid"=>4],$rm);
        $checktour=4;
        $test=function ($n)  use ($checktour)
        {
            $rt=array_intersect_assoc(["tourid"=>$checktour ],$n);
            if (count($rt)>0)  { return true;}
            return false;
        };
       $newarr=array_filter($rm, $test);
        $this->assertGreaterThan(0,count($newarr));
    }

    public function testAddGetDelete()
    {
        $tc=new tours();
        $checkplace=9999;
        $checktour=4;
        $test=function ($n)  use ($checkplace)
        {
            $rt=array_intersect_assoc(["placeid"=>$checkplace],$n);
            if (count($rt)>0)  {return true;}
            return false;
        };
        $rm=$tc->addTourLocations($checktour,$checkplace);
        $rm2=$tc->getTourLocations($checktour);

        $this->assertInternalType("int", $rm);
        $this->assertInternalType("array", $rm2);
        $this->assertGreaterThan(0,count(array_filter($rm2, $test)));
        $tc->deleteTourLocations($checktour,$checkplace);

        $rm3=$tc->getTourLocations($checktour);
        $this->assertEquals(0,count(array_filter($rm3, $test)));
    }

  public function testDates()
  {
    $tc = new tours();
    $param1=(Array)json_decode('{"date":"2017-06-28","tourid":"30","maxplaces":12,"comment":"задем ..."}');
    $rm=$tc->getTourDates(30);
    $this->assertInternalType("array", $rm);
    $last=count($rm);
    //$this->assertEquals(4, count($rm));
    $newdateid=$tc->addTourDate($param1);
    $rm2=$tc->getTourDates(30);
    $this->assertInternalType("array", $rm2);
    $this->assertEquals($last+1, count($rm2));
    


    $tc->delTourDate($newdateid);
    $rm3=$tc->getTourDates(30);
    $this->assertInternalType("array", $rm3);
    $this->assertEquals($last, count($rm3));



    //var_dump($newid);
  }

  public function testAddServices()
  {
    $tc=new tours();

    $testparam=(Array)json_decode('{"tourid":36,"title":"теплые носки","description":"носки в дорогу","price":2856,"type":2,"visible":0}');


    /*
    $rm=$tc->getAddServicesById(21);
    $this->assertInternalType("array", $rm);
    $qt=$rm["tourid"];
    $this->assertEquals($qt, 36);*/
    $rm2=$tc->getAddServicesByTourId($testparam["tourid"]);
    $this->assertInternalType("array", $rm2);
    $startlen=count($rm2);
    $newservice=$tc->addAddService($testparam);
    $rm3=$tc->getAddServicesByTourId($testparam["tourid"]);
    $this->assertInternalType("array", $rm3);
    $this->assertEquals(count($rm3), $startlen+1);
    $rmt=$tc->getAddServicesById($newservice);
    $this->assertEquals($rmt["title"], $testparam["title"]);
    $this->assertEquals($rmt["description"], $testparam["description"]);
    $this->assertEquals($rmt["price"], $testparam["price"]);
    $this->assertEquals($rmt["type"], $testparam["type"]);
    $this->assertEquals($rmt["visible"], $testparam["visible"]);

    $testparam2=(Array)json_decode('{"tourid":36, "id":'.$newservice.',"title":"очень теплые носки","description":"самые носки в дорогу","price":12856,"type":3,"visible":1}');
    $tc->updateAddService($testparam2);



    $rmt2=$tc->getAddServicesById($newservice);


    $this->assertEquals($rmt2["title"], $testparam2["title"]);
    $this->assertEquals($rmt2["description"], $testparam2["description"]);
    $this->assertEquals($rmt2["price"], $testparam2["price"]);
    $this->assertEquals($rmt2["type"], $testparam2["type"]);
    $this->assertEquals($rmt2["visible"], $testparam2["visible"]);


    $tc->delAddServiceById($newservice);
    $rm4=$tc->getAddServicesByTourId(36);
    $this->assertInternalType("array", $rm4);
    $this->assertEquals(count($rm4), $startlen);
  }

}
