<?php

/**
 * @var bool $isOrderPage
 * @var bool $isArticlePage
 * @var bool $isTextPage
 * @var bool $isFavoritePage
 * @var bool $isCarePage
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

        <div class="pagenavigation-loader js-pagenavigation-loader">
            <img src="<?=SITE_TEMPLATE_PATH?>/img/loader.gif">
        </div>
        <div class="pagenavigation-overlay js-pagenavigation-overlay"></div>

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