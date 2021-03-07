<?php

/**
 * @var array $arParams
 * @var array $arResult
 */

use Germen\Content;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

if (!function_exists('mb_ucfirst') && extension_loaded('mbstring')) {
    /**
     * mb_ucfirst - преобразует первый символ в верхний регистр
     * @param string $str - строка
     * @param string $encoding - кодировка, по-умолчанию UTF-8
     * @return string
     */
    function mb_ucfirst(string $str, $encoding = 'UTF-8'): string
    {
        $str = mb_ereg_replace('^[\ ]+', '', $str);
        $str = mb_strtoupper(mb_substr($str, 0, 1, $encoding), $encoding).
            mb_substr($str, 1, mb_strlen($str), $encoding);

        return $str;
    }
}

$imageParams = array('width' => 64, 'height' => 64);
if ($arParams['ORDER_PAGE']) {
    $imageParams = array('width' => 134, 'height' => 124);
}

$arResult['ITEMS'] = Content::getCartItemsData($arResult['ITEMS']['AnDelCanBuy'], $imageParams);

$products = Content::getUpsaleBookmateProducts();

$arResult['UPSALE_PRODUCTS'] = $products['upsaleProducts'];
$arResult['BOOKMATE_PRODUCTS'] = $products['bookmateProducts'];

foreach ($arResult['UPSALE_PRODUCTS'] as $i => $item) {
    $arResult['UPSALE_PRODUCTS'][$i]['inCart'] = false;

    foreach ($arResult['ITEMS'] as $cartItem) {
        if ($cartItem['productId'] === $item['id']) {
            $arResult['UPSALE_PRODUCTS'][$i]['inCart'] = true;
            break;
        }
    }
}

$arResult['coupon'] = array();
if (!empty($arResult['COUPON'])) {
    foreach ($arResult['COUPON_LIST'] as $item) {
        if ($item['COUPON'] === $arResult['COUPON']) {
            $arResult['coupon'] = array(
                'coupon' => $item['COUPON'],
                'status' => (int)$item['STATUS'],
                'statusText' => mb_ucfirst($item['STATUS_TEXT']),
            );
            break;
        }
    }
}

$arResult['activeEndTime'] = 0;
$arResult['countDateDelivery'] = 1;

if ($arParams['ORDER_PAGE']) {
    $productsId = array();
    $elements = array();
    foreach ($arResult['ITEMS'] as $item) {
        if (!in_array((int)$item['productId'], $productsId, true)) {
            $productsId[] = (int)$item['productId'];
            $elements[(int)$item['productId']] = array(
                'ID' => (int)$item['productId'],
                'PROPERTIES' => array(),
            );
        }
    }

    if (!empty($productsId)) {
        $propertyFilter = array(
            'CODE' => array('ACTIVE_WEEK_DAYS', 'ACTIVE_START_SHIFT', 'ACTIVE_END_SHIFT', 'ACTIVE_END_DATE'),
        );
        $options = array('GET_RAW_DATA' => 'Y');
        CIBlockElement::GetPropertyValuesArray($elements, IBLOCK_ID__CATALOG, array(), $propertyFilter, $options);

        foreach ($elements as $item) {
            if (empty($item['PROPERTIES']['ACTIVE_END_DATE']['VALUE'])) {
                continue;
            }

            $time = strtotime($item['PROPERTIES']['ACTIVE_END_DATE']['VALUE']);

            if ($arResult['activeEndTime'] === 0) {
                $arResult['activeEndTime'] = $time;
            }

            if ($time < $arResult['activeEndTime']) {
                $arResult['activeEndTime'] = $time;
            }
        }

        $filter = array('ID' => $productsId);
        $select = array('IBLOCK_ID', 'ID', 'PROPERTY_COUNT_DELIVERIES');
        $result = CIBlockElement::GetList(array(), $filter, false, false, $select);
        while ($row = $result->Fetch()) {
            if (
                (int)$row['IBLOCK_ID'] === IBLOCK_ID__SUBSCRIBE_OFFERS &&
                (int)$row['PROPERTY_COUNT_DELIVERIES_VALUE'] > $arResult['countDateDelivery']
            ) {
                $arResult['countDateDelivery'] = (int)$row['PROPERTY_COUNT_DELIVERIES_VALUE'];
            }
        }
    }
}
