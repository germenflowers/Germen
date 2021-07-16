<?php

/**
 * @global CMain $APPLICATION
 */

use \Bitrix\Main\Loader;
use \Bitrix\Main\Page\Asset;
use \Germen\Couriers\Courier;

if (empty($_REQUEST['token'])) {
    header('HTTP/1.0 403 Forbidden');
    exit;
}

require_once $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php';

if (!Loader::includeModule('germen.couriers')) {
    header('HTTP/1.0 403 Forbidden');
    exit;
}

$data = (new Courier())->getByToken($_REQUEST['token']);

if (empty($data)) {
    header('HTTP/1.0 403 Forbidden');
    exit;
}

$modulePath = '/local/modules/germen.couriers';
$templatePath = '/local/templates/admin';

$deliveryTimestamp = strtotime($data['order']['deliveryDate']);

$mobileDetect = new Mobile_Detect();

$isApple = $mobileDetect->isiPhone() ||
    $mobileDetect->isiOS() ||
    $mobileDetect->isiPad() ||
    $mobileDetect->isiPadOS() ||
    $mobileDetect->isSafari();
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
        Asset::getInstance()->addCss($templatePath.'/css/style.css');
        Asset::getInstance()->addCss($modulePath.'/css/style.css');
        Asset::getInstance()->addString(
            '<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">'
        );

        Asset::getInstance()->addJs($templatePath.'/js/jquery-3.6.0.min.js');
        Asset::getInstance()->addJs($templatePath.'/js/jquery-ui.min.js');
        Asset::getInstance()->addJs($modulePath.'/js/script.js');
        ?>
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
                        <div class="courier-top__order-number">Заказ № <?=$data['order']['id']?></div>
                        <div class="courier-top__order-status js-order-status"><?=$data['order']['status']['name']?></div>
                    </div>
                    <div class="courier-top__delivery">
                        <div class="courier-top__delivery-block">
                            <div class="courier-top__delivery-note">Доставить до</div>
                            <div class="courier-top__delivery-data"><?=date('H:i', $deliveryTimestamp)?></div>
                        </div>
                        <div class="courier-top__delivery-block">
                            <div class="courier-top__delivery-note">Дата доставки</div>
                            <div class="courier-top__delivery-data"><?=date('d.m.Y', $deliveryTimestamp)?></div>
                        </div>
                    </div>
                    <div class="courier-top__note">
                        <p><?=$data['order']['comment']?></p>
                    </div>
                </div>
                <div class="courier-tabs js-tabs" id="courierTabs">
                    <div class="courier-tabs__header js-tabs__header">
                        <div class="courier-tabs__header-inner">
                            <a class="courier-tabs__link courier-tabs__link--first js-tabs__title" href="">
                                Адрес заказа
                            </a>
                            <a class="courier-tabs__link courier-tabs__link--second js-tabs__title" href="">
                                Состав заказа
                            </a>
                            <div class="courier-tabs__chosen"></div>
                        </div>
                    </div>
                    <div class="courier-tabs__content courier-tabs__content--first js-tabs__content">
                        <div class="courier-contacts">
                            <div class="courier-contacts__block">
                                <p class="courier-contacts__block-line container-mobile">
                                    <span><?=$data['order']['address']?></span>
                                    <span>Офис/квартира: <?=$data['order']['flat']?></span>
                                </p>
                                <?php if ($isApple): ?>
                                    <a
                                            class="courier-contacts__link courier-contacts__link--map"
                                            href="https://maps.apple.com/maps?q=<?=$data['order']['address']?>"
                                            target="_blank"
                                    ></a>
                                <?php else: ?>
                                    <a
                                            class="courier-contacts__link courier-contacts__link--map"
                                            href="https://maps.google.com/?q=<?=$data['order']['address']?>"
                                            target="_blank"
                                    ></a>
                                <?php endif; ?>
                            </div>
                            <div class="courier-contacts__block">
                                <p class="courier-contacts__block-line container-mobile">
                                    <span class="courier-contacts__name"><?=$data['order']['userName']?></span>
                                    <span class="courier-contacts__tel"><?=$data['order']['userPhone']?></span>
                                </p>
                                <a
                                        class="courier-contacts__link courier-contacts__link--tel"
                                        href="tel:+<?=$data['order']['userPhone']?>"
                                ></a>
                            </div>
                            <?php if ($data['order']['isSurprise']): ?>
                                <div class="courier-contacts__gift container-mobile">
                                    <span>Это сюрприз</span>
                                </div>
                            <?php endif; ?>
                            <?php if ($data['order']['status']['id'] !== 'CA'): ?>
                                <div class="courier-contacts__btn container-mobile js-check-order">
                                    <button class="courier__btn" type="button">Проверить заказ</button>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="courier-tabs__content courier-tabs__content--second js-tabs__content">
                        <div class="courier-order-info">
                            <?php foreach ($data['order']['basket'] as $item): ?>
                                <div class="courier-order-info__block container-mobile">
                                    <div class="courier-order-info__block-image">
                                        <img src="<?=$item['image']?>" alt="<?=$item['name']?>">
                                    </div>
                                    <div class="courier-order-info__block-info">
                                        <div class="courier-order-info__block-name"><?=$item['name']?></div>
                                        <div class="courier-order-info__block-note"><?=$item['composition']?></div>
                                    </div>
                                </div>
                                <div class="courier-order-info__block container-mobile">
                                    <div class="courier-order-info__block-info">
                                        <div class="courier-order-info__block-name">Количество</div>
                                        <div class="courier-order-info__block-note"><?=$item['quantity']?> шт</div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                            <div class="courier-order-info__block container-mobile">
                                <div class="courier-order-info__block-info">
                                    <div class="courier-order-info__block-name">Текст открытки</div>
                                    <div class="courier-order-info__block-note">
                                        <?=$data['order']['note']?>
                                    </div>
                                </div>
                            </div>
                            <?php if ($data['order']['status']['id'] !== 'CA'): ?>
                                <form name="couriersForm" action="<?=$modulePath?>/ajax/handler.php" method="post">
                                    <input type="hidden" name="action" value="takeOrder">
                                    <input type="hidden" name="token" value="<?=$_REQUEST['token']?>">

                                    <div class="courier-order-info__block courier-order-info__block--no-border container-mobile">
                                        <input name="checkOrder" id="courierCheck" type="checkbox">
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
                                        <button class="courier__btn disabled" type="submit">Принять заказ</button>
                                    </div>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <div class="loader js-loader">
            <img src="<?=$modulePath?>/img/loader.gif" alt="">
        </div>
        <div class="overlay js-overlay"></div>

        <script src="<?=$templatePath?>/js/script.js"></script>
    </body>
</html>
