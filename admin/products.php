<?php

/**
 * Created by PhpStorm.
 * User: леново
 * Date: 29.09.2016
 * Time: 2:36
 */
class products
{

    public $products;
    public $photos;
    protected $mysqlil;

    function __construct($mysqli)
    {
        $this->mysqlil = $mysqli;
        $sq = 'select * from add_services where tourid=0 and visible=1';
        $this->products = $this->mysqlil->query($sq);
        $sq2='select * from photos';
        $this->photos=$this->mysqlil->query($sq);
    }


    private function query($sq)
    {
        return $this->mysqlil->query($sq);
    }

    function showAllProducts()
    {


    }

    function showProduct($id)
    {
        $sq='select * from add_services where id='.$id;
        $r1=$this->query($sq);

        $rm=$r1->fetch_assoc();
        $sq2='select * from photos where asid='.$id;
        $r2=$this->query($sq2);

        while($rtt=$r2->fetch_assoc()) {
            if ($rtt['asid']==$id) {
                            echo('<span></span><a><img src="img/'.$rtt['name'].'" width="200"/> <br />'.$rm['price'].' руб. </a></span>');


            }
        }



    }


}