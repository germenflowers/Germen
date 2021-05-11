<?php

/**
 * @global CMain $APPLICATION
 * @global CMain $USER
 */

require_once $_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php';

LocalRedirect('/admin/');
exit;

if ($USER->IsAuthorized() === false) {
    LocalRedirect('/admin/login/');
    exit;
}

$APPLICATION->SetTitle('Профиль');
?>
<?php $APPLICATION->IncludeComponent(
    'bitrix:main.profile',
    '',
    array(
        'CHECK_RIGHTS' => 'N',
        'SEND_INFO' => 'N',
        'SET_TITLE' => 'N',
        'USER_PROPERTY' => array(),
        'USER_PROPERTY_NAME' => '',
    )
); ?>
<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php';
?>