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
        'DISPLAY_BOTTOM_PAGER' => 'Y',
        'DISPLAY_TOP_PAGER' => 'N',
        'FILE_404' => '',
        'FILTER_NAME' => 'filter',
        'MESSAGE_404' => '',
        'PAGER_BASE_LINK_ENABLE' => 'N',
        'PAGER_DESC_NUMBERING' => 'N',
        'PAGER_DESC_NUMBERING_CACHE_TIME' => 36000,
        'PAGER_ID' => 'admin-order',
        'PAGER_SHOW_ALL' => 'N',
        'PAGER_SHOW_ALWAYS' => 'N',
        'PAGER_TEMPLATE' => 'ajax',
        'PAGER_TITLE' => '',
        'PAGE_ELEMENT_COUNT' => 999999,
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