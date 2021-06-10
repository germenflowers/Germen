<?php

/**
 * @global CMain $APPLICATION
 * @global CMain $USER
 */

use \Germen\Admin\Rights;

require_once $_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php';

$APPLICATION->SetTitle('Заказы');

if ($USER->IsAuthorized() === false) {
    LocalRedirect('/admin/login/');
    exit;
}

$rights = new Rights();
if (!$rights->checkOrdersRights('view')) {
    LocalRedirect('/');
    exit;
}
?>
<?php $APPLICATION->IncludeComponent(
    'germen:admin.order',
    '',
    array(
        'CACHE_FILTER' => 'N',
        'CACHE_GROUPS' => 'Y',
        'CACHE_TIME' => 3600,
        'CACHE_TYPE' => 'N',
        'FILE_404' => '',
        'MESSAGE_404' => '',
        'SEF_FOLDER' => '/admin/orders/',
        'SEF_MODE' => 'Y',
        'SEF_URL_TEMPLATES' => array(
            'element' => '#ID#/',
            'list' => '',
        ),
        'SET_STATUS_404' => 'Y',
        'SHOW_404' => 'Y',
    ),
    false
); ?>
<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php';
?>