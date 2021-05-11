<?php

/**
 * @global CMain $APPLICATION
 * @global CMain $USER
 */

require_once $_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php';

LocalRedirect('/admin/');
exit;

if ($USER->IsAuthorized() === true) {
    LocalRedirect('/admin/');
    exit;
}

$APPLICATION->SetTitle('Регистрация');
?>
<?php $APPLICATION->IncludeComponent(
    'bitrix:main.register',
    '',
    array(
        'AUTH' => 'Y',
        'REQUIRED_FIELDS' => array(),
        'SET_TITLE' => 'N',
        'SHOW_FIELDS' => array(),
        'SUCCESS_PAGE' => '',
        'USER_PROPERTY' => array(),
        'USER_PROPERTY_NAME' => '',
        'USE_BACKURL' => 'N',
        'AUTH_URL' => '/admin/login/',
    )
); ?>
<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php';
?>