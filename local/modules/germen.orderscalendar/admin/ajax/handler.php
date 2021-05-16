<?php

use \Bitrix\Main\Loader;
use \Germen\Orderscalendar\Order;
use \Germen\Orderscalendar\Tools;
use \Germen\Orderscalendar\Event;

define('NO_AGENT_CHECK', true);
define('DisableEventsCheck', true);
define('NO_KEEP_STATISTIC', true);
define('NO_AGENT_STATISTIC', true);
define('NOT_CHECK_PERMISSIONS', true);
define('STOP_STATISTICS', true);
define('PERFMON_STOP', true);
define('SM_SAFE_MODE', true);

require_once $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php';

$saleModulePermissions = $APPLICATION::GetGroupRight('sale');
if ($saleModulePermissions < 'W') {
    die(json_encode(array('error' => true)));
}

if (!Loader::includeModule('germen.orderscalendar')) {
    die(json_encode(array('error' => true)));
}

if (empty($_GET['start']) || empty($_GET['end'])) {
    die(json_encode(array('error' => true)));
}

$rangeStart = Tools::parseDateTime($_GET['start']);
$rangeEnd = Tools::parseDateTime($_GET['end']);

$timeZone = null;
if (isset($_GET['timeZone'])) {
    $timeZone = new DateTimeZone($_GET['timeZone']);
}

$order = new Order($_GET['admin-section'] === 'Y');
$items = $order->getOrdersFilterDeliveryDate($rangeStart->getTimestamp(), $rangeEnd->getTimestamp());
$events = $order->formatOrdersToEvents($items);

$result = array();
foreach ($events as $item) {
    $event = new Event($item, $timeZone);

    if ($event->isWithinDayRange($rangeStart, $rangeEnd)) {
        $result[] = $event->toArray();
    }
}

die(json_encode($result));
