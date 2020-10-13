<?php

use \Bitrix\Main\Loader;

Loader::includeModule('germen.orderscalendar');

$menu[] = array(
    'parent_menu' => 'global_menu_store',
    'sort' => 101,
    'text' => 'Календарь заказов',
    'title' => 'Календарь заказов',
    'icon' => 'sale_menu_icon_orders',
    'page_icon' => 'sale_page_icon_orders',
    'url' => 'germen_orders_calendar.php',
    'more_url' => array(),
    'items_id' => 'menu_orderscalendar',
    'module_id' => 'germen.orderscalendar',
);

return $menu;