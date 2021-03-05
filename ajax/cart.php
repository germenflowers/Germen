<?php

/**
 * Bitrix vars
 * @global CMain $APPLICATION
 */

use \Bitrix\Main\Context;
use \Bitrix\Main\Loader;
use \Bitrix\Main\LoaderException;
use \Bitrix\Main\ObjectNotFoundException;
use \Bitrix\Catalog\Product\Basket;
use \Bitrix\Sale\DiscountCouponsManager;
use \PDV\Tools;
use \Germen\Content;

if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || $_SERVER['HTTP_X_REQUESTED_WITH'] !== 'XMLHttpRequest') {
    die('No direct script access allowed');
}

define('NO_AGENT_CHECK', true);
define('DisableEventsCheck', true);
define('NO_KEEP_STATISTIC', true);
define('NO_AGENT_STATISTIC', true);
define('NOT_CHECK_PERMISSIONS', true);
define('STOP_STATISTICS', true);
define('PERFMON_STOP', true);
define('SM_SAFE_MODE', true);

require_once $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php';

/**
 * @param bool $isOrderPage
 * @return array
 */
function getCartData(bool $isOrderPage = false): array
{
    global $APPLICATION;

    $APPLICATION->IncludeComponent(
        'bitrix:sale.basket.basket',
        'ajax',
        array(
            'ACTION_VARIABLE' => '',
            'AUTO_CALCULATION' => 'N',
            'BASKET_IMAGES_SCALING' => '',
            'COLUMNS_LIST_EXT' => array(),
            'COLUMNS_LIST_MOBILE' => array(),
            'COMPATIBLE_MODE' => 'Y',
            'CORRECT_RATIO' => 'N',
            'DEFERRED_REFRESH' => 'N',
            'DISCOUNT_PERCENT_POSITION' => '',
            'DISPLAY_MODE' => '',
            'EMPTY_BASKET_HINT_PATH' => '',
            'GIFTS_BLOCK_TITLE' => '',
            'GIFTS_CONVERT_CURRENCY' => 'N',
            'GIFTS_HIDE_BLOCK_TITLE' => 'N',
            'GIFTS_HIDE_NOT_AVAILABLE' => 'N',
            'GIFTS_MESS_BTN_BUY' => '',
            'GIFTS_MESS_BTN_DETAIL' => '',
            'GIFTS_PAGE_ELEMENT_COUNT' => '',
            'GIFTS_PLACE' => '',
            'GIFTS_PRODUCT_PROPS_VARIABLE' => '',
            'GIFTS_PRODUCT_QUANTITY_VARIABLE' => '',
            'GIFTS_SHOW_DISCOUNT_PERCENT' => 'N',
            'GIFTS_SHOW_OLD_PRICE' => 'N',
            'GIFTS_TEXT_LABEL_GIFT' => '',
            'HIDE_COUPON' => 'N',
            'LABEL_PROP' => array(),
            'PATH_TO_ORDER' => '/order/',
            'PATH_TO_BASKET' => '/cart/',
            'PRICE_DISPLAY_MODE' => 'N',
            'PRICE_VAT_SHOW_VALUE' => 'N',
            'PRODUCT_BLOCKS_ORDER' => '',
            'QUANTITY_FLOAT' => 'N',
            'SET_TITLE' => 'N',
            'SHOW_DISCOUNT_PERCENT' => 'N',
            'SHOW_FILTER' => 'N',
            'SHOW_RESTORE' => 'N',
            'TEMPLATE_THEME' => '',
            'TOTAL_BLOCK_DISPLAY' => array(),
            'USE_DYNAMIC_SCROLL' => 'N',
            'USE_ENHANCED_ECOMMERCE' => 'N',
            'USE_GIFTS' => 'N',
            'USE_PREPAYMENT' => 'N',
            'USE_PRICE_ANIMATION' => 'N',
            'DISABLE_INIT_JS_IN_COMPONENT' => 'Y',
            'ORDER_PAGE' => $isOrderPage,
        )
    );

    return Tools::getStorage('cartData');
}

/**
 * @param int $id
 * @param array $cartItems
 * @return bool
 * @throws LoaderException
 * @throws ObjectNotFoundException
 */
function addUpsale(int $id, array $cartItems): bool
{
    $upsaleMaxQuantity = false;
    foreach ($cartItems as $cartItem) {
        if ($cartItem['productId'] === $id && $cartItem['quantity'] >= 5) {
            $upsaleMaxQuantity = true;
            break;
        }
    }

    if ($upsaleMaxQuantity) {
        return false;
    }

    $fields = array(
        'PRODUCT_ID' => $id,
        'QUANTITY' => 1,
        'PROPS' => array(
            array(
                'NAME' => 'Upsale',
                'CODE' => 'UPSALE',
                'VALUE' => true,
            ),
        ),
    );
    $result = Basket::addProduct($fields);

    return (bool)$result->isSuccess();
}

$request = Context::getCurrent()->getRequest();
$post = $request->getPostList();

if (!$request->isAjaxRequest()) {
    die(json_encode(array('status' => 'error', 'message' => 'No direct script access allowed')));
}

if (!Loader::includeModule('catalog')) {
    die(json_encode(array('status' => 'error', 'message' => 'Server Error')));
}

if (!Loader::includeModule('sale')) {
    die(json_encode(array('status' => 'error', 'message' => 'Server Error')));
}

$response = array('status' => 'error');

if (!empty((int)$_REQUEST['id'])) {
    if ($_REQUEST['action'] === 'add') {
        $cartItems = Content::getCartItems();

        $productId = (int)$_REQUEST['id'];

        $fields = array(
            'PRODUCT_ID' => $productId,
            'QUANTITY' => 1,
        );

        if (!empty((int)$_REQUEST['params']['quantity'])) {
            $fields['QUANTITY'] = (int)$_REQUEST['params']['quantity'];
        }

        if (!empty($_REQUEST['params']['cover'])) {
            $value = 'Без упаковки';
            if($_REQUEST['params']['cover'] === 'craft') {
                $value = 'Крафт';
            } elseif($_REQUEST['params']['cover'] === 'film') {
                $value = 'Пленка';
            }

            $fields['PROPS'] = array(
                array(
                    'NAME' => 'Упаковка',
                    'CODE' => 'COVER',
                    'VALUE' => $value,
                ),
            );
        }

        $thisSubscribeInCart = false;
        if($_REQUEST['params']['subscribe'] === 'Y') {
            foreach ($cartItems as $item) {
                if($item['productId'] === $productId) {
                    $thisSubscribeInCart = true;
                    break;
                }
            }

            $fields['PROPS'] = array(
                array(
                    'NAME' => 'Subscribe',
                    'CODE' => 'SUBSCRIBE',
                    'VALUE' => true,
                ),
                array(
                    'NAME' => 'Тип букета',
                    'CODE' => 'TYPE',
                    'VALUE' => $_REQUEST['params']['type'] === 'mono' ? 'Монобукеты' : 'Составные букеты',
                ),
                array(
                    'NAME' => 'Способ доставки',
                    'CODE' => 'DELIVERY',
                    'VALUE' => $_REQUEST['params']['delivery'] === 'assembled' ? 'Собранный букет' : 'Набор',
                ),
                array(
                    'NAME' => 'Размер',
                    'CODE' => 'SIZE',
                    'VALUE' => $_REQUEST['params']['size'] === 'standart' ? 'Стандартный' : 'Увеличенный',
                ),
            );
        }

        if (!$thisSubscribeInCart) {
            $result = Basket::addProduct($fields);
            if ($result->isSuccess()) {
                if (!empty($_REQUEST['params']['upsale'])) {
                    foreach (array_unique($_REQUEST['params']['upsale']) as $id) {
                        addUpsale((int)$id, $cartItems);
                    }
                }

                $response = array('status' => 'success', 'data' => getCartData());
            }
        }
    }

    elseif ($_REQUEST['action'] === 'addUpsale') {
        $cartItems = Content::getCartItems();

        $productId = (int)$_REQUEST['id'];

        if (addUpsale($productId, $cartItems)) {
            $response = array('status' => 'success', 'data' => getCartData());
        }
    }

    elseif ($_REQUEST['action'] === 'delete') {
        $CSaleBasket = new CSaleBasket();
        if ($CSaleBasket->Delete((int)$_REQUEST['id'])) {
            $cartItems = Content::getCartItems();

            $onlyUpsale = true;
            foreach ($cartItems as $item) {
                if (!$item['upsale']) {
                    $onlyUpsale = false;
                    break;
                }
            }

            if($onlyUpsale) {
                foreach ($cartItems as $item) {
                    $CSaleBasket->Delete((int)$item['id']);
                }
            }

            $response = array('status' => 'success', 'data' => getCartData());
        }
    }

    elseif ($_REQUEST['action'] === 'minus') {
        $filter = array('ID' => (int)$_REQUEST['id']);
        $select = array('QUANTITY');
        $result = CSaleBasket::GetList(array(), $filter, false, false, $select);
        if ($row = $result->Fetch()) {
            $quantity = (int)$row['QUANTITY'] - 1;
        }

        $CSaleBasket = new CSaleBasket();

        $success = false;
        $delete = false;
        if (!empty($quantity) && $quantity > 0) {
            $fields = array(
                'QUANTITY' => $quantity,
            );
            if ($CSaleBasket->Update((int)$_REQUEST['id'], $fields)) {
                $success = true;
            }
        } elseif ($CSaleBasket->Delete((int)$_REQUEST['id'])) {
            $success = true;
            $delete = true;
        }

        if ($success) {
            $response = array('status' => 'success', 'delete' => $delete, 'data' => getCartData());
        }
    }

    elseif ($_REQUEST['action'] === 'plus') {
        $isOrderPage = $_REQUEST['page'] === 'order';

        $filter = array('ID' => (int)$_REQUEST['id']);
        $select = array('QUANTITY');
        $result = CSaleBasket::GetList(array(), $filter, false, false, $select);
        if ($row = $result->Fetch()) {
            $quantity = (int)$row['QUANTITY'] + 1;
        }

        if (!empty($quantity) && $quantity > 0) {
            $CSaleBasket = new CSaleBasket();

            $fields = array(
                'QUANTITY' => $quantity,
            );
            if ($CSaleBasket->Update((int)$_REQUEST['id'], $fields)) {
                $response = array('status' => 'success', 'data' => getCartData($isOrderPage));
            }
        }
    }

    elseif (
        $_REQUEST['action'] === 'quantity' &&
        !empty((int)$_REQUEST['quantity']) &&
        (int)$_REQUEST['quantity'] > 0
    ) {
        $CSaleBasket = new CSaleBasket();

        $fields = array(
            'QUANTITY' => (int)$_REQUEST['quantity'],
        );
        if ($CSaleBasket->Update((int)$_REQUEST['id'], $fields)) {
            $response = array('status' => 'success', 'data' => getCartData());
        }
    }
}

elseif ($_REQUEST['action'] === 'couponAdd' && !empty($_REQUEST['coupon'])) {
    DiscountCouponsManager::init();
    DiscountCouponsManager::clear(true);
    DiscountCouponsManager::add($_REQUEST['coupon']);
    $response = array('status' => 'success', 'data' => getCartData());
}

elseif ($_REQUEST['action'] === 'couponDelete') {
    DiscountCouponsManager::init();
    DiscountCouponsManager::clear(true);
    $response = array('status' => 'success', 'data' => getCartData());
}

die(json_encode($response));