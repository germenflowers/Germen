<?php

/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 */

use Germen\Admin\Content as AdminContent;
use \Germen\Content;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$this->setFrameMode(true);

$items = array();
foreach ($arResult['orders'] as $item) {
    $title = AdminContent::getHistoryListItemTitle($item['basket']);

    $basket = array();
    foreach ($item['basket'] as $basketItem) {
        $propertiesString = AdminContent::getHistoryListBasketItemPropertiesString($basketItem['properties']);

        $basket[] = array(
            'name' => $basketItem['name'],
            'price' => number_format((int)$basketItem['price'], 0, '', ' '),
            'quantity' => $basketItem['quantity'],
            'propertiesString' => $propertiesString,
        );
    }

    $items[] = array(
        'id' => $item['id'],
        'title' => $title,
        'date' => $item['dateCreate']->format('d.m.Y'),
        'time' => $item['dateCreate']->format('H:i'),
        'price' => number_format((int)$item['price'], 0, '', ' '),
        'comment' => $item['comment'],
        'buildTime' => empty($item['dateBuild']) ? '' : $item['dateBuild']->format('H:i'),
        'basket' => $basket,
    );
}

Content::setStorage(
    'historyItems',
    array(
        'items' => $items,
        'pagenavigation' => $arResult['pageNavigation'],
    )
);
