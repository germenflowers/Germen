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

$APPLICATION->SetTitle('Восстановление пароля');
?>
<?php $APPLICATION->IncludeComponent(
    'bitrix:main.auth.forgotpasswd',
    '',
    array(
        'AUTH_AUTH_URL' => '/admin/login/',
        'AUTH_REGISTER_URL' => '/admin/registration/',
    )
); ?>
<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php';
?>