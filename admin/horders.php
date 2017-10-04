<?php

require_once "../public_html/palomnichestvo/classes/db.php";
require_once "../public_html/palomnichestvo/classes/main.php";



$sq="select * from orders ord
join add_u_reserves aur on ord.id=aur.orderid
join add_services ads on aur.service_id=ads.id join
clients on clients.id=ord.uid
  join places pl on pl.id=ads.placeid

where ads.type=3 and ifnull(ord.hid,0)>0 order by orderid desc
";
$rt=db::query2($sq);

if ($rt) $qt=$rt->fetchAll();


if (isset($qt) && count($qt)>0 ) {
    echo ('<table border="1">');
    foreach ($qt as $qline )
    {
        echo('<Tr><td>'.$qline["prepaysum"]).'</td><td>'.$qline["name"].'</td><td>'.$qline["phone"].'</td><td>'.$qline["hid"].'</td><Td>'.$qline["sdate"]."-".$qline["edate"]." ".$qline["title"].'*'.$qline["value"].'</td><td>'.$qline["regdate"].'</td></Tr>';

    }
    echo('</table>');

}