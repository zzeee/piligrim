<?php
ini_set('display_errors','On');
error_reporting('E_ALL');
define("IN_ADMIN", TRUE);
require "sqli.php";

/*
Корневые классы:
Main - суперкласс
pointOfInterest - точка интереса с географическими координатами
saints - классы, связанные со святостью

tours - тур
media - медиа-хранилище: фотографии, звук, видео
user - пользователь
order - заказ
payments - корневой класс всего что связано с платежами
trip - конкретная поездка
*/
class main
{
    var $sqlci;
    function __construct($sqli)
    {
        $this->sqlci=$sqli;
    }
    function query($sq)
    {
        $rt=0;
       // echo('1');
        try {
            $rt = $this->sqlci->query($sq);

         //   echo('2');
        }
        catch(Exception $e)
        {
            echo($e->getMessage());
        }
       // echo('3');
      //  var_dump($rt);
        return $rt;
    }


    function getPoint($id)
    {
        return new pointOfInterest($this->sqlci,$id);
    }

    function getSaint($id)
    {
     //   echo($id);
        return new saints($this->sqlci,$id);
    }

    function getSaintsIds()
    {
        $sq='select id from saints where visible=1';
        echo($sq);

        $rt=$this->query($sq);
        //var_dump($rt);
        echo(count($rt));
        $res=array();
        while($rm=$rt->fetch_assoc())
        {
            try {
                ECHO ($rm instanceof mysqli_result);
                $val = $rm['id'];
                echo('5');
                echo($val);
            }
            catch(Exception $e) {
                echo('2323');
                echo($e->getMessage());}

            array_push($res,$val);
        }
    return $res;
    }

    function addUser($phone, $pwd)
    {

    }

}

trait Base {
    public function dlog($txt){
        echo($txt);
    }
    public function query($sq) {
        $mysqlic=$this->mysqlci;
     //   echo($sq);
    //   var_dump($mysqlic);
        $rt=0;
        try {
            $rt = $mysqlic->query($sq);
            }
        catch(Exception $e)
        {
            echo($e->getMessage());
            $this->dlog($e->getMessage());
        }
        return $rt;
    }

    public function getTitle(){
        return $this->title;
    }
    function getDescription(){
        return $this->description;
    }
}

class pointOfInterest
{
    use Base;
    var  $title;
    private $description;
    private $lon;
    private $lat;
    private $address;
    private $mysqlci;
    private $type;
    private $rm;

    function __construct($sqli, $id)
    {
        $this->mysqlci=$sqli;
        $sq='select * from places where id='.$id;
        $rt=$this->query($sq);
        $rm=$rt->fetch_assoc();
        $this->title=$rm['name'];
        $this->description=$rm['descr'];
        $this->type=$rm['type'];
        $this->lon=$rm['lon'];
        $this->lat=$rm['lat'];
        $this->address=$rm['address'];
        $this->rm=rm;
    }

    function getLon(){
        return $this->lon;
    }
    function getLat(){
        return $this->lat;
    }
    function getAddress(){
        return $this->address;
    }

    function getType(){
        return $this->type;
    }

    function getEvents()
    {

    }
}
class saints
{
    use Base;
    private $mysqlci;
    private $rm;
    private $title;
    private $description;

    function __construct($sqli, $id)
    {
        $this->mysqlci=$sqli;
        $sq='select * from saints where id='.$id;
        $rt=$this->query($sq);
        $this->rm=$rt->fetch_assoc();
        $this->title=$this->rm['title'];
        $this->description=$this->rm['description'];
    }
}

class Tours
{


}

class Order
{


}

class Trip
{


}


class User
{
    function __construct($id)
    {

    }

}


class icon
{
    function __construct($title) {}
}

class Monastery extends pointOfInterest
{
    function __construct ($sqli, $id) {
        parent::__construct($sqli, $id);
    }

    function getTemples()
    {    }

    function getSaints()
    {    }

    function getMedia()
    {    }
}

class temple extends pointOfInterest
{
    function __construct ($sqli, $id) {
        parent::__construct($sqli, $id);
    }

    function getSaints()
    {
    }

    function getMedia()
    {
    }

}

class chapel extends pointOfInterest
{
    function __construct ($title, $description, $lon, $lat,$address) {
        parent::__construct($title, $description, $lat, $lon, $address);
    }
}

class building extends pointOfInterest
{
    function __construct ($title, $description, $lon, $lat,$address) {
        parent::__construct($title, $description, $lat, $lon, $address);
    }
}

class monument extends pointOfInterest
{
    function __construct ($title, $description, $lon, $lat,$address) {
        parent::__construct($title, $description, $lat, $lon, $address);
    }
}

class nativeMonument extends pointOfInterest
{
    function __construct ($title, $description, $lon, $lat,$address) {
        parent::__construct($title, $description, $lat, $lon, $address);
    }
}

