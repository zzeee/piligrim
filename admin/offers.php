<?php

require_once "../public_html/palomnichestvo/classes/db.php";
require_once "../public_html/palomnichestvo/classes/main.php";



$sq="select * from el_offer order by id desc";
$rt=db::query2($sq);

if ($rt) $qt=$rt->fetchAll();


if (isset($qt) && count($qt)>0 ) {
    echo ('<table>');
    foreach ($qt as $qline )
    {
        echo('<Tr><td>'.$qline["id"]).'</td><td>'.$qline["phone"].'</td><td>'.$qline["qtext"].'</td><td>'.$qline["dat"].'</td></Tr>';

    }
    echo('</table>');

}