<?php

/**
 * @global CMain $APPLICATION
 * @global CMain $USER
 */

use \Bitrix\Main\Loader;
use \Bitrix\Main\Page\Asset;
use \Germen\Admin\Rights;
use \Germen\Orderscalendar\Tools;

require_once $_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php';

$APPLICATION->SetTitle('Календарь');

if ($USER->IsAuthorized() === false) {
    LocalRedirect('/admin/login/');
    exit;
}

$rights = new Rights();
if (!$rights->checkCalendarRights('view')) {
    LocalRedirect('/');
    exit;
}

if (!Loader::includeModule('germen.orderscalendar')) {
    echo 'Не подключен модуль germen.orderscalendar';
}

$tools = new Tools();
$modulePath = $tools->getModulePath(false);

Asset::getInstance()->addJs($modulePath.'/admin/js/vendor/fullcalendar/main.js');
Asset::getInstance()->addJs($modulePath.'/admin/js/vendor/fullcalendar/locales/ru.js');
Asset::getInstance()->addJs($modulePath.'/admin/js/vendor/popper.min.js');
Asset::getInstance()->addJs($modulePath.'/admin/js/vendor/tippy.min.js');
Asset::getInstance()->addJs($modulePath.'/admin/js/script.js');

$APPLICATION->SetAdditionalCSS($modulePath.'/admin/js/vendor/fullcalendar/main.css');
$APPLICATION->SetAdditionalCSS($modulePath.'/admin/css/style.css');
?>
<div id='germen-orders-calendar-loading' class='germen-orders-calendar-loading'>loading...</div>
<div id='germen-orders-calendar-warning' class='germen-orders-calendar-warning'></div>
<div id='germen-orders-calendar' class='germen-orders-calendar' data-adminSection="Y"></div>
<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php';
?>