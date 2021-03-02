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

$arResult['ITEMS'] = Content::getCartItemsData($arResult['ITEMS']['AnDelCanBuy']);

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