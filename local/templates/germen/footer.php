<?php

/**
 * @var bool $isOrderPage
 * @var bool $isArticlePage
 * @var bool $isTextPage
 * @var bool $isFavoritePage
 * @var bool $isCarePage
 * @var bool $isCartPage
 * @global CMain $APPLICATION
 */

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}
?>
            <?php if (!$isOrderPage && !$isCarePage): ?>
                    <?php if ($isArticlePage || $isTextPage) : ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if ($isFavoritePage) : ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="footer">
                    <div class="footer__container">
                        <div class="promo-about">
                            <?php $APPLICATION->IncludeComponent(
                                'bitrix:main.include',
                                '',
                                array(
                                    'AREA_FILE_SHOW' => 'file',
                                    'AREA_FILE_SUFFIX' => '',
                                    'EDIT_TEMPLATE' => '',
                                    'PATH' => SITE_TEMPLATE_PATH.'/include/footer/about.php',
                                )
                            ); ?>
                        </div>
                    </div>
                    <div class="footer__main">
                        <div class="footer__container">
                            <div class="footer__content">
                                <ul class="footer__nav">
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
                                <p>
                                    <?php $APPLICATION->IncludeComponent(
                                        'bitrix:main.include',
                                        '',
                                        array(
                                            'AREA_FILE_SHOW' => 'file',
                                            'AREA_FILE_SUFFIX' => '',
                                            'EDIT_TEMPLATE' => '',
                                            'PATH' => SITE_TEMPLATE_PATH.'/include/footer/copyright.php',
                                        )
                                    ); ?>
                                </p>
                            </div>
                            <div class="footer__info">
                                <ul class="footer__social">
                                    <?php $APPLICATION->IncludeComponent(
                                        'bitrix:main.include',
                                        '',
                                        array(
                                            'AREA_FILE_SHOW' => 'file',
                                            'AREA_FILE_SUFFIX' => '',
                                            'EDIT_TEMPLATE' => '',
                                            'PATH' => SITE_TEMPLATE_PATH.'/include/footer/social.php',
                                        )
                                    ); ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </main>

        <?php if (!$isOrderPage && !$isCartPage): ?>
            <?php $APPLICATION->IncludeComponent(
                'bitrix:sale.basket.basket',
                'mobile',
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
        <?php endif; ?>

        <?php $APPLICATION->IncludeComponent(
            'bitrix:main.include',
            '',
            array(
                'AREA_FILE_SHOW' => 'file',
                'AREA_FILE_SUFFIX' => '',
                'EDIT_TEMPLATE' => '',
                'PATH' => SITE_TEMPLATE_PATH.'/include/footer/modals.php',
            )
        ); ?>

        <div class="pagenavigation-loader js-pagenavigation-loader">
            <img src="<?=SITE_TEMPLATE_PATH?>/img/loader.gif">
        </div>
        <div class="pagenavigation-overlay js-pagenavigation-overlay"></div>

        <script src="<?=SITE_TEMPLATE_PATH?>/js/script.js"></script>

        <?php $APPLICATION->IncludeComponent(
            'bitrix:main.include',
            '',
            array(
                'AREA_FILE_SHOW' => 'file',
                'AREA_FILE_SUFFIX' => '',
                'EDIT_TEMPLATE' => '',
                'PATH' => SITE_TEMPLATE_PATH.'/include/footer/counters.php',
            )
        ); ?>
    </body>
</html>