<?php
require_once "../classes/MailChimp.php";
$rt=new MailChimp("284e76c5201169006ec6f90867ef7d86-us13");

$result = $rt->get('lists');

print_r($result);


?>