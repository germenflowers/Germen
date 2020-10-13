<?php

$moduleId = 'germen.orderscalendar';

if (file_exists($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/'.$moduleId.'/')) {
    $path = $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/'.$moduleId.'/admin/germen_orders_calendar.php';
} elseif (file_exists($_SERVER['DOCUMENT_ROOT'].'/local/modules/'.$moduleId.'/')) {
    $path = $_SERVER['DOCUMENT_ROOT'].'/local/modules/'.$moduleId.'/admin/germen_orders_calendar.php';
}

require_once $path;
