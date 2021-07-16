<?php

/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 */

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$this->setFrameMode(true);

$activeOrderType = 'new';
$activeOrderId = 0;
foreach ($arResult['orders'] as $type => $orders) {
    if (!empty($orders)) {
        $activeOrderType = $type;
        $activeOrderId = current($orders)['id'];
        break;
    }
}

$types = array(
    'new' => array('title' => 'Новые заказы', 'label' => 'Новый'),
    'collected' => array('title' => 'Собранные', 'label' => 'Собранный'),
    'canceled' => array('title' => 'Отмененные', 'label' => 'Отмененный'),
);

$noOrders = (empty($arResult['orders']['new']) && empty($arResult['orders']['collected']) && empty($arResult['orders']['canceled']));
?>
<div class="js-orders-container" data-time="<?=time()?>">
    <div class="aside js-aside" style="<?=$noOrders ? 'display: none;' : ''?>">
        <div class="aside__dropdown">
            <select name="orders" id="orders-select" class="js-orders-type-select">
                <?php foreach ($arResult['orders'] as $type => $orders): ?>
                    <option value="<?=$type?>" <?=$activeOrderType === $type ? 'selected="selected"' : ''?>>
                        <?=$types[$type]['title']?>
                        <span class="js-orders-type-count"><?=count($orders)?></span>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <?php foreach ($arResult['orders'] as $type => $orders): ?>
            <div
                    class="aside__orders-list js-aside-orders-list <?=$activeOrderType === $type ? 'aside__orders-list_active' : ''?>"
                    data-type="<?=$type?>"
            >
                <?php foreach ($orders as $order): ?>
                    <div class="aside__order js-aside-order" data-id="<?=$order['id']?>">
                        <div class="order order--is-opened snap-content">
                            <div class="order__title">№<?=$order['id']?></div>
                            <?php if ($type === 'new'): ?>
                                <div class="order__note js-order-note-container">
                                    <svg width="15" height="22" aria-hidden="true">
                                        <use xlink:href="<?=SITE_TEMPLATE_PATH?>/img/sprite.svg#clock"></use>
                                    </svg>
                                    <?=$order['asideNote']?>
                                </div>
                            <?php endif; ?>
                            <div class="order__state js-order-state-container">
                                <?php if ($type === 'collected'): ?>
                                    <div class="order__done">
                                        <svg width="17" height="15" aria-hidden="true">
                                            <use xlink:href="<?=SITE_TEMPLATE_PATH?>/img/sprite.svg#tick"></use>
                                        </svg>
                                    </div>
                                <?php elseif ($type === 'canceled'): ?>
                                    <div class="order__cancel"></div>
                                <?php else: ?>
                                    <div class="order__in-process">
                                        <div class="circle-timer" data-time-limit="<?=$order['timeLimit']?>"></div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php if ($type === 'new'): ?>
                            <div class="order__cancel snap-drawers js-order-delete-container">
                                <div
                                        class="snap-drawer snap-drawer-right js-order-delete-button"
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
        <?php endforeach; ?>
    </div>

    <?php if ($noOrders): ?>
        <div class="note-block js-empty-orders">
            <p class="note-block__text">Новых заказов пока нет</p>
        </div>
    <?php else: ?>
        <?php foreach ($arResult['orders'] as $type => $orders): ?>
            <?php foreach ($orders as $order): ?>
                <?php if ($type === 'new'): ?>
                    <div
                            class="steps <?=$activeOrderType === $type ? 'steps_active' : ''?> js-order-steps"
                            data-id="<?=$order['id']?>"
                    >
                        <div
                                class="steps__item js-order-take <?=$order['isStartBuild'] ? '' : 'is-active'?> <?=$order['isExpiringTime'] ? 'steps__item--small-text' : ''?>"
                                data-id="<?=$order['id']?>"
                        >
                            <?php if ($order['isExpiringTime']): ?>
                                Принять заказ с&nbsp;истекающим временем
                            <?php else: ?>
                                Принять заказ
                            <?php endif; ?>
                        </div>
                        <div class="steps__arrow"></div>
                        <div
                                class="steps__item js-order-ready <?=$order['isStartBuild'] && !$order['isBuild'] ? 'is-active' : ''?>"
                                data-id="<?=$order['id']?>"
                        >
                            Заказ готов
                        </div>
                        <div class="steps__arrow"></div>
                        <div
                                class="steps__item js-order-courier <?=$order['isBuild'] ? 'is-active' : ''?>"
                                data-id="<?=$order['id']?>"
                        >
                            Передать курьеру
                        </div>
                    </div>
                <?php endif; ?>

                <div
                        class="order-cover <?=$activeOrderId === $order['id'] ? 'order-cover_active' : ''?> js-order"
                        data-id="<?=$order['id']?>"
                >
                    <?php if ($type === 'new'): ?>
                        <?php if ($order['isBuild']): ?>
                            <div class="order-status">
                                <div class="order-status__img">
                                    <svg width="75" height="75" aria-hidden="true">
                                        <use xlink:href="<?=SITE_TEMPLATE_PATH?>/img/sprite.svg#bouquet"></use>
                                    </svg>
                                </div>
                                <div class="order-status__title">№<?=$order['id']?> готов</div>
                                <div class="order-status__text">Передайте заказ курьеру</div>
                            </div>
                        <?php else: ?>
                            <div class="order-summary">
                                <div class="order-summary__top">
                                    <div class="order-summary__schedule order-summary__block">
                                        <div class="order-summary__label">Срок приготовления</div>
                                        <div class="order-summary__data">
                                            <span
                                                    class="js-order-build-date"
                                                    data-id="<?=$order['id']?>"
                                                    data-time="<?=$order['orderBuildTime']?>"
                                            >
                                                <?=$order['orderBuildDate']?>
                                            </span>
                                            <span class="gray">
                                                /
                                                <span
                                                        class="js-order-build-timer"
                                                        data-id="<?=$order['id']?>"
                                                        data-time="<?=$order['orderBuildTimestamp']?>"
                                                        data-timestamp="<?=$order['orderBuildTimerTimestamp']?>"
                                                        data-init="<?=$order['isStartBuild'] ? 'Y' : 'N'?>"
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
                                            <?=$order['ordersBuildSumFormat']?>
                                            <span>₽</span>
                                        </div>
                                    </div>
                                    <div class="order-summary__state order-summary__block">
                                        <div class="order-summary__label order-summary__label--green">Новый</div>
                                    </div>
                                </div>
                                <div class="order-summary__bottom">
                                    <div class="order-summary__comment">
                                        <?=$order['comment']?>
                                    </div>
                                    <div class="order-summary__total-price">
                                        <?=$order['orderBuildPriceFormat']?>
                                        <span>₽</span>
                                    </div>
                                </div>
                            </div>
                            <div class="order-list">
                                <?php foreach ($order['basket'] as $basketItem): ?>
                                    <div class="order-item">
                                        <div class="order-item__img">
                                            <img src="<?=$basketItem['imageSrc']?>" alt="<?=$basketItem['name']?>">
                                        </div>
                                        <div class="order-item__info">
                                            <div class="order-item__info-top">
                                                <div class="order-item__name"><?=$basketItem['name']?></div>
                                                <div class="order-item__ammount"><?=$basketItem['quantity']?></div>
                                                <div class="order-item__price"><?=$basketItem['priceFormat']?> ₽</div>
                                            </div>
                                            <div class="order-item__extra"><?=$basketItem['propertiesString']?></div>
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
                                            <?php if (!empty($basketItem['orderNote'])): ?>
                                                <div class="order-item__block">
                                                    <div class="order-item__block-title">Текст открытки</div>
                                                    <div class="order-item__block-text"><?=$basketItem['orderNote']?></div>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    <?php elseif ($type === 'collected'): ?>
                        <div class="order-status">
                            <div class="order-status__img">
                                <svg width="90" height="59" aria-hidden="true">
                                    <use xlink:href="<?=SITE_TEMPLATE_PATH?>/img/sprite.svg#car-big"></use>
                                </svg>
                            </div>
                            <div class="order-status__title">№<?=$order['id']?> передан курьеру</div>
                        </div>
                    <?php else: ?>
                        <div class="order-status">
                            <div class="order-status__img">
                                <svg width="90" height="59" aria-hidden="true">
                                    <use xlink:href="<?=SITE_TEMPLATE_PATH?>/img/sprite.svg#bouquet"></use>
                                </svg>
                            </div>
                            <div class="order-status__title">№<?=$order['id']?> отменен</div>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<div class="modal fade" id="deleteModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content modal-delete">
            <div class="modal__header">
                <h5 class="modal-delete__header">Отмена заказа: несколько позиций</h5>
                <button class="modal-delete__close" type="button" data-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form name="deleteOrder" action="" method="post" class="modal-delete__form">
                    <input type="hidden" name="action" value="delete">
                    <input type="hidden" name="id" value="">

                    <div class="modal-delete__top">
                        <div class="modal-delete__radio">
                            <div class="modal-delete__title">Причины</div>
                            <div class="modal-delete__inputs">
                                <div class="modal-delete__input">
                                    <input type="radio" name="cancelation" id="not-free-cancel" value="not-free" checked>
                                    <label for="not-free-cancel">Отмена со списанием</label>
                                </div>
                                <div class="modal-delete__input">
                                    <input type="radio" name="cancelation" id="free-cancel" value="free">
                                    <label for="free-cancel">Отмена без списания</label>
                                </div>
                            </div>
                        </div>
                        <div class="modal-delete__textarea">
                            <div class="modal-delete__title">Комментарий</div>
                            <textarea name="comment" class="modal-delete__textarea-input" required="required"></textarea>
                        </div>
                    </div>
                    <div class="modal-delete__buttons">
                        <button class="btn btn-secondary" type="submit">Отправить</button>
                        <button class="btn btn-primary" type="button" data-dismiss="modal">Отмена</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="newOrdersModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal__header">
                <h5 class="modal-new-orders__header">
                    Появились новые заказы: <span class="js-modal-new-orders-id"></span>
                </h5>
                <button class="modal-new-orders__close" type="button" data-dismiss="modal"></button>
            </div>
        </div>
    </div>
</div>

<script id="noNewOrdersTmpl" type="text/x-jsrender">
    <div class="note-block">
        <p class="note-block__text">Новых заказов пока нет</p>
    </div>
</script>

<script id="orderStateCollectedTmpl" type="text/x-jsrender">
    <div class="order__done">
        <svg width="17" height="15" aria-hidden="true">
            <use xlink:href="<?=SITE_TEMPLATE_PATH?>/img/sprite.svg#tick"></use>
        </svg>
    </div>
</script>

<script id="orderStateCanceledTmpl" type="text/x-jsrender">
    <div class="order__cancel"></div>
</script>

<script id="orderBuildTmpl" type="text/x-jsrender">
    <div class="order-status">
        <div class="order-status__img">
            <svg width="75" height="75" aria-hidden="true">
                <use xlink:href="<?=SITE_TEMPLATE_PATH?>/img/sprite.svg#bouquet"></use>
            </svg>
        </div>
        <div class="order-status__title">№{{:id}} готов</div>
        <div class="order-status__text">Передайте заказ курьеру</div>
    </div>
</script>

<script id="orderCollectedTmpl" type="text/x-jsrender">
    <div class="order-status">
        <div class="order-status__img">
            <svg width="90" height="59" aria-hidden="true">
                <use xlink:href="<?=SITE_TEMPLATE_PATH?>/img/sprite.svg#car-big"></use>
            </svg>
        </div>
        <div class="order-status__title">№{{:id}} передан курьеру</div>
    </div>
</script>

<script id="orderCanceledTmpl" type="text/x-jsrender">
    <div class="order-status">
        <div class="order-status__img">
            <svg width="90" height="59" aria-hidden="true">
                <use xlink:href="<?=SITE_TEMPLATE_PATH?>/img/sprite.svg#bouquet"></use>
            </svg>
        </div>
        <div class="order-status__title">№{{:id}} отменен</div>
    </div>
</script>

<script id="asideOrderTmpl" type="text/x-jsrender">
    <div class="aside__order js-aside-order" data-id="{{:id}}">
        <div class="order order--is-opened snap-content">
            <div class="order__title">№{{:id}}</div>
            <div class="order__note js-order-note-container">
                <svg width="15" height="22" aria-hidden="true">
                    <use xlink:href="<?=SITE_TEMPLATE_PATH?>/img/sprite.svg#clock"></use>
                </svg>
                {{:asideNote}}
            </div>
            <div class="order__state js-order-state-container">
                <div class="order__in-process">
                    <div class="circle-timer" data-time-limit="{{:timeLimit}}"></div>
                </div>
            </div>
        </div>
        <div class="order__cancel snap-drawers js-order-delete-container">
            <div
                class="snap-drawer snap-drawer-right js-order-delete-button"
                data-target="#deleteModal"
                data-id="{{:id}}"
            >Отменить</div>
        </div>
    </div>
</script>

<script id="orderStepsTmpl" type="text/x-jsrender">
    <div class="steps steps_active js-order-steps" data-id="{{:id}}">
        <div
                class="steps__item js-order-take is-active {{if isExpiringTime}}steps__item--small-text{{/if}}"
                data-id="{{:id}}"
        >
            {{if isExpiringTime}}
                Принять заказ с&nbsp;истекающим временем
            {{else}}
                Принять заказ
            {{/if}}
        </div>
        <div class="steps__arrow"></div>
        <div class="steps__item js-order-ready" data-id="{{:id}}">Заказ готов</div>
        <div class="steps__arrow"></div>
        <div class="steps__item js-order-courier" data-id="{{:id}}">Передать курьеру</div>
    </div>
</script>

<script id="orderTmpl" type="text/x-jsrender">
    <div class="order-cover js-order" data-id="{{:id}}">
        <div class="order-summary">
            <div class="order-summary__top">
                <div class="order-summary__schedule order-summary__block">
                    <div class="order-summary__label">Срок приготовления</div>
                    <div class="order-summary__data">
                        <span class="js-order-build-date" data-id="{{:id}}" data-time="{{:orderBuildTime}}">
                            {{:orderBuildDate}}
                        </span>
                        <span class="gray">
                            /
                            <span
                                    class="js-order-build-timer"
                                    data-id="{{:id}}"
                                    data-time="{{:orderBuildTimestamp}}"
                                    data-timestamp="{{:orderBuildTimerTimestamp}}"
                                    data-init="{{if isStartBuild}}Y{{else}}N{{/if}}"
                            >
                                00:00
                            </span>
                        </span>
                    </div>
                </div>
                <div class="order-summary__order-number order-summary__block">
                    <div class="order-summary__label">Номер заказа</div>
                    <div class="order-summary__data">#{{:id}}</div>
                </div>
                <div class="order-summary__total-price order-summary__block">
                    <div class="order-summary__label">Итого за день</div>
                    <div class="order-summary__data">{{:ordersBuildSumFormat}} <span>₽</span></div>
                </div>
                <div class="order-summary__state order-summary__block">
                    <div class="order-summary__label order-summary__label--green">Новый</div>
                </div>
            </div>
            <div class="order-summary__bottom">
                <div class="order-summary__comment">{{:comment}}</div>
                <div class="order-summary__total-price">{{:orderBuildPriceFormat}} <span>₽</span></div>
            </div>
        </div>
        <div class="order-list">
            {{for basket}}
                <div class="order-item">
                    <div class="order-item__img">
                        <img src="{{>imageSrc}}" alt="{{>name}}">
                    </div>
                    <div class="order-item__info">
                        <div class="order-item__info-top">
                            <div class="order-item__name">{{>name}}</div>
                            <div class="order-item__ammount">{{>quantity}}</div>
                            <div class="order-item__price">{{>priceFormat}} ₽</div>
                        </div>
                        <div class="order-item__extra">{{>propertiesString}}</div>
                        {{if composition !== ''}}
                            <div class="order-item__block">
                                <div class="order-item__block-title">Состав</div>
                                <div class="order-item__block-text">{{>composition}}</div>
                            </div>
                        {{/if}}
                        {{if orderNote !== ''}}
                            <div class="order-item__block">
                                <div class="order-item__block-title">Текст открытки</div>
                                <div class="order-item__block-text">{{>orderNote}}</div>
                            </div>
                        {{/if}}
                    </div>
                </div>
            {{/for}}
        </div>
    </div>
</script>