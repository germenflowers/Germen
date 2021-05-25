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
        'FILTER_NAME' => 'historyFilter',
        'PAGER_ID' => 'admin-order-history',
        'PAGE_ELEMENT_COUNT' => 20,
        'SEF_FOLDER' => '/admin/history/',
        'SEF_URL_TEMPLATES' => array(
            'list' => '',
            'element' => '#ID#/',
        ),
        'SET_STATUS_404' => 'Y',
        'SHOW_404' => 'Y',
        'FILE_404' => '',
        'DISPLAY_BOTTOM_PAGER' => 'Y',
        'DISPLAY_TOP_PAGER' => 'N',
        'PAGER_BASE_LINK_ENABLE' => 'N',
        'PAGER_DESC_NUMBERING' => 'N',
        'PAGER_DESC_NUMBERING_CACHE_TIME' => 36000,
        'PAGER_SHOW_ALL' => 'N',
        'PAGER_SHOW_ALWAYS' => 'N',
        'PAGER_TEMPLATE' => 'ajax',
        'PAGER_TITLE' => '',
        'CACHE_FILTER' => 'Y',
        'CACHE_GROUPS' => 'Y',
        'CACHE_TIME' => 3600,
        'CACHE_TYPE' => 'A',
    ),
    false
); ?>
<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php';
?>