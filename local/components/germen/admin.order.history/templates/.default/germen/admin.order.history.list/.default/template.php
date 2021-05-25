<?php

/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 */

use Germen\Admin\Content;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$this->setFrameMode(true);
?>
<div class="history__table">
    <div class="table">
        <div class="thead">
            <div class="th history__table-order">Заказ</div>
            <div class="th history__table-composition">Содержимое заказа</div>
            <div class="th history__table-date">Дата</div>
            <div class="th history__table-time">Время</div>
            <div class="th history__table-sum">Сумма</div>
        </div>
        <div class="tbody">
            <div class="history__table js-items" id="history-accordion">
                <?php foreach ($arResult['orders'] as $item): ?>
                    <?php
                    $price = number_format((int)$item['price'], 0, '', ' ');

                    $title = Content::getHistoryListItemTitle($item['basket']);
                    ?>
                    <h3 class="tr">
                        <div class="td history__table-order">№<?=$item['id']?></div>
                        <div class="td history__table-composition"><?=$title?></div>
                        <div class="td history__table-date"><?=$item['dateCreate']->format('d.m.Y')?></div>
                        <div class="td history__table-time"><?=$item['dateCreate']->format('H:i')?></div>
                        <div class="td history__table-sum"><?=$price?> ₽</div>
                        <div class="td--arrow"></div>
                    </h3>
                    <div>
                        <table class="history__table--inner">
                            <?php foreach ($item['basket'] as $basketItem): ?>
                                <?php
                                $price = number_format((int)$basketItem['price'], 0, '', ' ');

                                $propertiesString = Content::getHistoryListBasketItemPropertiesString($basketItem['properties']);
                                ?>
                                <tr>
                                    <td class="td td--inner history__table-composition history__table-composition--inner">
                                        <?=$basketItem['name']?>
                                        <?php if (!empty($propertiesString)): ?>
                                            <span><?=$propertiesString?></span>
                                        <?php endif ?>
                                    </td>
                                    <td class="td td--inner history__table-ammont"><?=$basketItem['quantity']?></td>
                                    <td class="td td--inner history__table-sum--inner"><?=$price?> ₽</td>
                                </tr>
                            <?php endforeach; ?>
                        </table>
                        <div class="history__table-comment">
                            <?php if (!empty($item['comment'])): ?>
                                <svg width="21" height="40" aria-hidden="true">
                                    <use xlink:href="<?=SITE_TEMPLATE_PATH?>/img/sprite.svg#comment"></use>
                                </svg>
                                <?=$item['comment']?>
                            <?php endif; ?>
                        </div>
                        <div class="history__table-notes">
                            <?php if (!empty($item['dateBuild'])): ?>
                                <div class="history__table-note1">
                                    Приготовлен в <?=$item['dateBuild']->format('H:i')?>
                                </div>
                            <?php endif; ?>
                            <?php /*
                            <div class="history__table-note2">Телефон: +7 905 932 3493</div>
                            */ ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
<div class="js-pagenavigation" data-entity="history"><?=$arResult['pageNavigation']?></div>
<script id="historyTmpl" type="text/x-jsrender">
    <h3 class="tr">
        <div class="td history__table-order">№{{:id}}</div>
        <div class="td history__table-composition">{{:title}}</div>
        <div class="td history__table-date">{{:date}}</div>
        <div class="td history__table-time">{{:time}}</div>
        <div class="td history__table-sum">{{:price}} ₽</div>
        <div class="td--arrow"></div>
    </h3>
    <div>
        <table class="history__table--inner">
            {{for basket}}
                <tr>
                    <td class="td td--inner history__table-composition history__table-composition--inner">
                        {{>name}}
                        {{if propertiesString !== ''}}
                            <span>{{>propertiesString}}</span>
                        {{/if}}
                    </td>
                    <td class="td td--inner history__table-ammont">{{>quantity}}</td>
                    <td class="td td--inner history__table-sum--inner">{{>price}} ₽</td>
                </tr>
            {{/for}}
        </table>
        <div class="history__table-comment">
            {{if comment !== ''}}
                <svg width="21" height="40" aria-hidden="true">
                    <use xlink:href="<?=SITE_TEMPLATE_PATH?>/img/sprite.svg#comment"></use>
                </svg>
                {{:comment}}
            {{/if}}
        </div>
        <div class="history__table-notes">
            {{if buildTime !== ''}}
                <div class="history__table-note1">Приготовлен в {{:buildTime}}</div>
            {{/if}}
        </div>
    </div>
</script>
