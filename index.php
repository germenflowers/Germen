<?php

/**
 * @global CMain $APPLICATION
 * @global CMain $USER
 */

use \Bitrix\Main\Loader;
use \PDV\Tools;
use \Germen\WorkTimeFilter;

require($_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php');

$APPLICATION->SetTitle('Удобный сервис доставки цветов. Подписка на цветы');

Loader::includeModule('iblock');

$deliveryTime = Tools::getTimeByDelivery();

$workTimeFilterObject = new WorkTimeFilter();
$workTimeFilter = $workTimeFilterObject->getFilter($_GET['strict'] === 'on');

$arrFilterPopular = array('!PROPERTY_POPULAR' => false, 'PROPERTY_BANNER' => false);
//$bannerPopularFilter = array('!PROPERTY_POPULAR' => false, '!PROPERTY_BANNER' => false);
$catalogFilter = array('PROPERTY_BANNER' => false);
//$bannerFilter = array('!PROPERTY_BANNER' => false);

if (!empty($workTimeFilter)) {
    $arrFilterPopular = array_merge($arrFilterPopular, $workTimeFilter);
    $catalogFilter = array_merge($catalogFilter, $workTimeFilter);
}

$popularPageSize = 4;

$order = array('sort' => 'asc');
$filter = array('IBLOCK_ID' => IBLOCK_ID__CATALOG, 'ACTIVE' => 'Y');
$select = array('IBLOCK_ID', 'ID', 'NAME', 'UF_PAGE_SIZE');
$sectionResult = CIBlockSection::GetList($order, $filter, false, $select);

$wishlist = array();
if (!empty($_COOKIE['wishlist'])) {
    $wishlist = array_map('intval', array_filter(explode('|', $_COOKIE['wishlist'])));
}
?>
<div class="content__container">
    <?php if ($USER->IsAdmin()): ?>
        <form name="timeForm" action="/" method="get">
            <label>
                Выберите удобное время
                <input
                        type="datetime-local"
                        name="time"
                        value="<?=date('Y-m-d\TH:i')?>"
                        min="<?=date('Y-m-d')?>T00:00"
                        max="<?=date('Y-m-d', strtotime('last day of december this year'))?>T00:00"
                >
            </label>
            <label>
                Строгое сравнение
                <input type="checkbox" name="strict" <?=$_GET['strict'] === 'on' ? 'checked' : ''?>>
            </label>
            <button type="submit">Выбрать</button>
        </form>
    <?php endif; ?>

    <div class="promo-catalog__wrapper">
        <?php $APPLICATION->IncludeComponent(
            'bitrix:catalog.section',
            'catalog',
            Array(
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
                'COMPATIBLE_MODE' => 'N',
                'CONVERT_CURRENCY' => 'N',
                'CUSTOM_FILTER' => '',
                'DETAIL_URL' => '',
                'DISABLE_INIT_JS_IN_COMPONENT' => 'N',
                'DISPLAY_BOTTOM_PAGER' => 'Y',
                'DISPLAY_COMPARE' => 'N',
                'DISPLAY_TOP_PAGER' => 'N',
                'ELEMENT_SORT_FIELD' => 'sort',
                'ELEMENT_SORT_FIELD2' => 'id',
                'ELEMENT_SORT_ORDER' => 'asc',
                'ELEMENT_SORT_ORDER2' => 'desc',
                'ENLARGE_PRODUCT' => '',
                'FILTER_NAME' => 'arrFilterPopular',
                'HIDE_NOT_AVAILABLE' => 'N',
                'HIDE_NOT_AVAILABLE_OFFERS' => 'N',
                'IBLOCK_ID' => IBLOCK_ID__CATALOG,
                'IBLOCK_TYPE' => 'germen',
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
                'OFFERS_LIMIT' => 0,
                'PAGER_BASE_LINK_ENABLE' => 'N',
                'PAGER_DESC_NUMBERING' => 'N',
                'PAGER_DESC_NUMBERING_CACHE_TIME' => 36000,
                'PAGER_SHOW_ALL' => 'N',
                'PAGER_SHOW_ALWAYS' => 'Y',
                'PAGER_TEMPLATE' => 'catalog',
                'PAGER_TITLE' => '',
                'PAGE_ELEMENT_COUNT' => $popularPageSize,
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
                'PROPERTY_CODE_MOBILE' => array(),
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
                'SLIDER_INTERVAL' => 3000,
                'SLIDER_PROGRESS' => 'N',
                'TEMPLATE_THEME' => '',
                'USE_ENHANCED_ECOMMERCE' => 'N',
                'USE_MAIN_ELEMENT_SECTION' => 'N',
                'USE_PRICE_COUNT' => 'N',
                'USE_PRODUCT_QUANTITY' => 'N',
                'DELIVERY_TIME' => $deliveryTime,
                'BLOCK_TITLE' => 'Самое популярное',
                'POPULAR' => 'Y',
                'WISHLIST' => $wishlist,
            )
        ); ?>
    </div>

    <div class="promo-features__wrapper" data-anchor="advantages">
        <div class="promo-features js-promo-features-slider">
            <ul class="promo-features__list">
                <li class="promo-features__item">
                    <div class="promo-features__block">
                        <div class="promo-features__icon promo-features__icon--car">
                            <img src="<?=SITE_TEMPLATE_PATH?>/img/features-car.svg" alt=""/>
                        </div>
                        <h5 class="promo-features__title">Быстрая доставка</h5>
                        <p class="promo-features__text">
                            Как только вы&nbsp;сделали заказ, флорист идет выбирать свежие цветы, а&nbsp;курьер сразу
                            выезжает на&nbsp;базу. Доставка от&nbsp;60&nbsp;минут.
                        </p>
                    </div>
                </li>
                <li class="promo-features__item">
                    <div class="promo-features__block">
                        <div class="promo-features__icon promo-features__icon--flower">
                            <img src="<?=SITE_TEMPLATE_PATH?>/img/features-flower.svg" alt=""/>
                        </div>
                        <h5 class="promo-features__title">Только свежие цветы</h5>
                        <p class="promo-features__text">
                            Собираем букет под конкретный заказ&nbsp;&mdash; цветы не&nbsp;вянут в&nbsp;ожидании
                            продажи. Каждое растение храним в&nbsp;подходящей для него температуре, регулярно меняем
                            воду, сразу списываем вялые цветы и&nbsp;брак.
                        </p>
                    </div>
                </li>
                <li class="promo-features__item">
                    <div class="promo-features__block">
                        <div class="promo-features__icon promo-features__icon--reward">
                            <img src="<?=SITE_TEMPLATE_PATH?>/img/features-reward.svg" alt=""/>
                        </div>
                        <h5 class="promo-features__title">Гарантия качества</h5>
                        <p class="promo-features__text">
                            Возвращаем деньги, если цветы окажутся несвежими или завянут в&nbsp;день доставки.
                        </p>
                    </div>
                </li>
            </ul>
        </div>
    </div>

    <div class="promo-catalog__wrapper">
        <?php while ($row = $sectionResult->Fetch()) : ?>
            <?php
            $pageSize = 4;
            if ((int)$row['UF_PAGE_SIZE'] > 0) {
                $pageSize = (int)$row['UF_PAGE_SIZE'];
            }
            ?>
            <?php $APPLICATION->IncludeComponent(
                'bitrix:catalog.section',
                'catalog',
                Array(
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
                    'COMPATIBLE_MODE' => 'N',
                    'CONVERT_CURRENCY' => 'N',
                    'CUSTOM_FILTER' => '',
                    'DETAIL_URL' => '',
                    'DISABLE_INIT_JS_IN_COMPONENT' => 'N',
                    'DISPLAY_BOTTOM_PAGER' => 'Y',
                    'DISPLAY_COMPARE' => 'N',
                    'DISPLAY_TOP_PAGER' => 'N',
                    'ELEMENT_SORT_FIELD' => 'sort',
                    'ELEMENT_SORT_FIELD2' => 'id',
                    'ELEMENT_SORT_ORDER' => 'asc',
                    'ELEMENT_SORT_ORDER2' => 'desc',
                    'ENLARGE_PRODUCT' => '',
                    'FILTER_NAME' => 'catalogFilter',
                    'HIDE_NOT_AVAILABLE' => 'N',
                    'HIDE_NOT_AVAILABLE_OFFERS' => 'N',
                    'IBLOCK_ID' => IBLOCK_ID__CATALOG,
                    'IBLOCK_TYPE' => 'germen',
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
                    'OFFERS_LIMIT' => 0,
                    'PAGER_BASE_LINK_ENABLE' => 'N',
                    'PAGER_DESC_NUMBERING' => 'N',
                    'PAGER_DESC_NUMBERING_CACHE_TIME' => 36000,
                    'PAGER_SHOW_ALL' => 'N',
                    'PAGER_SHOW_ALWAYS' => 'Y',
                    'PAGER_TEMPLATE' => 'catalog',
                    'PAGER_TITLE' => '',
                    'PAGE_ELEMENT_COUNT' => $pageSize,
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
                    'PROPERTY_CODE_MOBILE' => array(),
                    'RCM_PROD_ID' => '',
                    'RCM_TYPE' => '',
                    'SECTION_CODE' => '',
                    'SECTION_ID' => $row['ID'],
                    'SECTION_ID_VARIABLE' => '',
                    'SECTION_URL' => '',
                    'SECTION_USER_FIELDS' => array(),
                    'SEF_MODE' => 'N',
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
                    'SLIDER_INTERVAL' => 0,
                    'SLIDER_PROGRESS' => 'N',
                    'TEMPLATE_THEME' => '',
                    'USE_ENHANCED_ECOMMERCE' => 'N',
                    'USE_MAIN_ELEMENT_SECTION' => 'N',
                    'USE_PRICE_COUNT' => 'N',
                    'USE_PRODUCT_QUANTITY' => 'N',
                    'DELIVERY_TIME' => $deliveryTime,
                    'BLOCK_TITLE' => $row['NAME'],
                    'SHOW_SECTION_DESC' => 'Y',
                    'WISHLIST' => $wishlist,
                )
            ); ?>
        <?php endwhile; ?>
    </div>

    <div class="promo-chat">
        <div class="promo-chat__preview">
            <img src="<?=SITE_TEMPLATE_PATH?>/img/chat.gif" alt=""/>
        </div>
        <div class="promo-chat__content">
            <h2 class="head-h2 promo-chat__title">Удобный чат с флористом</h2>
            <div class="promo-chat__text">
                Порекомендуем лучшие сезонные цветы, пришлем фото всех растений, которые есть на&nbsp;базе,
                сфотографируем готовый букет и&nbsp;подписанную открытку перед отправкой, добавим или заменим упаковку и&nbsp;цветы
                по&nbsp;вашей просьбе. И&nbsp;всё это&nbsp;&mdash; в&nbsp;удобном чате!
            </div>
        </div>
    </div>

    <?php $APPLICATION->IncludeComponent(
        'bitrix:news.list',
        'reviews-home',
        array(
            'ACTIVE_DATE_FORMAT' => 'd.m.Y',
            'ADD_SECTIONS_CHAIN' => 'N',
            'AJAX_MODE' => 'N',
            'AJAX_OPTION_ADDITIONAL' => '',
            'AJAX_OPTION_HISTORY' => 'N',
            'AJAX_OPTION_JUMP' => 'N',
            'AJAX_OPTION_STYLE' => 'Y',
            'CACHE_FILTER' => 'N',
            'CACHE_GROUPS' => 'Y',
            'CACHE_TIME' => 36000000,
            'CACHE_TYPE' => 'A',
            'CHECK_DATES' => 'Y',
            'DETAIL_URL' => '',
            'DISPLAY_BOTTOM_PAGER' => 'N',
            'DISPLAY_DATE' => 'N',
            'DISPLAY_NAME' => 'Y',
            'DISPLAY_PICTURE' => 'N',
            'DISPLAY_PREVIEW_TEXT' => 'N',
            'DISPLAY_TOP_PAGER' => 'N',
            'FIELD_CODE' => array(),
            'FILTER_NAME' => '',
            'HIDE_LINK_WHEN_NO_DETAIL' => 'N',
            'IBLOCK_ID' => IBLOCK_ID__REVIEWS,
            'IBLOCK_TYPE' => 'germen',
            'INCLUDE_IBLOCK_INTO_CHAIN' => 'N',
            'INCLUDE_SUBSECTIONS' => 'N',
            'MESSAGE_404' => '',
            'NEWS_COUNT' => 100,
            'PAGER_BASE_LINK_ENABLE' => 'N',
            'PAGER_DESC_NUMBERING' => 'N',
            'PAGER_DESC_NUMBERING_CACHE_TIME' => 36000,
            'PAGER_SHOW_ALL' => 'N',
            'PAGER_SHOW_ALWAYS' => 'N',
            'PAGER_TEMPLATE' => '.default',
            'PAGER_TITLE' => '',
            'PARENT_SECTION' => '',
            'PARENT_SECTION_CODE' => '',
            'PREVIEW_TRUNCATE_LEN' => '',
            'PROPERTY_CODE' => array('RATING'),
            'SET_BROWSER_TITLE' => 'N',
            'SET_LAST_MODIFIED' => 'N',
            'SET_META_DESCRIPTION' => 'N',
            'SET_META_KEYWORDS' => 'N',
            'SET_STATUS_404' => 'N',
            'SET_TITLE' => 'N',
            'SHOW_404' => 'N',
            'SORT_BY1' => 'ACTIVE_FROM',
            'SORT_BY2' => 'SORT',
            'SORT_ORDER1' => 'DESC',
            'SORT_ORDER2' => 'ASC',
            'STRICT_SECTION_CHECK' => 'N',
        )
    ); ?>
</div>

<?php /* $APPLICATION->IncludeComponent(
    'bitrix:main.include',
    '',
    array(
        'AREA_FILE_SHOW' => 'file',
        'AREA_FILE_SUFFIX' => '',
        'EDIT_TEMPLATE' => '',
        'PATH' => SITE_TEMPLATE_PATH.'/include/index/instagram.php',
    )
); */?>

<div class="promo-sub promo-sub--main u-pt-0">
    <?php $APPLICATION->IncludeComponent(
        'bitrix:main.include',
        '',
        array(
            'AREA_FILE_SHOW' => 'file',
            'AREA_FILE_SUFFIX' => '',
            'EDIT_TEMPLATE' => '',
            'PATH' => SITE_TEMPLATE_PATH.'/include/index/subscribe.php',
        )
    ); ?>
</div>

<?php
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php');
?>
<div class="product-info__fixed-footer">
<div class="product-info__row product-info__row--baseline product-info__row">
<div class="product-info__cell product-info__time"><svg class="promo-item__delivery__icon">
                                            <use xlink:href="<?=SITE_TEMPLATE_PATH?>/icons/icons.svg#properties-car-mini"></use>
                                        </svg><span>60 мин.</span></div>
<div class="product-info__cell">
    <div class="payment-systems">
    <div class="payment-systems__item" aria-label="Оплата по счёту">
        <div class="payment-systems__icon payment-systems__icon--invoice"><svg width="24" height="19" aria-hidden="true">
            <use xlink:href="img/sprite.svg#account"></use>
        </svg></div>
    </div>
    <div class="payment-systems__item" aria-label="MasterCard">
        <div class="payment-systems__icon payment-systems__icon--mastercard"><svg width="28" height="18" aria-hidden="true">
        <use xlink:href="<?=SITE_TEMPLATE_PATH?>/img/sprites/sprite.svg#mastercard"></use>
        </svg></div>
    </div>
    <div class="payment-systems__item" aria-label="Visa"><svg class="payment-systems__icon payment-systems__icon--visa" width="49" height="17" viewBox="0 0 49 17" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path id="Vector" d="M18.6774 0.737724L12.288 15.9773H8.13382L4.99559 3.8308C4.81497 3.08575 4.63435 2.81483 4.06992 2.49875C3.0991 1.97947 1.54127 1.48277 0.164062 1.18927L0.254371 0.737724H6.95981C7.81774 0.737724 8.58537 1.30215 8.76598 2.29555L10.4367 11.1007L14.5458 0.737724H18.6774ZM35.0007 11.0104C35.0233 6.99162 29.4467 6.76584 29.4693 4.98224C29.4919 4.44039 30.0111 3.85338 31.14 3.69534C31.7044 3.62761 33.2623 3.55988 35.0233 4.37266L35.7232 1.14411C34.7749 0.805455 33.5558 0.466797 32.0431 0.466797C28.1598 0.466797 25.4054 2.5439 25.3828 5.50152C25.3602 7.69151 27.347 8.91068 28.8371 9.65573C30.3724 10.4008 30.8917 10.8749 30.8917 11.5522C30.8691 12.5682 29.6725 13.0197 28.5436 13.0423C26.5568 13.0649 25.4054 12.5005 24.5023 12.0715L23.7798 15.4129C24.7055 15.8419 26.3988 16.2031 28.1598 16.2257C32.2915 16.2257 34.9781 14.1712 35.0007 11.0104ZM45.2959 15.9773H48.9309L45.7475 0.737724H42.3835C41.6158 0.737724 40.9837 1.16669 40.7128 1.84401L34.7975 15.9548H38.9291L39.7419 13.6745H44.7992L45.2959 15.9773ZM40.8934 10.5814L42.9705 4.86936L44.1671 10.5814H40.8934ZM24.2991 0.737724L21.048 15.9773H17.1196L20.3707 0.737724H24.2991Z" fill="url(#paint0_linear)"></path>
        <defs>
            <lineargradient id="paint0_linear" x1="0.164062" y1="8.34153" x2="48.9309" y2="8.34153" gradientUnits="userSpaceOnUse">
            <stop stop-color="#24215B"></stop>
            <stop offset="1" stop-color="#0C509F"></stop>
            </lineargradient>
        </defs>
        </svg></div>
    </div>
</div>
</div>
<div class="product-info__action">
<div class="product-info__ammount" id="input_div"><input id="moins" type="button" value="-"><input id="count" type="number" size="1" value="1" max="99"><input id="plus" type="button" value="+"></div><a class="button product-info__order-button" href="">
    <div class="promo-item__delivery__text">Заказать · <span>2 750</span> ₽</div>
</a><button class="product-info__favorite-desktop red-heart" type="button"></button>
</div>
</div>