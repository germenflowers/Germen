<?php

/**
 * @global CMain $APPLICATION
 */


require_once $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/urlrewrite.php';

CHTTP::SetStatus('404 Not Found');
@define('ERROR_404', 'Y');

require_once $_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php';

$APPLICATION->SetPageProperty('NOT_SHOW_NAV_CHAIN', 'Y');
$APPLICATION->SetTitle('Страница не найдена');
?>
<div class="content__container">
    <div class="promo-catalog__wrapper">
        <p>К сожалению, запрашиваемая страница не найдена</p>
        <br/>
        <p>Мы приносим свои извинения за доставленные неудобства и предлагаем Вам:</p>
        <p>вернуться назад при помощи кнопки браузера
            <a href="javascript:history.go(-2);">back</a>
            или перейти на
            <a href="/">главную страницу</a>
        </p>
    </div>
</div>
<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php';
?>