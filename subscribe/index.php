<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php';

global $APPLICATION;

$APPLICATION->SetTitle('Подписка на цветы');
?>
<div class="container-block">
    <div class="hero">
        <div class="hero__slider">
            <div class="swiper-container hero__slider-container-index">
                <?php $APPLICATION->IncludeComponent(
                    'bitrix:news.list',
                    'flower_subscription_slider',
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
                        'IBLOCK_ID' => IBLOCK_ID__SUBSCRIBE_SLIDER,
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
        <div class="hero__text">
            <h2 class="hero__title"><?php $APPLICATION->ShowTitle(); ?></h2>
            <div class="hero__intro">
                <?php $APPLICATION->IncludeComponent(
                    'bitrix:main.include',
                    '',
                    array(
                        'AREA_FILE_SHOW' => 'file',
                        'AREA_FILE_SUFFIX' => '',
                        'EDIT_TEMPLATE' => '',
                        'PATH' => SITE_TEMPLATE_PATH.'/include/flower_subscription/text1.php',
                    )
                ); ?>
            </div>
            <a href="/subscribe/choice/" class="hero__btn button">Подписаться</a>
        </div>
    </div>
    <?php $APPLICATION->IncludeComponent(
        'bitrix:news.list',
        'flower_subscription_promo',
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
            'IBLOCK_ID' => IBLOCK_ID__SUBSCRIBE_PROMO,
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
    <div class="subscribe">
        <div class="subscribe__tabs js-tabs" id="tabs">
            <ul class="subscribe__header js-tabs__header">
                <li class="subscribe__list-item">
                    <a class="subscribe__link js-tabs__title" href="">Монобукеты</a>
                </li>
                <li class="subscribe__list-item">
                    <a class="subscribe__link js-tabs__title" href="">Составные букеты</a>
                </li>
            </ul>
            <div class="subscribe__content js-tabs__content">
                <p class="subscribe__text">
                    <?php $APPLICATION->IncludeComponent(
                        'bitrix:main.include',
                        '',
                        array(
                            'AREA_FILE_SHOW' => 'file',
                            'AREA_FILE_SUFFIX' => '',
                            'EDIT_TEMPLATE' => '',
                            'PATH' => SITE_TEMPLATE_PATH.'/include/flower_subscription/mono_bouquets_text1.php',
                        )
                    ); ?>
                </p>
                <?php $APPLICATION->IncludeComponent(
                    'bitrix:news.list',
                    'bouquets_slider',
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
            <div class="subscribe__content js-tabs__content">
                <p class="subscribe__text">
                    <?php $APPLICATION->IncludeComponent(
                        'bitrix:main.include',
                        '',
                        array(
                            'AREA_FILE_SHOW' => 'file',
                            'AREA_FILE_SUFFIX' => '',
                            'EDIT_TEMPLATE' => '',
                            'PATH' => SITE_TEMPLATE_PATH.'/include/flower_subscription/composite_bouquets_text1.php',
                        )
                    ); ?>
                </p>
                <?php $APPLICATION->IncludeComponent(
                    'bitrix:news.list',
                    'bouquets_slider',
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
        <p class="subscribe__note">
            <?php $APPLICATION->IncludeComponent(
                'bitrix:main.include',
                '',
                array(
                    'AREA_FILE_SHOW' => 'file',
                    'AREA_FILE_SUFFIX' => '',
                    'EDIT_TEMPLATE' => '',
                    'PATH' => SITE_TEMPLATE_PATH.'/include/flower_subscription/text2.php',
                )
            ); ?>
        </p>
        <a href="/subscribe/choice/" class="button subscribe__btn">Оформить подписку</a>
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