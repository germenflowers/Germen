<?php

use \Bitrix\Main\Loader;
use \Bitrix\Main\Page\Asset;
use \PDV\Tools;
use \UniPlug\Settings;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

global $APPLICATION;

$isHome = Tools::isHomePage();
$isArticlePage = Tools::isArticlePage();
$isOrderPage = Tools::isOrderPage();
$isSubscribePage = Tools::isSubscribePage();
$isSubscribeTestPage = Tools::isSubscribeTestPage();
$isTextPage = Tools::isTextPage();

$infoLineText = '';
$infoLineIsShow = false;
$ratingYell = 0;
$cntReviewsYell = 0;
if (Loader::includeModule('germen.settings')) {
    $infoLineText = Settings::get('INFO_LINE_TEXT');
    $infoLineIsShow = (bool)Settings::get('INFO_LINE_IS_SHOW');
    $ratingYell = (float)Settings::get('YELL_RATING');
    $cntReviewsYell = (int)Settings::get('YELL_REVIEWS_CNT');
}

$phone = trim(
    str_replace(
        array('(', ')', '-', ' '),
        '',
        file_get_contents($_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/include/phone.php')
    )
);
?>
<!doctype html>
<html lang="ru">
    <head>
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">

        <?php $APPLICATION->ShowHead(); ?>

        <title><?php $APPLICATION->ShowTitle() ?></title>

        <link rel="apple-touch-icon" href="<?=SITE_TEMPLATE_PATH?>/img/apple-touch-icon.png">

        <link rel="preload" href="<?=SITE_TEMPLATE_PATH?>/fonts/MuseoSansCyrl100.woff2" as="font" type="font/woff2" crossorigin>
        <link rel="preload" href="<?=SITE_TEMPLATE_PATH?>/fonts/MuseoSansCyrl500.woff2" as="font" type="font/woff2" crossorigin>
        <link rel="preload" href="<?=SITE_TEMPLATE_PATH?>/fonts/MuseoSansCyrl700.woff2" as="font" type="font/woff2" crossorigin>
        <link rel="preload" href="<?=SITE_TEMPLATE_PATH?>/fonts/MuseoSansCyrl900.woff2" as="font" type="font/woff2" crossorigin>

        <link rel="stylesheet" href="<?=SITE_TEMPLATE_PATH?>/js/air-datepicker/datepicker.min.css"/>
        <link rel="stylesheet" href="<?=SITE_TEMPLATE_PATH?>/css/main.css?v=1.13">
        <link rel="stylesheet" href="<?=SITE_TEMPLATE_PATH?>/css/custom.css?v=1.1">

        <?php if ($isSubscribeTestPage): ?>
            <link rel="stylesheet" href="<?=SITE_TEMPLATE_PATH?>/css/subscribe.min.css">
        <?php endif; ?>

        <?php
        Asset::getInstance()->addJs(SITE_TEMPLATE_PATH.'/js/main.js');
        Asset::getInstance()->addJs(SITE_TEMPLATE_PATH.'/js/air-datepicker/datepicker.min.js');
        Asset::getInstance()->addJs(SITE_TEMPLATE_PATH.'/js/jquery.maskedinput.js');
        Asset::getInstance()->addJs(SITE_TEMPLATE_PATH.'/js/jquery.number.min.js');
        Asset::getInstance()->addJs(SITE_TEMPLATE_PATH.'/js/custom.js?v=1.212');
        Asset::getInstance()->addJs(SITE_TEMPLATE_PATH.'/js/jquery.mask.js');
        Asset::getInstance()->addJs(SITE_TEMPLATE_PATH.'/js/jquery.inputmask.bundle.min.js');
        Asset::getInstance()->addJs(SITE_TEMPLATE_PATH.'/js/jquery.inputmask-multi.min.js');
        Asset::getInstance()->addJs(SITE_TEMPLATE_PATH.'/js/moment-with-locales.min.js');

        if ($isSubscribeTestPage) {
            Asset::getInstance()->addJs(SITE_TEMPLATE_PATH.'/js/subscribe.min.js');
        }
        ?>

        <script data-skip-moving="true">
          (function (w, d, s, l, i) {
            w[l] = w[l] || [];
            w[l].push({
              'gtm.start':
                new Date().getTime(), event: 'gtm.js'
            });
            var f = d.getElementsByTagName(s)[0],
              j = d.createElement(s), dl = l != 'dataLayer' ? '&l=' + l : '';
            j.async = true;
            j.src =
              'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
            f.parentNode.insertBefore(j, f);
          })(window, document, 'script', 'dataLayer', 'GTM-MP6JBLB');
        </script>
    </head>
    <body>
        <?$APPLICATION->ShowPanel()?>

        <noscript>
            <iframe src="https://www.googletagmanager.com/ns.html?id=GTM-MP6JBLB" height="0" width="0" style="display:none;visibility:hidden"></iframe>
        </noscript>

        <?php if (!$isOrderPage): ?>
            <nav id="menu-body" class="promo-menu">
                <div class="promo-menu__burger">
                    <span class="toggle-menu--inside mobile-menu mobile-menu--white active">
                        <span></span>
                    </span>
                </div>
                <ul class="promo-menu__nav">
                    <?php $APPLICATION->IncludeComponent(
                        'bitrix:menu',
                        'simple',
                        array(
                            'ALLOW_MULTI_SELECT' => 'N',
                            'CHILD_MENU_TYPE' => '',
                            'DELAY' => 'N',
                            'MAX_LEVEL' => 1,
                            'MENU_CACHE_GET_VARS' => array(),
                            'MENU_CACHE_TIME' => 3600,
                            'MENU_CACHE_TYPE' => 'A',
                            'MENU_CACHE_USE_GROUPS' => 'Y',
                            'ROOT_MENU_TYPE' => 'top',
                            'USE_EXT' => 'N',
                        )
                    ); ?>
                </ul>
                <ul class="promo-menu__info">
                    <?php $APPLICATION->IncludeComponent(
                        'bitrix:menu',
                        'simple',
                        array(
                            'ALLOW_MULTI_SELECT' => 'N',
                            'CHILD_MENU_TYPE' => '',
                            'DELAY' => 'N',
                            'MAX_LEVEL' => 1,
                            'MENU_CACHE_GET_VARS' => array(),
                            'MENU_CACHE_TIME' => 3600,
                            'MENU_CACHE_TYPE' => 'A',
                            'MENU_CACHE_USE_GROUPS' => 'Y',
                            'ROOT_MENU_TYPE' => 'footer',
                            'USE_EXT' => 'N',
                        )
                    ); ?>
                </ul>
                <div class="promo-menu__post">
                    <p>
                        <?php $APPLICATION->IncludeComponent(
                            'bitrix:main.include',
                            '.default',
                            array(
                                'AREA_FILE_SHOW' => 'sect',
                                'AREA_FILE_SUFFIX' => 'copyright_inc',
                                'EDIT_TEMPLATE' => '',
                                'COMPONENT_TEMPLATE' => '.default',
                                'AREA_FILE_RECURSIVE' => 'Y',
                            ),
                            false
                        ); ?>
                    </p>
                </div>
            </nav>
        <?php endif; ?>

        <?php if (!empty($infoLineText) && $infoLineIsShow && $_COOKIE['HIDE_INFOBAR'] !== 'Y'): ?>
            <div class="info-bar">
                <button class="info-bar__close" aria-label="close">
                    <svg aria-hidden="true">
                        <use xlink:href="<?=SITE_TEMPLATE_PATH?>/icons/icons.svg?v=<?=VERSION_SPRITE__ICONS?>#cross-2"></use>
                    </svg>
                </button>
                <div class="info-bar__inner"><?=$infoLineText?></div>
            </div>
        <?php endif; ?>

        <main id="panel-body">
            <?php if (!$isOrderPage): ?>
                <div class="header">
                    <div class="header__container">
                        <div class="header__logo">
                            <a href="/" class="logo" title="germen"></a>
                        </div>
                        <a href="tel:<?=$phone?>" class="header__phone">
                            <svg width="24px" height="24px" aria-hidden="true">
                                <use xlink:href="<?=SITE_TEMPLATE_PATH?>/icons/icons.svg?v=<?=VERSION_SPRITE__ICONS?>#phone"></use>
                            </svg>
                        </a>
                        <div class="header__content">
                            <div class="header__nav" id="header-nav">
                                <ul class="nav">
                                    <?php $APPLICATION->IncludeComponent(
                                        'bitrix:menu',
                                        'simple',
                                        array(
                                            'ALLOW_MULTI_SELECT' => 'N',
                                            'CHILD_MENU_TYPE' => '',
                                            'DELAY' => 'N',
                                            'MAX_LEVEL' => 1,
                                            'MENU_CACHE_GET_VARS' => array(),
                                            'MENU_CACHE_TIME' => 3600,
                                            'MENU_CACHE_TYPE' => 'A',
                                            'MENU_CACHE_USE_GROUPS' => 'Y',
                                            'ROOT_MENU_TYPE' => 'top',
                                            'USE_EXT' => 'N',
                                        )
                                    ); ?>
                                    <li>
                                        <a href="tel:<?=$phone?>">
                                            <?php $APPLICATION->IncludeFile(SITE_TEMPLATE_PATH.'/include/phone.php')?>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="header__burger">
                            <span class="toggle-menu mobile-menu">
                                <svg width="26px" height="22px" aria-hidden="true">
                                    <use xlink:href="<?=SITE_TEMPLATE_PATH?>/icons/icons.svg?v=<?=VERSION_SPRITE__ICONS?>#burger"></use>
                                </svg>
                            </span>
                        </div>
                    </div>
                </div>
                
                <div class="content <?=(!$isArticlePage && !$isSubscribePage && !$isSubscribeTestPage) ? 'content--main' : ''?>">
                    <?php if (!$isArticlePage && !$isSubscribePage && !$isSubscribeTestPage): ?>
                        <div class="promo-main">
                            <?php
                            $arrFilterBanner = array();
                            if (Tools::is404()) {
                                $arrFilterBanner = array('=CODE' => '404');
                            } else {
                                $arrFilterBanner = array('=CODE' => $APPLICATION->GetCurDir());
                            }
                            ?>
                            <?php $APPLICATION->IncludeComponent(
                                'bitrix:news.list',
                                'promo-banner',
                                array(
                                    'ACTIVE_DATE_FORMAT' => 'd.m.Y',
                                    'ADD_SECTIONS_CHAIN' => 'N',
                                    'AJAX_MODE' => 'N',
                                    'AJAX_OPTION_ADDITIONAL' => '',
                                    'AJAX_OPTION_HISTORY' => 'N',
                                    'AJAX_OPTION_JUMP' => 'N',
                                    'AJAX_OPTION_STYLE' => 'Y',
                                    'CACHE_FILTER' => 'Y',
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
                                    'FILTER_NAME' => 'arrFilterBanner',
                                    'HIDE_LINK_WHEN_NO_DETAIL' => 'N',
                                    'IBLOCK_ID' => IBLOCK_ID__PROMO_BANNER,
                                    'IBLOCK_TYPE' => 'germen',
                                    'INCLUDE_IBLOCK_INTO_CHAIN' => 'N',
                                    'INCLUDE_SUBSECTIONS' => 'N',
                                    'MESSAGE_404' => '',
                                    'NEWS_COUNT' => 20,
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
                                    'PROPERTY_CODE' => array(),
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
                                    'YELL_RATING' => $ratingYell,
                                    'YELL_REVIEWS_CNT' => $cntReviewsYell,
                                )
                            ); ?>
                            <?php /* if ($isHome): ?>
                                <?php $APPLICATION->IncludeComponent(
                                    'bitrix:news.list',
                                    'promo-icons',
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
                                        'IBLOCK_ID' => IBLOCK_ID__PROMO_ICONS,
                                        'IBLOCK_TYPE' => 'germen',
                                        'INCLUDE_IBLOCK_INTO_CHAIN' => 'N',
                                        'INCLUDE_SUBSECTIONS' => 'N',
                                        'MESSAGE_404' => '',
                                        'NEWS_COUNT' => 20,
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
                                        'PROPERTY_CODE' => array(),
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
                            <?php endif; */?>
                        </div>
                    <?php endif; ?>

                    <?php if ($isArticlePage || $isTextPage) : ?>
                        <div class="content__container">
                            <div class="promo-article">
                                <?php if ($isArticlePage): ?>
                                    <?php
                                    $arrFilterBanner = array('=CODE' => $APPLICATION->GetCurDir());
                                    ?>
                                    <?php $APPLICATION->IncludeComponent(
                                        'bitrix:news.list',
                                        'promo-article',
                                        array(
                                            'ACTIVE_DATE_FORMAT' => 'd.m.Y',
                                            'ADD_SECTIONS_CHAIN' => 'N',
                                            'AJAX_MODE' => 'N',
                                            'AJAX_OPTION_ADDITIONAL' => '',
                                            'AJAX_OPTION_HISTORY' => 'N',
                                            'AJAX_OPTION_JUMP' => 'N',
                                            'AJAX_OPTION_STYLE' => 'Y',
                                            'CACHE_FILTER' => 'Y',
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
                                            'FILTER_NAME' => 'arrFilterBanner',
                                            'HIDE_LINK_WHEN_NO_DETAIL' => 'N',
                                            'IBLOCK_ID' => IBLOCK_ID__PROMO_BANNER,
                                            'IBLOCK_TYPE' => 'germen',
                                            'INCLUDE_IBLOCK_INTO_CHAIN' => 'N',
                                            'INCLUDE_SUBSECTIONS' => 'N',
                                            'MESSAGE_404' => '',
                                            'NEWS_COUNT' => 20,
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
                                            'PROPERTY_CODE' => array(),
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
                                    <div class="head-h2"><?php $APPLICATION->ShowTitle() ?></div>
                                <?php endif; ?>
                    <?php endif; ?>
            <?php endif; ?>