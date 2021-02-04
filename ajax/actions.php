<?php

use \Bitrix\Main\Application;
use \Bitrix\Main\Web\Cookie;
use \PDV\Tools;

require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php');

set_time_limit(0);

global $USER;
global $APPLICATION;

$request = Application::getInstance()->getContext()->getRequest();

$result = array(
    'data' => '',
    'error' => false,
);

try {
    $action = htmlspecialchars($request->getPost('action'));
    $id = (int)$request->getPost('id');
    $isOrder = $request->getPost('order') === 'Y';

    if ($id <= 0) {
        throw new \Exception();
    }

    switch ($action) {
        case 'setDelivery':
            $context = Application::getInstance()->getContext();
            $cookie = new Cookie('USER_DELIVERY_ID', $id);
            $cookie->setDomain($_SERVER['SERVER_NAME']);
            $cookie->setHttpOnly(false);
            $context->getResponse()->addCookie($cookie);
            $context->getResponse()->flush('');

            $deliveryTimes = Tools::getDeliveryTime();
            $result['data'] = $deliveryTimes[$id];

            break;
        case 'getDetail':
            ob_start();

            $wishlist = array();
            if (!empty($_COOKIE['wishlist'])) {
                $wishlist = array_map('intval', array_filter(explode('|', $_COOKIE['wishlist'])));
            }

            $APPLICATION->IncludeComponent(
                'bitrix:catalog.element',
                'popup',
                array(
                    'IBLOCK_ID' => IBLOCK_ID__CATALOG,
                    'ELEMENT_ID' => $id,
                    'PROPERTY_CODE' => array('IMAGES', 'SOSTAV'),
                    'ACTION_VARIABLE' => 'action_var',
                    'CACHE_TYPE' => 'A',
                    'CACHE_TIME' => 36000000,
                    'CACHE_GROUPS' => 'Y',
                    'SET_TITLE' => 'N',
                    'PRICE_CODE' => array('BASE'),
                    'USE_PRICE_COUNT' => 'N',
                    'SHOW_PRICE_COUNT' => 1,
                    'PRICE_VAT_INCLUDE' => 'Y',
                    'PRICE_VAT_SHOW_VALUE' => 'N',
                    'USE_PRODUCT_QUANTITY' => 'Y',
                    'PRODUCT_PROPERTIES' => array(),
                    'CONVERT_CURRENCY' => 'N',
                    'HIDE_NOT_AVAILABLE' => 'N',
                    'USE_ELEMENT_COUNTER' => 'Y',
                    'IS_ORDER' => $isOrder,
                    'CACHE_CLEANER' => $request->getPost('clear_cache') === 'Y' ? random_int(1, 100) : '',
                    'WISHLIST' => $wishlist,
                ),
                false
            );

            $result['data'] = ob_get_clean();

            break;
    }
} catch (\Exception $e) {
    $result = array(
        'data' => '',
        'error' => true,
    );
}

echo json_encode($result);