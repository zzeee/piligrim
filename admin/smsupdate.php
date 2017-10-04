<?php
define("IN_ADMIN", TRUE);
require "sqli.php";
require "newconnect-head.php";

global $mysqli;
foreach ($_POST["data"] as $entry) {
    $lines = explode("\n",$entry);
    if ($lines[0] == "sms_status") {

        $sms_id = $lines[1];
        $sms_status = $lines[2];
        $sq='update smshistory set bstatus='.$sms_status." where status='".$sms_id."'";
        $mysqli->query($sq);

        // "Изменение статуса. Сообщение: $sms_id. Новый статус: $sms_status";
        // Здесь вы можете уже выполнять любые действия над этими данными.
    }
}
echo "100"; /* Важно наличие этого блока, иначе наша система посчитает, что в вашем обработчике сбой */
?>