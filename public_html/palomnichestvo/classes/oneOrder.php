<?php

/**
 * Created by PhpStorm.
 * User: леново
 * Date: 11.12.2016
 * Time: 20:26
 */
class oneOrder
{
    private $oid;
    private $order="";
    private $order_tourlines="";
    private $order_products;
    private $order_rules;


    function __construct ($oid)
    {
        $this->oid=$oid;

        $sq='select * from orders where id='.$oid;
        $rt=db::query2($sq);
        if ($rt)
        $this->order=$rt->fetchAll();
        $sq2='select * from u_reserves where orderid='.$oid;
        $rm=db::query2($sq2);
        if ($rm) $this->tourlines=$rm->fetchAll();
        $sq3='select * from add_u_reserves where orderid'.$oid;
        $rtt=db::query2($sq3);
        if ($rtt) $this->products=$rtt->fetchAll();

        /*
        $sq2='select * from my_reserves where orderid in (select id from orders where uid='.$this->uid.')';
        //echo($sq2);
        $rtt = db::query2($sq2);
        $res=[];
        $dat=[];
        //  foreach ($rtt as $rmm) {            array_push($res,$rmm);        }
        //var_dump($res);
        if ($rtt) $this->ordertourlines=$rtt->fetchAll();


        $sq3="select * from add_u_reserves where orderid in (select id from orders where uid=$this->uid)";
        $rt3=db::query2($sq3);
        if ($rt3) $this->products=$rt3->fetchAll();

        $sq4="SELECT * FROM zzeeee_tours.bills where orderid=$this->uid";
        $rt4=db::query2($sq4);
        if ($rt3) $this->bills=$rt4->fetchAll();*/
    }


    function updateTourLine($lineid, $tourid, $datid, $conf)
    {
        $fio="";
        if (isset($conf["fio"])) $fio=$conf["fio"];
        $phone="";
        if (isset($conf["phone"])) $fio=$conf["phone"];
        $email=" ";
        if (isset($conf["email"])) $fio=$conf["email"];
        $conf1=json_encode($conf);
        $sq="update u_reserves set tourid=$tourid, turdate=$datid, orderid=$this->oid, config='$conf1', fio='$fio', phone='$phone', email='$email' where id=$lineid";
        echo($sq);
    }

    function updateProductLine($lineid, $productid, $num, $conf)
    {
        $conf1=json_encode($conf);
        $sq="update add_u_reserves set service_id=$productid, orderid=$this->orderid, config='$conf1', value=$num";
        echo($sq);
    }



    function addTourLine($tourid, $datid, $conf)
    {
        $fio="";
        if (isset($conf["fio"])) $fio=$conf["fio"];
        $phone="";
        if (isset($conf["phone"])) $fio=$conf["phone"];
        $email=" ";
        if (isset($conf["email"])) $fio=$conf["email"];
        $conf1=json_encode($conf);

        $sq="insert into u_reserves (turid, turdate, orderid, config, fio, phone, email) values($tourid, $datid, $this->oid, '$conf1', '$fio', '$phone', '$email')";
        $rm=db::query2($sq);
        $resn=db::lastInsertId();
        //echo($sq." ".$resn);

        if ($rm) return $resn;

        return false;

         }

    function getTourLines()
    {
        return $this->tourlines;
    }

    function getProductLines()
    {
        $this->products;
    }

    function addProductLine($productid, $num, $conf)
    {
        $conf1=json_encode($conf);

        $sq="insert into add_u_reserves (service_id, orderid, config, value) values($productid, $this->oid, '$conf1', $num)";

        $rm=db::query2($sq);
        $resn=db::lastInsertId();
       // echo($sq." ".$resn);

        if ($rm) return $resn;

        return false;

    }


    function updateOrderLine($id, $params=array())
    {

    }

    function getOrderLine($id)
    {

    }


}