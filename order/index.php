<?php

/**
 * @global CMain $APPLICATION
 * @global CMain $USER
 */

require_once $_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php';

$APPLICATION->SetTitle('Оформление заказа');
?>
<?php $APPLICATION->IncludeComponent(
    'bitrix:sale.order.ajax',
    '',
    array(
        'ACTION_VARIABLE' => '',
        'ADDITIONAL_PICT_PROP_2' => '',
        'ADDITIONAL_PICT_PROP_3' => '',
        'ALLOW_APPEND_ORDER' => 'Y',
        'ALLOW_AUTO_REGISTER' => 'Y',
        'ALLOW_NEW_PROFILE' => 'N',
        'ALLOW_USER_PROFILES' => 'N',
        'BASKET_IMAGES_SCALING' => '',
        'BASKET_POSITION' => '',
        'COMPATIBLE_MODE' => 'N',
        'DELIVERIES_PER_PAGE' => 0,
        'DELIVERY_FADE_EXTRA_SERVICES' => 'N',
        'DELIVERY_NO_AJAX' => 'N',
        'DELIVERY_NO_SESSION' => 'N',
        'DELIVERY_TO_PAYSYSTEM' => 'd2p',
        'DISABLE_BASKET_REDIRECT' => 'N',
        'EMPTY_BASKET_HINT_PATH' => '',
        'HIDE_ORDER_DESCRIPTION' => 'N',
        'ONLY_FULL_PAY_FROM_ACCOUNT' => 'N',
        'PATH_TO_AUTH' => '/',
        'PATH_TO_BASKET' => '/cart/',
        'PATH_TO_PAYMENT' => '',
        'PATH_TO_PERSONAL' => '/',
        'PAY_FROM_ACCOUNT' => 'N',
        'PAY_SYSTEMS_PER_PAGE' => 0,
        'PICKUPS_PER_PAGE' => 0,
        'PICKUP_MAP_TYPE' => '',
        'PRODUCT_COLUMNS_HIDDEN' => array(),
        'PRODUCT_COLUMNS_VISIBLE' => array(),
        'SEND_NEW_USER_NOTIFY' => 'N',
        'SERVICES_IMAGES_SCALING' => '',
        'SET_TITLE' => 'N',
        'SHOW_BASKET_HEADERS' => 'N',
        'SHOW_COUPONS' => 'N',
        'SHOW_COUPONS_BASKET' => 'N',
        'SHOW_COUPONS_DELIVERY' => 'N',
        'SHOW_COUPONS_PAY_SYSTEM' => 'N',
        'SHOW_DELIVERY_INFO_NAME' => 'N',
        'SHOW_DELIVERY_LIST_NAMES' => 'N',
        'SHOW_DELIVERY_PARENT_NAMES' => 'N',
        'SHOW_MAP_IN_PROPS' => 'N',
        'SHOW_NEAREST_PICKUP' => 'N',
        'SHOW_NOT_CALCULATED_DELIVERIES' => 'L',
        'SHOW_ORDER_BUTTON' => '',
        'SHOW_PAY_SYSTEM_INFO_NAME' => 'N',
        'SHOW_PAY_SYSTEM_LIST_NAMES' => 'N',
        'SHOW_PICKUP_MAP' => 'N',
        'SHOW_STORES_IMAGES' => 'N',
        'SHOW_TOTAL_ORDER_BUTTON' => 'N',
        'SHOW_VAT_PRICE' => 'N',
        'SKIP_USELESS_BLOCK' => 'N',
        'SPOT_LOCATION_BY_GEOIP' => 'N',
        'TEMPLATE_LOCATION' => '',
        'TEMPLATE_THEME' => '',
        'USER_CONSENT' => 'N',
        'USER_CONSENT_ID' => 0,
        'USER_CONSENT_IS_CHECKED' => 'N',
        'USER_CONSENT_IS_LOADED' => 'N',
        'USE_CUSTOM_ADDITIONAL_MESSAGES' => 'N',
        'USE_CUSTOM_ERROR_MESSAGES' => 'N',
        'USE_CUSTOM_MAIN_MESSAGES' => 'N',
        'USE_ENHANCED_ECOMMERCE' => 'N',
        'USE_PHONE_NORMALIZATION' => 'N',
        'USE_PRELOAD' => 'N',
        'USE_PREPAYMENT' => 'N',
        'USE_YM_GOALS' => 'N',
    )
); ?>
<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php';
?>