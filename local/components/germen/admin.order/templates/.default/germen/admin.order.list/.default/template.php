<?php

/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 */

use \Germen\Admin\Content;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$this->setFrameMode(true);

//global $APPLICATION;
//$APPLICATION->RestartBuffer();
//echo '<pre>'.print_r($arResult, true).'</pre>';
//die();
?>
<?php if (
    empty($arResult['orders']['new']) &&
    empty($arResult['orders']['collected']) &&
    empty($arResult['orders']['canceled'])
): ?>
    <div class="note-block">
        <p class="note-block__text">Новых заказов пока нет</p>
    </div>
<?php else: ?>
    <div class="aside">
        <div class="aside__dropdown">
            <select name="orders" id="orders-select" class="js-orders-type-select">
                <?php
                $first = true;
                ?>
                <?php foreach ($arResult['orders'] as $type => $orders): ?>
                    <?php
                    if (empty($orders)) {
                        continue;
                    }

                    $title = 'Новые заказы';
                    if ($type === 'collected') {
                        $title = 'Собранные';
                    }
                    if ($type === 'canceled') {
                        $title = 'Отмененные';
                    }
                    ?>
                    <option value="<?=$type?>" <?=$first ? 'selected="selected"' : ''?>>
                        <?=$title?>
                        <span><?=count($orders)?></span>
                    </option>
                    <?php
                    $first = false;
                    ?>
                <?php endforeach; ?>
            </select>
        </div>
        <?php
        $first = true;
        ?>
        <?php foreach ($arResult['orders'] as $type => $orders): ?>
            <div class="aside__orders-list <?=$first ? 'aside__orders-list_active' : ''?>" data-type="<?=$type?>">
                <?php foreach ($orders as $order): ?>
                    <?php
                    $timeLimit = $order['dateBuildEstimated']->getTimestamp() - time();
                    if ($timeLimit < 0) {
                        $timeLimit = 0;
                    }

                    $note = 'Собрать к '.$order['dateBuildEstimated']->format('H:i');

                    if ($timeLimit < 30 * 60) {
                        $note = 'Собрать как можно скорее';
                    }

                    if ($order['dateBuildEstimated']->format('d.m.Y') !== date('d.m.Y')) {
                        $note = 'Собрать к '.$order['dateBuildEstimated']->format('d.m.y H:i');
                    }
                    ?>
                    <div class="aside__order js-aside-order" data-id="<?=$order['id']?>">
                        <div class="order order--is-opened snap-content">
                            <div class="order__title">№<?=$order['id']?></div>
                            <?php if ($type === 'new'): ?>
                                <div class="order__note">
                                    <svg width="15" height="22" aria-hidden="true">
                                        <use xlink:href="<?=SITE_TEMPLATE_PATH?>/img/sprite.svg#clock"></use>
                                    </svg>
                                    <?=$note?>
                                </div>
                            <?php endif; ?>
                            <div class="order__state">
                                <?php if ($type === 'collected'): ?>
                                    <div class="order__done">
                                        <svg width="17" height="15" aria-hidden="true">
                                            <use xlink:href="<?=SITE_TEMPLATE_PATH?>/img/sprite.svg#tick"></use>
                                        </svg>
                                    </div>
                                <?php elseif ($type === 'canceled'): ?>
                                    <div class="order__cancel">

                                    </div>
                                <?php else: ?>
                                    <div class="order__in-process">
                                        <div class="circle-timer" data-time-limit="<?=$timeLimit?>"></div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php if ($type === 'new'): ?>
                            <div class="order__cancel snap-drawers">
                                <div
                                        class="snap-drawer snap-drawer-right"
                                        data-target="#deleteModal"
                                        data-id="<?=$order['id']?>"
                                >
                                    Отменить
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php
            $first = false;
            ?>
        <?php endforeach; ?>
    </div>

    <?php
    $first = true;
    ?>
    <?php foreach ($arResult['orders'] as $type => $orders): ?>
        <?php
        $label = 'Новый';
        if ($type === 'collected') {
            $label = 'Собранный';
        }
        if ($type === 'canceled') {
            $label = 'Отмененный';
        }
        ?>
        <?php foreach ($orders as $order): ?>
            <?php
            $orderBuildPrice = 0;
            $orderBuildTime = 0;
            foreach ($order['basket'] as $basketItem) {
                $orderBuildPrice += $basketItem['buildPrice'] * $basketItem['quantity'];
                $orderBuildTime += $basketItem['buildTime'];
            }

            if (empty($orderBuildTime)) {
                $orderBuildTime = 10 * 60;
            }

            $timeLimit = $order['dateBuildEstimated']->getTimestamp() - time();
            if ($timeLimit < 0) {
                $timeLimit = 0;
            }

            $expiringTime = $timeLimit < 30 * 60;

            $isStartBuild = !empty($order['dateBuildStart']);

            $orderBuildTimestamp = time() + $orderBuildTime;
            if ($isStartBuild) {
                $orderBuildTimestamp = $order['dateBuildStart']->getTimestamp() + $orderBuildTime;
            }

            $orderBuildTimerTimestamp = $orderBuildTimestamp - time();
            if ($orderBuildTimerTimestamp < 0) {
                $orderBuildTimerTimestamp = 0;
            }
            ?>
            <div class="steps <?=$first ? 'steps_active' : ''?> js-order-steps" data-id="<?=$order['id']?>">
                <div
                        class="steps__item js-order-take <?=$isStartBuild ? '' : 'is-active'?> <?=$expiringTime ? 'steps__item--small-text' : ''?>"
                        data-id="<?=$order['id']?>"
                >
                    <?php if ($expiringTime): ?>
                        Принять заказ с&nbsp;истекающим временем
                    <?php else: ?>
                        Принять заказ
                    <?php endif; ?>
                </div>
                <div class="steps__arrow"></div>
                <div class="steps__item js-order-ready <?=$isStartBuild ? 'is-active' : ''?>">Заказ готов</div>
                <div class="steps__arrow"></div>
                <div class="steps__item js-order-courier">Передать курьеру</div>
            </div>
            <div class="order-cover <?=$first ? 'order-cover_active' : ''?> js-order" data-id="<?=$order['id']?>">
                <div class="order-summary">
                    <div class="order-summary__top">
                        <div class="order-summary__schedule order-summary__block">
                            <div class="order-summary__label">Срок приготовления</div>
                            <div class="order-summary__data">
                                <span
                                        class="js-order-build-date"
                                        data-id="<?=$order['id']?>"
                                        data-time="<?=$orderBuildTime?>"
                                >
                                    <?=date('H:i', $orderBuildTimestamp)?>
                                </span>
                                <span class="gray">
                                    /
                                    <span
                                            class="js-order-build-timer"
                                            data-id="<?=$order['id']?>"
                                            data-time="<?=$orderBuildTime?>"
                                            data-timestamp="<?=$orderBuildTimerTimestamp?>"
                                            data-init="<?=$isStartBuild ? 'Y' : 'N'?>"
                                    >
                                        00:00
                                    </span>
                                </span>
                            </div>
                        </div>
                        <div class="order-summary__order-number order-summary__block">
                            <div class="order-summary__label">Номер заказа</div>
                            <div class="order-summary__data">#<?=$order['id']?></div>
                        </div>
                        <div class="order-summary__total-price order-summary__block">
                            <div class="order-summary__label">Итого за день</div>
                            <div class="order-summary__data">
                                <?=number_format($arResult['buildSum'], 0, '', ' ')?>
                                <span>₽</span>
                            </div>
                        </div>
                        <div class="order-summary__state order-summary__block">
                            <div class="order-summary__label <?=$type === 'new' ? 'order-summary__label--green' : ''?>">
                                <?=$label?>
                            </div>
                        </div>
                    </div>
                    <div class="order-summary__bottom">
                        <div class="order-summary__comment">
                            <?=$order['comment']?>
                        </div>
                        <div class="order-summary__total-price">
                            <?=number_format($orderBuildPrice, 0, '', ' ')?>
                            <span>₽</span>
                        </div>
                    </div>
                </div>
                <div class="order-list">
                    <?php foreach ($order['basket'] as $basketItem): ?>
                        <?php
                        $image = array('src' => '');
                        if (!empty($basketItem['imageId'])) {
                            $image = CFile::ResizeImageGet(
                                $basketItem['imageId'],
                                array('width' => 90, 'height' => 100),
                                BX_RESIZE_IMAGE_PROPORTIONAL
                            );
                        }

                        $price = number_format(
                            $basketItem['buildPrice'] * $basketItem['quantity'],
                            0,
                            '',
                            ' '
                        );

                        $propertiesString = Content::getHistoryListBasketItemPropertiesString(
                            $basketItem['properties']
                        );
                        ?>
                        <div class="order-item">
                            <div class="order-item__img">
                                <img src="<?=$image['src']?>" alt="<?=$basketItem['name']?>">
                            </div>
                            <div class="order-item__info">
                                <div class="order-item__info-top">
                                    <div class="order-item__name"><?=$basketItem['name']?></div>
                                    <div class="order-item__ammount"><?=$basketItem['quantity']?></div>
                                    <div class="order-item__price"><?=$price?> ₽</div>
                                </div>
                                <div class="order-item__extra"><?=$propertiesString?></div>
                                <?php if (!empty($basketItem['composition'])): ?>
                                    <div class="order-item__block">
                                        <div class="order-item__block-title">Состав</div>
                                        <div class="order-item__block-text"><?=$basketItem['composition']?></div>
                                    </div>
                                <?php endif; ?>
                                <?php /*
                                <div class="order-item__block">
                                    <div class="order-item__block-title">Комментарий</div>
                                    <div class="order-item__block-text"></div>
                                </div>
                                */ ?>
                                <?php if (!empty($order['note'])): ?>
                                    <div class="order-item__block">
                                        <div class="order-item__block-title">Текст открытки</div>
                                        <div class="order-item__block-text"><?=$order['note']?></div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php
            $first = false;
            ?>
        <?php endforeach; ?>
    <?php endforeach; ?>

<?php endif; ?>
<?php /*
<div class="order-cover">
    <div class="order-status">
        <div class="order-status__img">
            <svg width="75" height="75" aria-hidden="true">
                <use xlink:href="img/sprite.svg#bouquet"></use>
            </svg>
        </div>
        <div class="order-status__title">№934013 готов</div>
        <div class="order-status__text">Передайте заказ курьеру</div>
    </div>
</div>
*/ ?>
<?php /*
<div class="order-cover">
    <div class="order-status">
        <div class="order-status__img">
            <svg width="90" height="59" aria-hidden="true">
                <use xlink:href="img/sprite.svg#car-big"></use>
            </svg>
        </div>
        <div class="order-status__title">№934013 передан курьеру</div>
    </div>
</div>
*/ ?>