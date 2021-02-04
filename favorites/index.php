<?php

/**
 * @global CMain $APPLICATION
 */

require($_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php');

$APPLICATION->SetTitle('Избранное');

$wishlist = array();
if (!empty($_COOKIE['wishlist'])) {
    $wishlist = array_map('intval', array_filter(explode('|', $_COOKIE['wishlist'])));
}

global $favoritesFilter;

$favoritesFilter = array();
if (!empty($wishlist)) {
    $favoritesFilter = array(
        'ID' => $wishlist,
    );
}
?>
<div class="favorite--empty js-favorite-empty-block" style="<?=empty($wishlist) ? '' : 'display: none;'?>">
    <div class="favorite__block">
        <div class="favorite__title head-h2">Избранное</div>
        <div class="favorite__text">
            Здесь пока пусто. Чтобы добавить букет в избранное, поставьте сердечко букетам из каталога.
        </div>
        <div class="favorite__links">
            <a class="favorite__link" href="/">Перейти в каталог</a>
            <a class="favorite__link" href="/">Написать в чат</a>
        </div>
        <div class="favorite__text favorite__text--grey">
            Не можете выбрать? Напишите нам в удобный мессенджер&nbsp;—&nbsp;поможем.
        </div>
    </div>
</div>
<?php if (!empty($wishlist)): ?>
    <?php $APPLICATION->IncludeComponent(
        'bitrix:catalog.section',
        'favorites',
        array(
            'ACTION_VARIABLE' => '',
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
            'COMPATIBLE_MODE' => 'N',
            'CONVERT_CURRENCY' => 'N',
            'DETAIL_URL' => '',
            'DISABLE_INIT_JS_IN_COMPONENT' => 'N',
            'DISPLAY_BOTTOM_PAGER' => 'N',
            'DISPLAY_COMPARE' => 'N',
            'DISPLAY_TOP_PAGER' => 'N',
            'ELEMENT_SORT_FIELD' => 'sort',
            'ELEMENT_SORT_FIELD2' => 'id',
            'ELEMENT_SORT_ORDER' => 'asc',
            'ELEMENT_SORT_ORDER2' => 'desc',
            'ENLARGE_PRODUCT' => '',
            'FILE_404' => '',
            'FILTER_NAME' => 'favoritesFilter',
            'HIDE_NOT_AVAILABLE' => 'Y',
            'HIDE_NOT_AVAILABLE_OFFERS' => 'Y',
            'IBLOCK_ID' => IBLOCK_ID__CATALOG,
            'IBLOCK_TYPE' => 'germen',
            'INCLUDE_SUBSECTIONS' => 'Y',
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
            'OFFERS_LIMIT' => 0,
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
            'PRODUCT_ID_VARIABLE' => '',
            'PRODUCT_PROPERTIES' => array(),
            'PRODUCT_PROPS_VARIABLE' => '',
            'PRODUCT_QUANTITY_VARIABLE' => '',
            'PRODUCT_ROW_VARIANTS' => '',
            'PRODUCT_SUBSCRIPTION' => 'N',
            'PROPERTY_CODE' => array(),
            'RCM_PROD_ID' => '',
            'RCM_TYPE' => '',
            'SECTION_CODE' => '',
            'SECTION_ID' => '',
            'SECTION_ID_VARIABLE' => '',
            'SECTION_URL' => '',
            'SECTION_USER_FIELDS' => array(),
            'SEF_MODE' => 'N',
            'SET_BROWSER_TITLE' => 'N',
            'SET_LAST_MODIFIED' => 'N',
            'SET_META_DESCRIPTION' => 'N',
            'SET_META_KEYWORDS' => 'N',
            'SET_STATUS_404' => 'Y',
            'SET_TITLE' => 'N',
            'SHOW_404' => 'Y',
            'SHOW_ALL_WO_SECTION' => 'Y',
            'SHOW_CLOSE_POPUP' => 'N',
            'SHOW_DISCOUNT_PERCENT' => 'N',
            'SHOW_FROM_SECTION' => 'N',
            'SHOW_MAX_QUANTITY' => 'N',
            'SHOW_OLD_PRICE' => 'N',
            'SHOW_PRICE_COUNT' => 1,
            'SHOW_SLIDER' => 'N',
            'TEMPLATE_THEME' => '',
            'USE_ENHANCED_ECOMMERCE' => 'N',
            'USE_MAIN_ELEMENT_SECTION' => 'N',
            'USE_PRICE_COUNT' => 'N',
            'USE_PRODUCT_QUANTITY' => 'N',
        )
    ); ?>
<?php endif; ?>
<?php
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php');
?>