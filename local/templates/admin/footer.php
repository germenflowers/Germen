<?php

/**
 * @global CMain $APPLICATION
 */

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}
?>
            </div>
        </main>

        <div class="page-loader js-page-loader">
            <img src="<?=SITE_TEMPLATE_PATH?>/img/loader.gif" alt="">
        </div>
        <div class="page-overlay js-page-overlay"></div>

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