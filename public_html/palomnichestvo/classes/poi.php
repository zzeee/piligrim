<?php
/**
 * Created by PhpStorm.
 * User: zzeee
 * Date: 30.11.2016
 * Time: 22:57
 */

class pointOfInterest
{
    private $title;
    private $description;
    private $lon;
    private $lat;
    private $address;
    private $type;
    private $rm;

    function __construct($id)
    {
        $sq='select id, name, descr, lon,lat, address, type from places where id='.$id;
        $rt=db::query($sq);
        $rm=$rt->fetch_assoc();
        $this->title=$rm['name'];
        $this->description=$rm['descr'];
        $this->type=$rm['type'];
        $this->lon=$rm['lon'];
        $this->lat=$rm['lat'];
        $this->address=$rm['address'];
        $this->rm=$rm;
    }

    public function __get($property)
    {
       // echo('---- '.$property);
        if (property_exists($this, $property)) {
            return $this->$property;
        }
    }


    function getEvents()
    {

    }
}
