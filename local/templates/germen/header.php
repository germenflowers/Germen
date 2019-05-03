<?
use Bitrix\Main\Page\Asset;

$isHome = \PDV\Tools::isHomePage();
$isArticlePage = \PDV\Tools::isArticlePage();
$isOrderPage = \PDV\Tools::isOrderPage();
$isSubscribePage = \PDV\Tools::isSubscribePage();
$isTextPage = \PDV\Tools::isTextPage();
?>
<!doctype html>
<html lang="ru">
<head>
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <?$APPLICATION->ShowHead();?>
    <title><?$APPLICATION->ShowTitle()?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link rel="apple-touch-icon" href="<?=SITE_TEMPLATE_PATH?>/img/apple-touch-icon.png">
    <link rel="stylesheet" href="<?=SITE_TEMPLATE_PATH?>/css/main.css?v=1.6">
    <link rel="stylesheet" href="<?=SITE_TEMPLATE_PATH?>/js/air-datepicker/datepicker.min.css"/>
    <link rel="stylesheet" href="<?=SITE_TEMPLATE_PATH?>/css/custom.css?v=1.0">
    <?
    Asset::getInstance()->addJs(SITE_TEMPLATE_PATH.'/js/main.js');
    Asset::getInstance()->addJs(SITE_TEMPLATE_PATH.'/js/air-datepicker/datepicker.min.js');
    Asset::getInstance()->addJs(SITE_TEMPLATE_PATH.'/js/jquery.maskedinput.js');
    Asset::getInstance()->addJs(SITE_TEMPLATE_PATH.'/js/jquery.number.min.js');
    Asset::getInstance()->addJs(SITE_TEMPLATE_PATH.'/js/custom.js?v=1.212');
    ?>
    <script data-skip-moving="true">(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
            new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
        j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
        'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
    })(window,document,'script','dataLayer','GTM-MP6JBLB');</script>
</head>
<body>
<?$APPLICATION->ShowPanel()?>
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-MP6JBLB" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<?if( !$isOrderPage ):?>
    <nav id="menu-body" class="promo-menu">
    <div class="promo-menu__burger">
        <span class="toggle-menu--inside mobile-menu mobile-menu--white active"><span></span></span>
    </div>

    <ul class="promo-menu__nav">
        <?$APPLICATION->IncludeComponent(
            "bitrix:menu",
            "simple",
            Array(
                "ALLOW_MULTI_SELECT" => "N",
                "CHILD_MENU_TYPE" => "",
                "DELAY" => "N",
                "MAX_LEVEL" => "1",
                "MENU_CACHE_GET_VARS" => array(""),
                "MENU_CACHE_TIME" => "3600",
                "MENU_CACHE_TYPE" => "A",
                "MENU_CACHE_USE_GROUPS" => "Y",
                "ROOT_MENU_TYPE" => "top",
                "USE_EXT" => "N"
            )
        );?>
    </ul>

    <ul class="promo-menu__info">
        <?$APPLICATION->IncludeComponent(
            "bitrix:menu",
            "simple",
            Array(
                "ALLOW_MULTI_SELECT" => "N",
                "CHILD_MENU_TYPE" => "",
                "DELAY" => "N",
                "MAX_LEVEL" => "1",
                "MENU_CACHE_GET_VARS" => array(""),
                "MENU_CACHE_TIME" => "3600",
                "MENU_CACHE_TYPE" => "A",
                "MENU_CACHE_USE_GROUPS" => "Y",
                "ROOT_MENU_TYPE" => "footer",
                "USE_EXT" => "N"
            )
        );?>
    </ul>

    <div class="promo-menu__post">
        <p>
            <?$APPLICATION->IncludeComponent(
                "bitrix:main.include",
                ".default",
                array(
                    "AREA_FILE_SHOW" => "sect",
                    "AREA_FILE_SUFFIX" => "copyright_inc",
                    "EDIT_TEMPLATE" => "",
                    "COMPONENT_TEMPLATE" => ".default",
                    "AREA_FILE_RECURSIVE" => "Y"
                ),
                false
            );?>
        </p>
    </div>
</nav>
<?endif;?>
<? if (\Bitrix\Main\Loader::includeModule("germen.settings")): ?>
    <?
    $infoLineText = \UniPlug\Settings::get("INFO_LINE_TEXT");
    $infoLineIsShow = (bool)\UniPlug\Settings::get("INFO_LINE_IS_SHOW");

    if (!empty($infoLineText) && $infoLineIsShow && $_COOKIE["HIDE_INFOBAR"] !== "Y"):?>
        <div class="info-bar">
            <button class="info-bar__close" aria-label="close">
                <svg aria-hidden="true">
                    <use xlink:href="<?= SITE_TEMPLATE_PATH ?>/icons/icons.svg?v=<?=VERSION_SPRITE__ICONS?>#cross-2"></use>
                </svg>
            </button>
            <div class="info-bar__inner"><?= $infoLineText ?></div>
        </div>
    <? endif; ?>
<? endif; ?>

<main id="panel-body">
    <?if( !$isOrderPage ):?>
        <div class="header">
            <div class="header__container">
                <div class="header__logo">
                    <a href="/" class="logo" title="germen"></a>
                </div>

                <?
                $phone = trim(str_replace(array('(',')','-',' '), '', file_get_contents($_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/include/phone.php')));
                ?>
                <a href="tel:<?=$phone?>" class="header__phone">
                    <svg width="24px" height="24px" aria-hidden="true">
                        <use xlink:href="<?=SITE_TEMPLATE_PATH?>/icons/icons.svg?v=<?=VERSION_SPRITE__ICONS?>#phone"></use>
                    </svg>
                </a>

                <div class="header__content">
                    <div class="header__nav" id="header-nav">
                        <ul class="nav">
                            <?$APPLICATION->IncludeComponent(
                                "bitrix:menu",
                                "simple",
                                Array(
                                    "ALLOW_MULTI_SELECT" => "N",
                                    "CHILD_MENU_TYPE" => "",
                                    "DELAY" => "N",
                                    "MAX_LEVEL" => "1",
                                    "MENU_CACHE_GET_VARS" => array(""),
                                    "MENU_CACHE_TIME" => "3600",
                                    "MENU_CACHE_TYPE" => "A",
                                    "MENU_CACHE_USE_GROUPS" => "Y",
                                    "ROOT_MENU_TYPE" => "top",
                                    "USE_EXT" => "N"
                                )
                            );?>
                            <li><a href="tel:<?=$phone?>"> <?$APPLICATION->IncludeFile(SITE_TEMPLATE_PATH.'/include/phone.php')?></a></li>
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
        <div class="content<?if ( !$isArticlePage && !$isSubscribePage ){?> content--main<?}?>">
            <?if ( $isArticlePage ) :?>
                <div class="content__container">
                    <div class="promo-article">
                        <?$arrFilterBanner = array('=CODE' => $APPLICATION->GetCurDir());?>
                        <?$APPLICATION->IncludeComponent(
                            "bitrix:news.list",
                            "promo-article",
                            Array(
                                "ACTIVE_DATE_FORMAT" => "d.m.Y",
                                "ADD_SECTIONS_CHAIN" => "N",
                                "AJAX_MODE" => "N",
                                "AJAX_OPTION_ADDITIONAL" => "",
                                "AJAX_OPTION_HISTORY" => "N",
                                "AJAX_OPTION_JUMP" => "N",
                                "AJAX_OPTION_STYLE" => "Y",
                                "CACHE_FILTER" => "Y",
                                "CACHE_GROUPS" => "Y",
                                "CACHE_TIME" => "36000000",
                                "CACHE_TYPE" => "A",
                                "CHECK_DATES" => "Y",
                                "DETAIL_URL" => "",
                                "DISPLAY_BOTTOM_PAGER" => "N",
                                "DISPLAY_DATE" => "N",
                                "DISPLAY_NAME" => "Y",
                                "DISPLAY_PICTURE" => "N",
                                "DISPLAY_PREVIEW_TEXT" => "N",
                                "DISPLAY_TOP_PAGER" => "N",
                                "FIELD_CODE" => array("", ""),
                                "FILTER_NAME" => "arrFilterBanner",
                                "HIDE_LINK_WHEN_NO_DETAIL" => "N",
                                "IBLOCK_ID" => IBLOCK_ID__PROMO_BANNER,
                                "IBLOCK_TYPE" => "germen",
                                "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
                                "INCLUDE_SUBSECTIONS" => "N",
                                "MESSAGE_404" => "",
                                "NEWS_COUNT" => "20",
                                "PAGER_BASE_LINK_ENABLE" => "N",
                                "PAGER_DESC_NUMBERING" => "N",
                                "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
                                "PAGER_SHOW_ALL" => "N",
                                "PAGER_SHOW_ALWAYS" => "N",
                                "PAGER_TEMPLATE" => ".default",
                                "PAGER_TITLE" => "",
                                "PARENT_SECTION" => "",
                                "PARENT_SECTION_CODE" => "",
                                "PREVIEW_TRUNCATE_LEN" => "",
                                "PROPERTY_CODE" => array("", ""),
                                "SET_BROWSER_TITLE" => "N",
                                "SET_LAST_MODIFIED" => "N",
                                "SET_META_DESCRIPTION" => "N",
                                "SET_META_KEYWORDS" => "N",
                                "SET_STATUS_404" => "N",
                                "SET_TITLE" => "N",
                                "SHOW_404" => "N",
                                "SORT_BY1" => "ACTIVE_FROM",
                                "SORT_BY2" => "SORT",
                                "SORT_ORDER1" => "DESC",
                                "SORT_ORDER2" => "ASC",
                                "STRICT_SECTION_CHECK" => "N"
                            )
                        );?>

                        <div class="head-h2"><?$APPLICATION->ShowTitle()?></div>
            <?else:?>
                <?if ( !$isSubscribePage ) :?>
                    <div class="promo-main">
                        <?
                        $arrFilterBanner = array();
                        if ( \PDV\Tools::is404() )
                            $arrFilterBanner = array('=CODE' => '404');
                        else
                            $arrFilterBanner = array('=CODE' => $APPLICATION->GetCurDir());
                        ?>
                        <?$APPLICATION->IncludeComponent(
                            "bitrix:news.list",
                            "promo-banner",
                            Array(
                                "ACTIVE_DATE_FORMAT" => "d.m.Y",
                                "ADD_SECTIONS_CHAIN" => "N",
                                "AJAX_MODE" => "N",
                                "AJAX_OPTION_ADDITIONAL" => "",
                                "AJAX_OPTION_HISTORY" => "N",
                                "AJAX_OPTION_JUMP" => "N",
                                "AJAX_OPTION_STYLE" => "Y",
                                "CACHE_FILTER" => "Y",
                                "CACHE_GROUPS" => "Y",
                                "CACHE_TIME" => "36000000",
                                "CACHE_TYPE" => "A",
                                "CHECK_DATES" => "Y",
                                "DETAIL_URL" => "",
                                "DISPLAY_BOTTOM_PAGER" => "N",
                                "DISPLAY_DATE" => "N",
                                "DISPLAY_NAME" => "Y",
                                "DISPLAY_PICTURE" => "N",
                                "DISPLAY_PREVIEW_TEXT" => "N",
                                "DISPLAY_TOP_PAGER" => "N",
                                "FIELD_CODE" => array("", ""),
                                "FILTER_NAME" => "arrFilterBanner",
                                "HIDE_LINK_WHEN_NO_DETAIL" => "N",
                                "IBLOCK_ID" => IBLOCK_ID__PROMO_BANNER,
                                "IBLOCK_TYPE" => "germen",
                                "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
                                "INCLUDE_SUBSECTIONS" => "N",
                                "MESSAGE_404" => "",
                                "NEWS_COUNT" => "20",
                                "PAGER_BASE_LINK_ENABLE" => "N",
                                "PAGER_DESC_NUMBERING" => "N",
                                "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
                                "PAGER_SHOW_ALL" => "N",
                                "PAGER_SHOW_ALWAYS" => "N",
                                "PAGER_TEMPLATE" => ".default",
                                "PAGER_TITLE" => "",
                                "PARENT_SECTION" => "",
                                "PARENT_SECTION_CODE" => "",
                                "PREVIEW_TRUNCATE_LEN" => "",
                                "PROPERTY_CODE" => array("", ""),
                                "SET_BROWSER_TITLE" => "N",
                                "SET_LAST_MODIFIED" => "N",
                                "SET_META_DESCRIPTION" => "N",
                                "SET_META_KEYWORDS" => "N",
                                "SET_STATUS_404" => "N",
                                "SET_TITLE" => "N",
                                "SHOW_404" => "N",
                                "SORT_BY1" => "ACTIVE_FROM",
                                "SORT_BY2" => "SORT",
                                "SORT_ORDER1" => "DESC",
                                "SORT_ORDER2" => "ASC",
                                "STRICT_SECTION_CHECK" => "N"
                            )
                        );?>

                        <?/*if ( $isHome ): ?>
                            <?$APPLICATION->IncludeComponent(
                                "bitrix:news.list",
                                "promo-icons",
                                Array(
                                    "ACTIVE_DATE_FORMAT" => "d.m.Y",
                                    "ADD_SECTIONS_CHAIN" => "N",
                                    "AJAX_MODE" => "N",
                                    "AJAX_OPTION_ADDITIONAL" => "",
                                    "AJAX_OPTION_HISTORY" => "N",
                                    "AJAX_OPTION_JUMP" => "N",
                                    "AJAX_OPTION_STYLE" => "Y",
                                    "CACHE_FILTER" => "N",
                                    "CACHE_GROUPS" => "Y",
                                    "CACHE_TIME" => "36000000",
                                    "CACHE_TYPE" => "A",
                                    "CHECK_DATES" => "Y",
                                    "DETAIL_URL" => "",
                                    "DISPLAY_BOTTOM_PAGER" => "N",
                                    "DISPLAY_DATE" => "N",
                                    "DISPLAY_NAME" => "Y",
                                    "DISPLAY_PICTURE" => "N",
                                    "DISPLAY_PREVIEW_TEXT" => "N",
                                    "DISPLAY_TOP_PAGER" => "N",
                                    "FIELD_CODE" => array("", ""),
                                    "FILTER_NAME" => "",
                                    "HIDE_LINK_WHEN_NO_DETAIL" => "N",
                                    "IBLOCK_ID" => IBLOCK_ID__PROMO_ICONS,
                                    "IBLOCK_TYPE" => "germen",
                                    "INCLUDE_IBLOCK_INTO_CHAIN" => "N",
                                    "INCLUDE_SUBSECTIONS" => "N",
                                    "MESSAGE_404" => "",
                                    "NEWS_COUNT" => "20",
                                    "PAGER_BASE_LINK_ENABLE" => "N",
                                    "PAGER_DESC_NUMBERING" => "N",
                                    "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
                                    "PAGER_SHOW_ALL" => "N",
                                    "PAGER_SHOW_ALWAYS" => "N",
                                    "PAGER_TEMPLATE" => ".default",
                                    "PAGER_TITLE" => "",
                                    "PARENT_SECTION" => "",
                                    "PARENT_SECTION_CODE" => "",
                                    "PREVIEW_TRUNCATE_LEN" => "",
                                    "PROPERTY_CODE" => array("", ""),
                                    "SET_BROWSER_TITLE" => "N",
                                    "SET_LAST_MODIFIED" => "N",
                                    "SET_META_DESCRIPTION" => "N",
                                    "SET_META_KEYWORDS" => "N",
                                    "SET_STATUS_404" => "N",
                                    "SET_TITLE" => "N",
                                    "SHOW_404" => "N",
                                    "SORT_BY1" => "ACTIVE_FROM",
                                    "SORT_BY2" => "SORT",
                                    "SORT_ORDER1" => "DESC",
                                    "SORT_ORDER2" => "ASC",
                                    "STRICT_SECTION_CHECK" => "N"
                                )
                            );?>
                        <?endif;*/?>
                    </div>
                    <?if ( $isTextPage ):?>
                        <div class="content__container">
                            <div class="promo-article<?if($isSubscribePage){?> promo-article--subscribe<?}?>">
                    <?endif;?>
                <?endif;?>
            <?endif;?>
    <?endif;?>