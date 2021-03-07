<?php

/**
 * @var array $arParams
 * @var array $arResult
 */

use Germen\Content;
use PDV\Tools;

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

$arResult['BASKET_ITEMS'] = Content::getCartItemsData(
    $arResult['BASKET_ITEMS'],
    array('width' => 134, 'height' => 124)
);

$products = Content::getUpsaleBookmateProducts();

$arResult['UPSALE_PRODUCTS'] = $products['upsaleProducts'];
$arResult['BOOKMATE_PRODUCTS'] = $products['bookmateProducts'];

$arResult['coupon'] = array();
if (
    !empty($arResult['JS_DATA']['COUPON_LIST'][0]) &&
    (
        (int)$arResult['JS_DATA']['COUPON_LIST'][0]['STATUS'] === 2 ||
        (int)$arResult['JS_DATA']['COUPON_LIST'][0]['STATUS'] === 4
    )
) {
    $arResult['coupon'] = array(
        'coupon' => $arResult['JS_DATA']['COUPON_LIST'][0]['COUPON'],
        'status' => (int)$arResult['JS_DATA']['COUPON_LIST'][0]['STATUS'],
        'statusText' => mb_ucfirst($arResult['JS_DATA']['COUPON_LIST'][0]['STATUS_TEXT']),
    );
}

$arResult['informationBanner'] = Content::getInformationBannerCached();

$deliveriesTimes = Tools::getDeliveryTime();
$arResult['deliveryTime'] = $deliveriesTimes[DELIVERY_ID__DEFAULT]['TIME_NUMBER'];

$arResult['activeEndTime'] = 0;
$arResult['countDateDelivery'] = 1;

$productsId = array();
$elements = array();
foreach ($arResult['BASKET_ITEMS'] as $item) {
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
