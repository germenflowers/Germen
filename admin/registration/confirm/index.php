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
    'bitrix:system.auth.confirmation',
    '',
    array(
        'CONFIRM_CODE' => 'confirm_code',
        'LOGIN' => 'login',
        'USER_ID' => 'confirm_user_id',
        'AUTH_URL' => '/admin/login/',
        'SUCCESS_URL' => '/admin/',
    ),
    false
); ?>
<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php';
?>