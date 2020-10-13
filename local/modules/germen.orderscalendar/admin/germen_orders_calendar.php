<?php

use \Bitrix\Main\Loader;
use \Bitrix\Main\Page\Asset;
use \Germen\Orderscalendar\Tools;
use \Germen\Orderscalendar\Order;

require_once $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_before.php';

$saleModulePermissions = $APPLICATION::GetGroupRight('sale');
if ($saleModulePermissions < 'W') {
    $APPLICATION->AuthForm(GetMessage('ACCESS_DENIED'));
}

Loader::includeModule('germen.orderscalendar');

$tools = new Tools();
$modulePath = $tools->getModulePath(false);

Asset::getInstance()->addJs($modulePath.'/admin/js/vendor/fullcalendar/main.js');
Asset::getInstance()->addJs($modulePath.'/admin/js/vendor/fullcalendar/locales/ru.js');
Asset::getInstance()->addJs($modulePath.'/admin/js/vendor/popper.min.js');
Asset::getInstance()->addJs($modulePath.'/admin/js/vendor/tippy.min.js');
Asset::getInstance()->addJs($modulePath.'/admin/js/script.js');

$APPLICATION->SetTitle('Календарь заказов');
$APPLICATION->SetAdditionalCSS($modulePath.'/admin/js/vendor/fullcalendar/main.css');
$APPLICATION->SetAdditionalCSS($modulePath.'/admin/css/style.css');

require $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_after.php';
?>
<div id='germen-orders-calendar-loading' class='germen-orders-calendar-loading'>loading...</div>
<div id='germen-orders-calendar-warning' class='germen-orders-calendar-warning'></div>
<div id='germen-orders-calendar' class='germen-orders-calendar'></div>