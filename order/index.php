<?php

/**
 * @global CMain $APPLICATION
 */

require_once $_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php';

$APPLICATION->SetTitle('Оформление заказа');
?>
<?php $APPLICATION->IncludeComponent(
    'pdv:order',
    '',
    array(
        'ID' => (int)$_REQUEST['id'],
        'COMPONENT_TEMPLATE' => '.default',
    ),
    false
); ?>
<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php';
?>