<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php';

global $APPLICATION;

$APPLICATION->SetTitle('Подписка на цветы');
?>
<div class="container-block subscribe-container">
    <div class="subscribe-page hero">
        <div class="subscribe-page__sticky">
            <div class="hero__slider js-mono-slider">
                <div class="swiper-container hero__slider-container hero__slider-container-1">
                    <?php $APPLICATION->IncludeComponent(
                        'bitrix:news.list',
                        'bouquets_slider2',
                        array(
                            'ACTIVE_DATE_FORMAT' => '',
                            'ADD_SECTIONS_CHAIN' => 'N',
                            'AJAX_MODE' => 'N',
                            'AJAX_OPTION_ADDITIONAL' => '',
                            'AJAX_OPTION_HISTORY' => 'N',
                            'AJAX_OPTION_JUMP' => 'N',
                            'AJAX_OPTION_STYLE' => 'N',
                            'CACHE_FILTER' => 'N',
                            'CACHE_GROUPS' => 'Y',
                            'CACHE_TIME' => 36000000,
                            'CACHE_TYPE' => 'A',
                            'CHECK_DATES' => 'Y',
                            'DETAIL_URL' => '',
                            'DISPLAY_BOTTOM_PAGER' => 'N',
                            'DISPLAY_DATE' => 'N',
                            'DISPLAY_NAME' => 'N',
                            'DISPLAY_PICTURE' => 'N',
                            'DISPLAY_PREVIEW_TEXT' => 'N',
                            'DISPLAY_TOP_PAGER' => 'N',
                            'FIELD_CODE' => array('PREVIEW_PICTURE', 'DETAIL_PICTURE'),
                            'FILTER_NAME' => '',
                            'HIDE_LINK_WHEN_NO_DETAIL' => 'N',
                            'IBLOCK_ID' => IBLOCK_ID__SUBSCRIBE_SLIDER_MONO,
                            'IBLOCK_TYPE' => 'flower_subscription',
                            'INCLUDE_IBLOCK_INTO_CHAIN' => 'N',
                            'INCLUDE_SUBSECTIONS' => 'Y',
                            'MESSAGE_404' => '',
                            'NEWS_COUNT' => 999,
                            'PAGER_BASE_LINK_ENABLE' => 'N',
                            'PAGER_DESC_NUMBERING' => 'N',
                            'PAGER_DESC_NUMBERING_CACHE_TIME' => '',
                            'PAGER_SHOW_ALL' => 'N',
                            'PAGER_SHOW_ALWAYS' => 'N',
                            'PAGER_TEMPLATE' => '',
                            'PAGER_TITLE' => '',
                            'PARENT_SECTION' => '',
                            'PARENT_SECTION_CODE' => '',
                            'PREVIEW_TRUNCATE_LEN' => '',
                            'PROPERTY_CODE' => array(),
                            'SET_BROWSER_TITLE' => 'N',
                            'SET_LAST_MODIFIED' => 'N',
                            'SET_META_DESCRIPTION' => 'N',
                            'SET_META_KEYWORDS' => 'N',
                            'SET_STATUS_404' => 'N',
                            'SET_TITLE' => 'N',
                            'SHOW_404' => 'N',
                            'SORT_BY1' => 'SORT',
                            'SORT_BY2' => 'ID',
                            'SORT_ORDER1' => 'ASC',
                            'SORT_ORDER2' => 'DESC',
                            'STRICT_SECTION_CHECK' => 'N',
                        )
                    ); ?>
                </div>
                <div class="swiper-container hero__slider-container hero__slider-container-2 visually-hidden">
                    <?php $APPLICATION->IncludeComponent(
                        'bitrix:news.list',
                        'bouquets_slider2',
                        array(
                            'ACTIVE_DATE_FORMAT' => '',
                            'ADD_SECTIONS_CHAIN' => 'N',
                            'AJAX_MODE' => 'N',
                            'AJAX_OPTION_ADDITIONAL' => '',
                            'AJAX_OPTION_HISTORY' => 'N',
                            'AJAX_OPTION_JUMP' => 'N',
                            'AJAX_OPTION_STYLE' => 'N',
                            'CACHE_FILTER' => 'N',
                            'CACHE_GROUPS' => 'Y',
                            'CACHE_TIME' => 36000000,
                            'CACHE_TYPE' => 'A',
                            'CHECK_DATES' => 'Y',
                            'DETAIL_URL' => '',
                            'DISPLAY_BOTTOM_PAGER' => 'N',
                            'DISPLAY_DATE' => 'N',
                            'DISPLAY_NAME' => 'N',
                            'DISPLAY_PICTURE' => 'N',
                            'DISPLAY_PREVIEW_TEXT' => 'N',
                            'DISPLAY_TOP_PAGER' => 'N',
                            'FIELD_CODE' => array('PREVIEW_PICTURE', 'DETAIL_PICTURE'),
                            'FILTER_NAME' => '',
                            'HIDE_LINK_WHEN_NO_DETAIL' => 'N',
                            'IBLOCK_ID' => IBLOCK_ID__SUBSCRIBE_SLIDER_COMPOSITE,
                            'IBLOCK_TYPE' => 'flower_subscription',
                            'INCLUDE_IBLOCK_INTO_CHAIN' => 'N',
                            'INCLUDE_SUBSECTIONS' => 'Y',
                            'MESSAGE_404' => '',
                            'NEWS_COUNT' => 999,
                            'PAGER_BASE_LINK_ENABLE' => 'N',
                            'PAGER_DESC_NUMBERING' => 'N',
                            'PAGER_DESC_NUMBERING_CACHE_TIME' => '',
                            'PAGER_SHOW_ALL' => 'N',
                            'PAGER_SHOW_ALWAYS' => 'N',
                            'PAGER_TEMPLATE' => '',
                            'PAGER_TITLE' => '',
                            'PARENT_SECTION' => '',
                            'PARENT_SECTION_CODE' => '',
                            'PREVIEW_TRUNCATE_LEN' => '',
                            'PROPERTY_CODE' => array(),
                            'SET_BROWSER_TITLE' => 'N',
                            'SET_LAST_MODIFIED' => 'N',
                            'SET_META_DESCRIPTION' => 'N',
                            'SET_META_KEYWORDS' => 'N',
                            'SET_STATUS_404' => 'N',
                            'SET_TITLE' => 'N',
                            'SHOW_404' => 'N',
                            'SORT_BY1' => 'SORT',
                            'SORT_BY2' => 'ID',
                            'SORT_ORDER1' => 'ASC',
                            'SORT_ORDER2' => 'DESC',
                            'STRICT_SECTION_CHECK' => 'N',
                        )
                    ); ?>
                </div>
            </div>
            <?php $APPLICATION->IncludeComponent(
                'bitrix:news.list',
                'flower_subscription_advantages',
                array(
                    'ACTIVE_DATE_FORMAT' => '',
                    'ADD_SECTIONS_CHAIN' => 'N',
                    'AJAX_MODE' => 'N',
                    'AJAX_OPTION_ADDITIONAL' => '',
                    'AJAX_OPTION_HISTORY' => 'N',
                    'AJAX_OPTION_JUMP' => 'N',
                    'AJAX_OPTION_STYLE' => 'N',
                    'CACHE_FILTER' => 'N',
                    'CACHE_GROUPS' => 'Y',
                    'CACHE_TIME' => 36000000,
                    'CACHE_TYPE' => 'A',
                    'CHECK_DATES' => 'Y',
                    'DETAIL_URL' => '',
                    'DISPLAY_BOTTOM_PAGER' => 'N',
                    'DISPLAY_DATE' => 'N',
                    'DISPLAY_NAME' => 'N',
                    'DISPLAY_PICTURE' => 'N',
                    'DISPLAY_PREVIEW_TEXT' => 'N',
                    'DISPLAY_TOP_PAGER' => 'N',
                    'FIELD_CODE' => array(),
                    'FILTER_NAME' => '',
                    'HIDE_LINK_WHEN_NO_DETAIL' => 'N',
                    'IBLOCK_ID' => IBLOCK_ID__SUBSCRIBE_ADVANTAGES,
                    'IBLOCK_TYPE' => 'flower_subscription',
                    'INCLUDE_IBLOCK_INTO_CHAIN' => 'N',
                    'INCLUDE_SUBSECTIONS' => 'Y',
                    'MESSAGE_404' => '',
                    'NEWS_COUNT' => 999,
                    'PAGER_BASE_LINK_ENABLE' => 'N',
                    'PAGER_DESC_NUMBERING' => 'N',
                    'PAGER_DESC_NUMBERING_CACHE_TIME' => '',
                    'PAGER_SHOW_ALL' => 'N',
                    'PAGER_SHOW_ALWAYS' => 'N',
                    'PAGER_TEMPLATE' => '',
                    'PAGER_TITLE' => '',
                    'PARENT_SECTION' => '',
                    'PARENT_SECTION_CODE' => '',
                    'PREVIEW_TRUNCATE_LEN' => '',
                    'PROPERTY_CODE' => array(),
                    'SET_BROWSER_TITLE' => 'N',
                    'SET_LAST_MODIFIED' => 'N',
                    'SET_META_DESCRIPTION' => 'N',
                    'SET_META_KEYWORDS' => 'N',
                    'SET_STATUS_404' => 'N',
                    'SET_TITLE' => 'N',
                    'SHOW_404' => 'N',
                    'SORT_BY1' => 'SORT',
                    'SORT_BY2' => 'ID',
                    'SORT_ORDER1' => 'ASC',
                    'SORT_ORDER2' => 'DESC',
                    'STRICT_SECTION_CHECK' => 'N',
                )
            ); ?>
        </div>
        <div class="subscribe-page__form-block">
            <div class="subscribe-page__title"><?php $APPLICATION->ShowTitle(); ?></div>
            <div class="subscribe-page__text">
                <?php $APPLICATION->IncludeComponent(
                    'bitrix:main.include',
                    '',
                    array(
                        'AREA_FILE_SHOW' => 'file',
                        'AREA_FILE_SUFFIX' => '',
                        'EDIT_TEMPLATE' => '',
                        'PATH' => SITE_TEMPLATE_PATH.'/include/flower_subscription/text3.php',
                    )
                ); ?>
            </div>
            <div class="subscribe-page__form">
                <?php $APPLICATION->IncludeComponent(
                    'bitrix:catalog.section',
                    'flower_subscription',
                    array(
                        'ACTION_VARIABLE' => '',
                        'ADD_PICT_PROP' => '',
                        'ADD_PROPERTIES_TO_BASKET' => 'N',
                        'ADD_SECTIONS_CHAIN' => 'N',
                        'ADD_TO_BASKET_ACTION' => '',
                        'AJAX_MODE' => 'N',
                        'AJAX_OPTION_ADDITIONAL' => '',
                        'AJAX_OPTION_HISTORY' => 'N',
                        'AJAX_OPTION_JUMP' => 'N',
                        'AJAX_OPTION_STYLE' => 'N',
                        'BACKGROUND_IMAGE' => '',
                        'BASKET_URL' => '',
                        'BROWSER_TITLE' => '',
                        'CACHE_FILTER' => 'N',
                        'CACHE_GROUPS' => 'Y',
                        'CACHE_TIME' => 36000000,
                        'CACHE_TYPE' => 'A',
                        'COMPATIBLE_MODE' => 'Y',
                        'CONVERT_CURRENCY' => 'N',
                        'CUSTOM_FILTER' => '',
                        'DETAIL_URL' => '',
                        'DISABLE_INIT_JS_IN_COMPONENT' => 'Y',
                        'DISPLAY_BOTTOM_PAGER' => 'N',
                        'DISPLAY_COMPARE' => 'N',
                        'DISPLAY_TOP_PAGER' => 'N',
                        'ELEMENT_SORT_FIELD' => 'sort',
                        'ELEMENT_SORT_FIELD2' => 'id',
                        'ELEMENT_SORT_ORDER' => 'asc',
                        'ELEMENT_SORT_ORDER2' => 'desc',
                        'ENLARGE_PRODUCT' => '',
                        'FILTER_NAME' => '',
                        'HIDE_NOT_AVAILABLE' => 'Y',
                        'HIDE_NOT_AVAILABLE_OFFERS' => 'Y',
                        'IBLOCK_ID' => IBLOCK_ID__SUBSCRIBE,
                        'IBLOCK_TYPE' => 'flower_subscription',
                        'INCLUDE_SUBSECTIONS' => 'Y',
                        'LABEL_PROP' => array(),
                        'LAZY_LOAD' => 'N',
                        'LINE_ELEMENT_COUNT' => 0,
                        'LOAD_ON_SCROLL' => 'N',
                        'MESSAGE_404' => '',
                        'MESS_BTN_ADD_TO_BASKET' => '',
                        'MESS_BTN_BUY' => '',
                        'MESS_BTN_DETAIL' => '',
                        'MESS_BTN_SUBSCRIBE' => '',
                        'MESS_NOT_AVAILABLE' => '',
                        'META_DESCRIPTION' => '',
                        'META_KEYWORDS' => '',
                        'OFFERS_CART_PROPERTIES' => array(),
                        'OFFERS_FIELD_CODE' => array('NAME'),
                        'OFFERS_LIMIT' => 0,
                        'OFFERS_PROPERTY_CODE' => array('SIZE', 'HIT', 'CAPTION'),
                        'OFFERS_SORT_FIELD' => 'sort',
                        'OFFERS_SORT_FIELD2' => 'id',
                        'OFFERS_SORT_ORDER' => 'asc',
                        'OFFERS_SORT_ORDER2' => 'desc',
                        'PAGER_BASE_LINK_ENABLE' => 'N',
                        'PAGER_DESC_NUMBERING' => 'N',
                        'PAGER_DESC_NUMBERING_CACHE_TIME' => '',
                        'PAGER_SHOW_ALL' => 'N',
                        'PAGER_SHOW_ALWAYS' => 'N',
                        'PAGER_TEMPLATE' => '',
                        'PAGER_TITLE' => '',
                        'PAGE_ELEMENT_COUNT' => 999,
                        'PARTIAL_PRODUCT_PROPERTIES' => 'N',
                        'PRICE_CODE' => array('BASE'),
                        'PRICE_VAT_INCLUDE' => 'Y',
                        'PRODUCT_BLOCKS_ORDER' => '',
                        'PRODUCT_DISPLAY_MODE' => 'N',
                        'PRODUCT_ID_VARIABLE' => '',
                        'PRODUCT_PROPERTIES' => array(),
                        'PRODUCT_PROPS_VARIABLE' => '',
                        'PRODUCT_QUANTITY_VARIABLE' => '',
                        'PRODUCT_ROW_VARIANTS' => '',
                        'PRODUCT_SUBSCRIPTION' => 'N',
                        'PROPERTY_CODE' => array(),
                        'PROPERTY_CODE_MOBILE' => array(),
                        'RCM_PROD_ID' => '',
                        'RCM_TYPE' => '',
                        'SECTION_CODE' => '',
                        'SECTION_CODE_PATH' => '',
                        'SECTION_ID' => '',
                        'SECTION_ID_VARIABLE' => '',
                        'SECTION_URL' => '',
                        'SECTION_USER_FIELDS' => array(),
                        'SEF_MODE' => 'Y',
                        'SEF_RULE' => '',
                        'SET_BROWSER_TITLE' => 'N',
                        'SET_LAST_MODIFIED' => 'N',
                        'SET_META_DESCRIPTION' => 'N',
                        'SET_META_KEYWORDS' => 'N',
                        'SET_STATUS_404' => 'N',
                        'SET_TITLE' => 'N',
                        'SHOW_404' => 'N',
                        'SHOW_ALL_WO_SECTION' => 'Y',
                        'SHOW_CLOSE_POPUP' => 'N',
                        'SHOW_DISCOUNT_PERCENT' => 'N',
                        'SHOW_FROM_SECTION' => 'N',
                        'SHOW_MAX_QUANTITY' => 'N',
                        'SHOW_OLD_PRICE' => 'N',
                        'SHOW_PRICE_COUNT' => 1,
                        'SHOW_SLIDER' => 'N',
                        'SLIDER_INTERVAL' => '',
                        'SLIDER_PROGRESS' => 'N',
                        'TEMPLATE_THEME' => '',
                        'USE_ENHANCED_ECOMMERCE' => 'N',
                        'USE_MAIN_ELEMENT_SECTION' => 'N',
                        'USE_PRICE_COUNT' => 'N',
                        'USE_PRODUCT_QUANTITY' => 'N',
                    )
                ); ?>
            </div>
        </div>
        <?php $APPLICATION->IncludeComponent(
            'bitrix:news.list',
            'flower_subscription_advantages_mobile',
            array(
                'ACTIVE_DATE_FORMAT' => '',
                'ADD_SECTIONS_CHAIN' => 'N',
                'AJAX_MODE' => 'N',
                'AJAX_OPTION_ADDITIONAL' => '',
                'AJAX_OPTION_HISTORY' => 'N',
                'AJAX_OPTION_JUMP' => 'N',
                'AJAX_OPTION_STYLE' => 'N',
                'CACHE_FILTER' => 'N',
                'CACHE_GROUPS' => 'Y',
                'CACHE_TIME' => 36000000,
                'CACHE_TYPE' => 'A',
                'CHECK_DATES' => 'Y',
                'DETAIL_URL' => '',
                'DISPLAY_BOTTOM_PAGER' => 'N',
                'DISPLAY_DATE' => 'N',
                'DISPLAY_NAME' => 'N',
                'DISPLAY_PICTURE' => 'N',
                'DISPLAY_PREVIEW_TEXT' => 'N',
                'DISPLAY_TOP_PAGER' => 'N',
                'FIELD_CODE' => array(),
                'FILTER_NAME' => '',
                'HIDE_LINK_WHEN_NO_DETAIL' => 'N',
                'IBLOCK_ID' => IBLOCK_ID__SUBSCRIBE_ADVANTAGES,
                'IBLOCK_TYPE' => 'flower_subscription',
                'INCLUDE_IBLOCK_INTO_CHAIN' => 'N',
                'INCLUDE_SUBSECTIONS' => 'Y',
                'MESSAGE_404' => '',
                'NEWS_COUNT' => 999,
                'PAGER_BASE_LINK_ENABLE' => 'N',
                'PAGER_DESC_NUMBERING' => 'N',
                'PAGER_DESC_NUMBERING_CACHE_TIME' => '',
                'PAGER_SHOW_ALL' => 'N',
                'PAGER_SHOW_ALWAYS' => 'N',
                'PAGER_TEMPLATE' => '',
                'PAGER_TITLE' => '',
                'PARENT_SECTION' => '',
                'PARENT_SECTION_CODE' => '',
                'PREVIEW_TRUNCATE_LEN' => '',
                'PROPERTY_CODE' => array(),
                'SET_BROWSER_TITLE' => 'N',
                'SET_LAST_MODIFIED' => 'N',
                'SET_META_DESCRIPTION' => 'N',
                'SET_META_KEYWORDS' => 'N',
                'SET_STATUS_404' => 'N',
                'SET_TITLE' => 'N',
                'SHOW_404' => 'N',
                'SORT_BY1' => 'SORT',
                'SORT_BY2' => 'ID',
                'SORT_ORDER1' => 'ASC',
                'SORT_ORDER2' => 'DESC',
                'STRICT_SECTION_CHECK' => 'N',
            )
        ); ?>
    </div>
    <?php $APPLICATION->IncludeComponent(
        'bitrix:news.list',
        'flower_subscription_faq',
        array(
            'ACTIVE_DATE_FORMAT' => '',
            'ADD_SECTIONS_CHAIN' => 'N',
            'AJAX_MODE' => 'N',
            'AJAX_OPTION_ADDITIONAL' => '',
            'AJAX_OPTION_HISTORY' => 'N',
            'AJAX_OPTION_JUMP' => 'N',
            'AJAX_OPTION_STYLE' => 'N',
            'CACHE_FILTER' => 'N',
            'CACHE_GROUPS' => 'Y',
            'CACHE_TIME' => 36000000,
            'CACHE_TYPE' => 'A',
            'CHECK_DATES' => 'Y',
            'DETAIL_URL' => '',
            'DISPLAY_BOTTOM_PAGER' => 'N',
            'DISPLAY_DATE' => 'N',
            'DISPLAY_NAME' => 'N',
            'DISPLAY_PICTURE' => 'N',
            'DISPLAY_PREVIEW_TEXT' => 'N',
            'DISPLAY_TOP_PAGER' => 'N',
            'FIELD_CODE' => array(),
            'FILTER_NAME' => '',
            'HIDE_LINK_WHEN_NO_DETAIL' => 'N',
            'IBLOCK_ID' => IBLOCK_ID__SUBSCRIBE_FAQ,
            'IBLOCK_TYPE' => 'flower_subscription',
            'INCLUDE_IBLOCK_INTO_CHAIN' => 'N',
            'INCLUDE_SUBSECTIONS' => 'Y',
            'MESSAGE_404' => '',
            'NEWS_COUNT' => 999,
            'PAGER_BASE_LINK_ENABLE' => 'N',
            'PAGER_DESC_NUMBERING' => 'N',
            'PAGER_DESC_NUMBERING_CACHE_TIME' => '',
            'PAGER_SHOW_ALL' => 'N',
            'PAGER_SHOW_ALWAYS' => 'N',
            'PAGER_TEMPLATE' => '',
            'PAGER_TITLE' => '',
            'PARENT_SECTION' => '',
            'PARENT_SECTION_CODE' => '',
            'PREVIEW_TRUNCATE_LEN' => '',
            'PROPERTY_CODE' => array(),
            'SET_BROWSER_TITLE' => 'N',
            'SET_LAST_MODIFIED' => 'N',
            'SET_META_DESCRIPTION' => 'N',
            'SET_META_KEYWORDS' => 'N',
            'SET_STATUS_404' => 'N',
            'SET_TITLE' => 'N',
            'SHOW_404' => 'N',
            'SORT_BY1' => 'SORT',
            'SORT_BY2' => 'ID',
            'SORT_ORDER1' => 'ASC',
            'SORT_ORDER2' => 'DESC',
            'STRICT_SECTION_CHECK' => 'N',
        )
    ); ?>
</div>
<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php';
?>