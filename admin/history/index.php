<?php

/**
 * @global CMain $APPLICATION
 * @global CMain $USER
 */

use \Germen\Admin\Rights;

require_once $_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php';

$APPLICATION->SetTitle('История');

if ($USER->IsAuthorized() === false) {
    LocalRedirect('/admin/login/');
    exit;
}

$rights = new Rights();
if (!$rights->checkHistoryRights('view')) {
    LocalRedirect('/');
    exit;
}
?>
<?php $APPLICATION->IncludeComponent(
    'germen:admin.order.history',
    '',
    array(
        'CACHE_FILTER' => 'Y',
        'CACHE_GROUPS' => 'Y',
        'CACHE_TIME' => 3600,
        'CACHE_TYPE' => 'A',
        'DISPLAY_BOTTOM_PAGER' => 'Y',
        'DISPLAY_TOP_PAGER' => 'N',
        'FILE_404' => '',
        'FILTER_NAME' => 'historyFilter',
        'MESSAGE_404' => '',
        'PAGER_BASE_LINK_ENABLE' => 'N',
        'PAGER_DESC_NUMBERING' => 'N',
        'PAGER_DESC_NUMBERING_CACHE_TIME' => 36000,
        'PAGER_ID' => 'admin-order-history',
        'PAGER_SHOW_ALL' => 'N',
        'PAGER_SHOW_ALWAYS' => 'N',
        'PAGER_TEMPLATE' => 'ajax',
        'PAGER_TITLE' => '',
        'PAGE_ELEMENT_COUNT' => 20,
        'SEF_FOLDER' => '/admin/history/',
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