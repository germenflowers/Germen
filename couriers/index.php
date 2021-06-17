<?php

/**
 * @global CMain $APPLICATION
 */

use \Bitrix\Main\Loader;
use \Bitrix\Main\Page\Asset;
use \Germen\Suppliers\Supplier;

//if (empty($_REQUEST['token'])) {
//    header('HTTP/1.0 403 Forbidden');
//    exit;
//}

require_once $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php';

//if (!Loader::includeModule('germen.suppliers')) {
//    header('HTTP/1.0 403 Forbidden');
//    exit;
//}

//$supplier = new Supplier;
//$data = $supplier->getByToken($_REQUEST['token']);
//$statuses = $supplier->getStatusList($data['status']);
//
//if (empty($data)) {
//    header('HTTP/1.0 403 Forbidden');
//    exit;
//}

$modulePath = '/local/modules/germen.suppliers';
$templatePath = '/local/templates/admin';
?>
<!doctype html>
<html lang="ru">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>Курьерам</title>

        <?php $APPLICATION->ShowHead(); ?>

        <link rel="preload" href="<?=$templatePath?>/fonts/SFProDisplay-Regular.woff2" as="font" type="font/woff2" crossorigin>
        <link rel="preload" href="<?=$templatePath?>/fonts/SFProDisplay-Bold.woff2" as="font" type="font/woff2" crossorigin>
        <link rel="preload" href="<?=$templatePath?>/fonts/SFProDisplay-Medium.woff2" as="font" type="font/woff2" crossorigin>
        <link rel="preload" href="<?=$templatePath?>/fonts/SFProDisplay-Semibold.woff2" as="font" type="font/woff2" crossorigin>

        <?php
        Asset::getInstance()->addCss($templatePath.'/css/style.min.css');
        Asset::getInstance()->addCss($templatePath.'/css/dev.css');
        Asset::getInstance()->addString(
            '<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">'
        );

        Asset::getInstance()->addJs($templatePath.'/js/jquery-3.6.0.min.js');
        Asset::getInstance()->addJs($templatePath.'/js/jquery-ui.min.js');
        Asset::getInstance()->addJs($templatePath.'/js/dev.js');
        ?>

<!--        <script type="text/javascript" src="--><?//=$modulePath?><!--/js/jquery-3-4-1.min.js"></script>-->
<!--        <script type="text/javascript" src="--><?//=$modulePath?><!--/js/script.js"></script>-->
    </head>
    <body class="mobile">
        <main>
            <div class="container">
                <div class="header header--mobile">
                    <div class="header__logo">
                        <svg width="83" height="20" aria-hidden="true">
                            <use xlink:href="<?=$templatePath?>/img/sprite.svg#logo"></use>
                        </svg>
                    </div>
                    <button class="header__info" type="button"></button>
                </div>
                <div class="courier-top container-mobile">
                    <div class="courier-top__order">
                        <div class="courier-top__order-number">Заказ № 2269</div>
                        <div class="courier-top__order-status">Новый</div>
                    </div>
                    <div class="courier-top__delivery">
                        <div class="courier-top__delivery-block">
                            <div class="courier-top__delivery-note">Доставить до</div>
                            <div class="courier-top__delivery-data">13:00</div>
                        </div>
                        <div class="courier-top__delivery-block">
                            <div class="courier-top__delivery-note">Дата доставки</div>
                            <div class="courier-top__delivery-data">03.03.2020</div>
                        </div>
                    </div>
                    <div class="courier-top__note">
                        <p>Домофон не работает. Наберите, пожалуйста, 89В и&nbsp;консьерж пустит вас в подъезд. Поднимайтесь
                            на&nbsp;6&nbsp;этаж, позвоните по телефону и оставьте цветы у&nbsp;двери на площадке. Заранее большое
                            спасибо!)
                        </p>
                    </div>
                </div>
                <div class="courier-tabs js-tabs" id="courierTabs">
                    <div class="courier-tabs__header js-tabs__header">
                        <div class="courier-tabs__header-inner">
                            <a class="courier-tabs__link courier-tabs__link--first js-tabs__title" href="">
                                Адрес
                                заказа
                            </a>
                            <a class="courier-tabs__link courier-tabs__link--second js-tabs__title" href="">
                                Состав
                                заказа
                            </a>
                            <div class="courier-tabs__chosen"></div>
                        </div>
                    </div>
                    <div class="courier-tabs__content courier-tabs__content--first js-tabs__content">
                        <div class="courier-contacts">
                            <div class="courier-contacts__block">
                                <p class="courier-contacts__block-line container-mobile">
                                    <span>Россия, г. Москва, Трёхпрудный&nbsp;пер., 4, стр. 1</span>
                                    <span>Офис/квартира: 401</span>
                                </p>
                                <a class="courier-contacts__link courier-contacts__link--map" href=""></a>
                            </div>
                            <div class="courier-contacts__block">
                                <p class="courier-contacts__block-line container-mobile">
                                    <span class="courier-contacts__name">Мария</span>
                                    <span class="courier-contacts__tel">+7 985 684-17-53</span>
                                </p>
                                <a class="courier-contacts__link courier-contacts__link--tel" href="tel:+79856841753"></a>
                            </div>
                            <div class="courier-contacts__gift container-mobile">
                                <span>Это сюрприз</span>
                            </div>
                            <div class="courier-contacts__btn container-mobile">
                                <button class="courier__btn" type="button">Проверить заказ</button>
                            </div>
                        </div>
                    </div>
                    <div class="courier-tabs__content courier-tabs__content--second js-tabs__content">
                        <div class="courier-order-info">
                            <div class="courier-order-info__block container-mobile">
                                <div class="courier-order-info__block-image">
                                    <img src="" alt="">
                                </div>
                                <div class="courier-order-info__block-info">
                                    <div class="courier-order-info__block-name">Chàmomile</div>
                                    <div class="courier-order-info__block-note">25 пионов сорта ян флеминг</div>
                                </div>
                            </div>
                            <div class="courier-order-info__block container-mobile">
                                <div class="courier-order-info__block-info">
                                    <div class="courier-order-info__block-name">Количество</div>
                                    <div class="courier-order-info__block-note">1 шт</div>
                                </div>
                            </div>
                            <div class="courier-order-info__block container-mobile">
                                <div class="courier-order-info__block-info">
                                    <div class="courier-order-info__block-name">Текст открытки</div>
                                    <div class="courier-order-info__block-note">
                                        Цветы для моего руководителя,
                                        наставника, учителя, примера и доброго человека. С днём рождения Ирина
                                        Александровна!!! от Рубена.
                                    </div>
                                </div>
                            </div>
                            <form>
                                <div class="courier-order-info__block courier-order-info__block--no-border container-mobile">
                                    <input id="courierCheck" type="checkbox">
                                    <label class="courier-order-info__block-info" for="courierCheck">
                                        <div class="courier-order-info__block-name courier-order-info__block-name--check">
                                            Проверил состав заказа
                                        </div>
                                        <div class="courier-order-info__block-note">
                                            Проверьте заказ, чтобы принять его
                                        </div>
                                    </label>
                                </div>
                                <div class="courier-order-info__btn">
                                    <button class="courier__btn disabled" type="button">Принять заказ</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <script src="<?=$templatePath?>/js/script.js"></script>
    </body>
</html>
