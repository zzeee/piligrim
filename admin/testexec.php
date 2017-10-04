<?php
header("Access-Control-Allow-Origin: http://molodrus.ru");
header("Access-Control-Allow-Credentials: true");

$headers = apache_request_headers();

setcookie("test3","234322342");
echo (json_encode($headers));



?>
