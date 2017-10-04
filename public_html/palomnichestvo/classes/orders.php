<?php

/**
 * Created by PhpStorm.
 * User: Zienko
 * Date: 06.12.2016
 * Time: 12:15
 *
 * СПИСОК ЗАКАЗОВ ПОЛЬЗОВАТЕЛЯ (!)
 * РАБОТАЕТ ТОЛЬКО ПОСЛЕ ТОГО КАК ИЗВЕСТЕН ID
 */
class orders
{
    var $orders;
    var $ordertourlines;
    var $products;
    var $bills;
    var $uid;

    function __construct($uid)
    {
        $this->uid = $uid;
        $this->load();
    }

    function load()
    {
        $sq = 'SELECT id FROM orders WHERE uid=' . $this->uid;
        $rt = db::query2($sq);
        $res = [];
        $dat = [];
        foreach ($rt as $rm) {
            array_push($res, $rm);
        }
        $this->orders = $res;
        $sq2 = 'SELECT * FROM my_reserves WHERE orderid IN (SELECT id FROM orders WHERE uid=' . $this->uid . ')';
        $rtt = db::query2($sq2);
        $res = [];
        $dat = [];
        if ($rtt) $this->ordertourlines = $rtt->fetchAll();
        $sq3 = "select * from add_u_reserves where orderid in (select id from orders where uid=$this->uid)";
        $rt3 = db::query2($sq3);
        if ($rt3) $this->products = $rt3->fetchAll();
    }



        function showOne($id)
    {
        //  echo($this->uid.'!!!!!!'.$id);

        $sq = "select 
 tours.*,
    dates.*,
    orders.*
 
 from orders
  join dates on dates.id=orders.dateid
join tours on tours.id=dates.tourid
where orders.id=$id
";
        $rs = db::query2($sq);
        if ($rs) $res["tourdata"] = $rs->fetch(PDO::FETCH_ASSOC);
        $sq2 = "select * from u_reserves where orderid=$id";
        $rs2 = db::query2($sq2);

        if ($rs2) {
            $res["tour_reserves"] = $rs2->fetchAll();
        }

        $sq3 = "select * from add_u_reserves  au
join add_services ads on au.service_id=ads.id and au.orderid=$id";

        $rs3 = db::query2($sq3);
        if ($rs3) {

            $res["tour_services"] = $rs3->fetchAll();
        }


        //return $this->ordertourlines[$key];
//echo ($res["tour_reserves"]);
        // echo("<hr />".json_encode($this->ordertourlines)."<hr />");
        $res["ordertourlines"] = $this->ordertourlines;
        $res["orderproductlines"] = $this->products;
        return $res;
    }


    function getList()
    {
        $sq = "select orders.id as order_id, orders.uid,orders.dateid, orders.dtime, oid.totprice, oid.cnt,  ifnull(orders.status,0) as status   from orders join 
(select count(id) as cnt, sum(ifnull(price,0)) as totprice, orderid from u_reserves where ifnull(deleted,0)<>1 group by orderid ) as oid on
orders.id=oid.orderid where oid.cnt>0 and orders.uid=$this->uid  order by dtime desc";

        $sq = "select orders.id as order_id,photos.name as mainfoto, orders.psum as psum,orders.prepaysum as prepaysum,  dates.date,tours.title, tours.baseprice as baseprice,tours.id, orders.uid,orders.dateid, orders.dtime, oid.totprice, oid.cnt,  ifnull(orders.status,0) as status   from orders
  join   (select count(id) as cnt, sum(ifnull(price,0)) as totprice, orderid from u_reserves where ifnull(deleted,0)<>1 group by orderid ) as oid on                                                                                                                                             orders.id=oid.orderid
join dates on orders.dateid=dates.id
join tours on dates.tourid=tours.id
left join ( SELECT
           min(sorder),
           any_value(id)   AS id,
           tid,
           any_value(name) AS name
         FROM photos
         WHERE tid > 0
         GROUP BY tid) as photos on photos.tid=tours.id

where oid.cnt>0 and orders.uid=$this->uid  order by dtime desc;
";


        $rs = db::query2($sq);
        $res = [];
        if ($rs) $res = $rs->fetchAll();
        return $res;
    }

    function getOne($orderid)
    {
        $sq = "select ur.id as rid, ur.fio,  ur.price, ur.turdate, ur.reservedate, ur.turid, ur.uid, tr.title, tr.description, tr.blength, tr.baseprice, tr.main_descr, ph.name as pname, ph.id as pid from u_reserves ur 
left join tours tr on tr.id=ur.turid
left join dates dr on dr.id=ur.turdate
left join (select count(id) as mi,any_value(id) as id,tid as tid, any_value(name) as name from photos where tid>0
  group by tid) as ph on ph.tid=tr.id
where ur.orderid=$orderid ";
        $rs = db::query2($sq);
        $res = [];
        if ($rs) $res = $rs->fetchAll();
        return $res;
    }



}