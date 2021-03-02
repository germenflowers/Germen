<?php

/**
 * @global CMain $APPLICATION
 */

require($_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php');

$APPLICATION->SetTitle('Корзина');
?>
<?php $APPLICATION->IncludeComponent(
    'bitrix:sale.basket.basket',
    '',
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
    )
); ?>
<?php
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php');
?>