<?php
$request = json_decode(file_get_contents("php://input"));
if ( $handle = fopen($_SERVER["DOCUMENT_ROOT"].'/upload/logs.txt', 'a+') ) {
    fwrite($handle, print_r($request, true));
    fclose($handle);
}
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/tinkoff.payment/install/sale_payment/tinkoff/notification.php");
?>