<?php

use Bitrix\Main\Application;

/**
 * @global CMain $APPLICATION
 * @var array $arParams
 * @var array $arResult
 * @var string $templateFolder
 */

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$this->setFrameMode(true);

$context = Application::getInstance()->getContext();
$request = $context->getRequest();

if ($arParams['SET_TITLE'] === 'Y' && strlen($request->get('ORDER_ID'))) {
    $APPLICATION->SetTitle('Заказ оформлен');
}

$hasUpsale = false;
$hasBookmate = false;

$orderPrice = (int)$arResult['ORDER_PRICE'] + (int)$arResult['DELIVERY_PRICE'];
$oldOrderPrice = (int)$arResult['PRICE_WITHOUT_DISCOUNT_VALUE'];
$discount = $oldOrderPrice - (int)$arResult['DISCOUNT_PRICE'];

$goodsPrice = 0;
$oldGoodsPrice = 0;
$upsalePrice = 0;
$oldUpsalePrice = 0;
foreach ($arResult['BASKET_ITEMS'] as $item) {
    if ($item['upsale']) {
        $hasUpsale = true;

        $upsalePrice += $item['sum'];
        $oldUpsalePrice += $item['oldSum'];
    } else {
        $goodsPrice += $item['sum'];
        $oldGoodsPrice += $item['oldSum'];
    }

    if ($item['bookmate']) {
        $hasBookmate = true;
    }
}

$orderPriceFormat = number_format($orderPrice, 0, '', ' ');
$oldOrderPriceFormat = number_format($oldOrderPrice, 0, '', ' ');
$discountFormat = number_format($discount, 0, '', ' ');
$goodsPriceFormat = number_format($goodsPrice, 0, '', ' ');
$oldGoodsPriceFormat = number_format($oldGoodsPrice, 0, '', ' ');
$upsalePriceFormat = number_format($upsalePrice, 0, '', ' ');
$oldUpsalePriceFormat = number_format($oldUpsalePrice, 0, '', ' ');
?>
<?php if (strlen($request->get('ORDER_ID')) > 0): ?>
    <?php
    include Application::getDocumentRoot().$templateFolder.'/confirm.php';
    ?>
<?php elseif ($arParams['DISABLE_BASKET_REDIRECT'] === 'Y' && $arResult['SHOW_EMPTY_BASKET']): ?>
    <?php
    include Application::getDocumentRoot().$templateFolder.'/empty.php';
    ?>
<?php else: ?>
    <header class="order-header">
        <div class="order-header__logo">
            <a href="/" class="logo"></a>
        </div>
    </header>
    <div class="order-content">
        <form class="order-form" name="orderForm" action="" method="post">
            <input type="hidden" name="countDateDelivery" value="<?=$arResult['countDateDelivery']?>">
            <input class="input" type="hidden" name="ORDER_PROP_1" value="1">

            <div class="order-form__main">
                <div class="order-form__title">Оформление заказа</div>

                <div class="order-form__section">
                    <?php if (!empty($arResult['UPSALE_PRODUCTS'])): ?>
                        <div class="order-form__subtitle order-form__subtitle--offers">Комплименты</div>
                        <div class="order-offers js-order-offers-slider">
                            <div class="order-offers__list">
                                <?php foreach ($arResult['UPSALE_PRODUCTS'] as $item): ?>
                                    <?php
                                    $itemPriceFormat = number_format($item['price'], 0, '', ' ');
                                    ?>
                                    <div class="order-offers__item">
                                        <div class="order-offer">
                                            <button
                                                    class="order-offer__add js-order-add-upsale"
                                                    type="button"
                                                    aria-label="Добавить к заказу"
                                                    data-id="<?=$item['id']?>"
                                                    data-productid="<?=$item['id']?>"
                                            >
                                                +
                                            </button>
                                            <div class="order-offer__image">
                                                <img
                                                        src="<?=$item['image']?>"
                                                        srcset="<?=$item['image']?> 1x, <?=$item['image']?> 2x"
                                                        alt=""
                                                >
                                            </div>
                                            <div class="order-offer__title"><?=$item['name']?></div>
                                            <div class="order-offer__price">
                                                +
                                                <span><?=$itemPriceFormat?></span>
                                                <span class="rouble"></span>
                                            </div>
                                            <div class="order-offer__text"><?=$item['text']?></div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <button class="swiper-button-next"></button>
                            <button class="swiper-button-prev"></button>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($arResult['BOOKMATE_PRODUCTS'])): ?>
                        <a class="order-bookmate js-order-bookmate" href="#" title="Добавить бесплатную книгу">
                            <span class="order-bookmate__title">Добавить бесплатную книгу</span>
                            <span class="order-bookmate__text">
                                Спецпроект с
                                <span class="order-bookmate__logo">
                                    <span class="sr-only">Bookmate</span>
                                </span>
                            </span>
                        </a>
                        <div class="order-offers js-order-offers-slider js-order-bookmate-items hidden">
                            <div class="order-offers__list">
                                <?php foreach ($arResult['BOOKMATE_PRODUCTS'] as $item): ?>
                                    <div class="order-offers__item">
                                        <div class="order-offer">
                                            <button
                                                    class="order-offer__add js-order-add-bookmate"
                                                    type="button"
                                                    aria-label="Добавить к заказу"
                                                    data-id="<?=$item['id']?>"
                                                    data-productid="<?=$item['id']?>"
                                                    style="<?=$hasBookmate ? 'display:none;' : ''?>"
                                            >
                                                +
                                            </button>
                                            <div class="order-offer__image">
                                                <img
                                                        src="<?=$item['image']?>"
                                                        srcset="<?=$item['image']?> 1x, <?=$item['image']?> 2x"
                                                        alt=""
                                                />
                                            </div>
                                            <div class="order-offer__title"><?=$item['name']?></div>
                                            <div class="order-offer__text"><?=$item['text']?></div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                            <button class="swiper-button-next"></button>
                            <button class="swiper-button-prev"></button>
                        </div>
                    <?php endif; ?>
                </div>

                <div class="order-form__section">
                    <div class="order-form__subtitle">Адрес доставки</div>
                    <div class="promo-order__row">
                        <div class="promo-order__block js-address-block">
                            <div class="input__wrapper input__wrapper--mark">
                                <input
                                        class="input js-address-property"
                                        type="text"
                                        name="ORDER_PROP_2"
                                        value=""
                                        placeholder="Улица и дом"
                                >
                                <div class="list_street">
                                    <ul class="js-search-street"></ul>
                                </div>
                            </div>
                        </div>
                        <div class="promo-order__block js-address-block">
                            <div class="input__wrapper">
                                <input
                                        class="input"
                                        type="text"
                                        name="ORDER_PROP_3"
                                        value=""
                                        placeholder="Офис, квартира"
                                >
                            </div>
                        </div>
                        <div class="promo-order__block promo-order__block--full">
                            <div class="styled-checkbox__block u-mb-0">
                                <input
                                        class="styled-checkbox"
                                        id="ORDER_PROP_15"
                                        name="ORDER_PROP_15"
                                        type="checkbox"
                                        value="Y"
                                >
                                <label for="ORDER_PROP_15">Я не знаю адрес доставки</label>
                                <span
                                        class="promo-tooltip promo-tooltip--question"
                                        title="Мы уточним удобное время и место доставки у получателя, не раскрывая сюрприз"
                                        data-toggle="tooltip"
                                        data-placement="bottom"
                                >
                                    <svg class="" width="23px" height="23px">
                                        <use xlink:href="<?=SITE_TEMPLATE_PATH?>/icons/icons.svg?v=1.2#question"></use>
                                    </svg>
                                </span>
                            </div>
                        </div>
                        <div class="promo-order__block promo-order__block--full js-address-block" style="width: 100%;">
                            <div class="order-form__map" id="delivery-map"></div>
                        </div>
                    </div>
                </div>

                <div class="order-form__section">
                    <div class="order-form__subtitle">Когда доставить</div>
                    <div class="promo-order__row">
                        <?php for ($i = 1; $i <= $arResult['countDateDelivery']; $i++): ?>
                            <div class="promo-order__block promo-order__block--full js-order-delivery-time">
                                <input
                                        class="input js-order-datetime"
                                        type="text"
                                        name="ORDER_PROP_4[<?=$i - 1?>]"
                                        value=""
                                        placeholder="Когда доставить"
                                        autocomplete="off"
                                        data-time="<?=$arResult['deliveryTime']?>"
                                    <?php if (!empty($arResult['activeEndTime'])): ?>
                                        data-maxtime="<?=$arResult['activeEndTime']?>"
                                    <?php endif; ?>
                                    <?php if (!empty($arResult['informationBanner'])): ?>
                                        data-mintime="<?=strtotime(date('d.m.Y').' 23:59')?>"
                                    <?php endif; ?>
                                >
                            </div>
                        <?php endfor; ?>
                    </div>
                </div>

                <div class="order-form__section">
                    <div class="order-form__subtitle">Ваш телефон</div>
                    <div class="promo-order__row">
                        <div class="promo-order__block">
                            <div class="input__wrapper input__wrapper--mark">
                                <input
                                        class="input js-phone-1"
                                        type="text"
                                        name="ORDER_PROP_5"
                                        value=""
                                >
                                <input type="checkbox" id="phone-mask-1" checked style="display:none;">
                            </div>
                        </div>
                        <div class="promo-order__block promo-order__block--full">
                            <div class="styled-checkbox__block styled-checkbox__block--full">
                                <input
                                        class="styled-checkbox"
                                        id="ORDER_PROP_6"
                                        type="checkbox"
                                        name="ORDER_PROP_6"
                                        value="Y"
                                >
                                <label for="ORDER_PROP_6">Не звонить для подтверждения</label>
                            </div>
                            <div class="styled-checkbox__block styled-checkbox__block--full">
                                <input
                                        class="styled-checkbox"
                                        id="ORDER_PROP_7"
                                        type="checkbox"
                                        name="ORDER_PROP_7"
                                        value="Y"
                                >
                                <label for="ORDER_PROP_7">Отправить фотографии букета перед доставкой</label>
                            </div>
                            <div class="styled-checkbox__block styled-checkbox__block--full u-mb-0">
                                <input
                                        class="styled-checkbox"
                                        id="ORDER_PROP_18"
                                        type="checkbox"
                                        name="ORDER_PROP_18"
                                        value="Y"
                                >
                                <label for="ORDER_PROP_18">Анонимный заказ</label>
                                <span
                                        class="promo-tooltip promo-tooltip--question"
                                        title="Мы не передаем получателю и курьеру никаких данных о заказчике"
                                        data-toggle="tooltip"
                                        data-placement="bottom"
                                >
                                    <svg class="" width="23px" height="23px">
                                        <use xlink:href="<?=SITE_TEMPLATE_PATH?>/icons/icons.svg?v=1.1#question"></use>
                                    </svg>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="order-form__section">
                    <div class="order-form__subtitle">Отправить электронный чек на E-mail</div>
                    <div class="promo-order__row">
                        <div class="promo-order__block">
                            <div class="input__wrapper input__wrapper--mark">
                                <input
                                        class="input"
                                        type="email"
                                        name="ORDER_PROP_19"
                                        value=""
                                        placeholder="E-mail"
                                >
                            </div>
                        </div>
                    </div>
                </div>

                <div class="order-form__section">
                    <div class="order-form__subtitle">Данные получателя</div>
                    <div class="promo-order__row">
                        <div class="promo-order__block">
                            <div class="input__wrapper input__wrapper--mark">
                                <input
                                        class="input js-phone-2"
                                        type="text"
                                        name="ORDER_PROP_8"
                                        value=""
                                >
                                <input type="checkbox" id="phone-mask-2" checked style="display:none;">
                            </div>
                        </div>
                        <div class="promo-order__block">
                            <div class="input__wrapper input__wrapper--mark">
                                <input
                                        class="input"
                                        type="text"
                                        name="ORDER_PROP_9"
                                        value=""
                                        placeholder="Имя"
                                >
                            </div>
                        </div>
                        <div class="promo-order__block promo-order__block--full hidden">
                            <div class="styled-checkbox__block">
                                <input
                                        class="styled-checkbox"
                                        id="ORDER_PROP_14"
                                        type="checkbox"
                                        name="ORDER_PROP_14"
                                        value="Y"
                                >
                                <label for="ORDER_PROP_14">Придумайте вместо меня!</label>
                            </div>
                        </div>
                        <div class="promo-order__block promo-order__block--full js-note-block">
                            <div class="promo-order__comment">
                                <textarea
                                        name="ORDER_PROP_13"
                                        class="textarea"
                                        placeholder="Текст записки"
                                ></textarea>
                            </div>
                        </div>

                        <div class="promo-order__block promo-order__block--full">
                            <div class="promo-order__comment">
                                <textarea
                                        name="COMMENT"
                                        class="textarea"
                                        placeholder="Комментарий к заказу"
                                        maxlength="400"
                                ></textarea>
                            </div>
                        </div>
                        <div class="promo-order__block promo-order__block--full">
                            <div class="styled-checkbox__block">
                                <input
                                        class="styled-checkbox"
                                        id="ORDER_PROP_10"
                                        type="checkbox"
                                        name="ORDER_PROP_10"
                                        value="Y"
                                >
                                <label for="ORDER_PROP_10">Это сюрприз</label>
                                <span
                                        class="promo-tooltip promo-tooltip--question"
                                        title="Цветы в подарок"
                                        data-toggle="tooltip"
                                        data-placement="bottom"
                                >
                                    <svg class="" width="23px" height="23px">
                                        <use xlink:href="<?=SITE_TEMPLATE_PATH?>/icons/icons.svg?v=<?=VERSION_SPRITE__ICONS?>#question"></use>
                                    </svg>
                                </span>
                            </div>
                            <div class="styled-checkbox__block">
                                <input
                                        class="styled-checkbox"
                                        id="ORDER_PROP_12"
                                        type="checkbox"
                                        name="ORDER_PROP_12"
                                        value="Y"
                                >
                                <label for="ORDER_PROP_12">Добавить записку</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="order-form__cta">
                    <button class="btn order-form__submit js-order-button">
                        Оплатить
                        <?php if ($orderPrice !== $oldOrderPrice): ?>
                            <span class="promo-order__submit__old-price">
                                <span><?=$oldOrderPriceFormat?></span>
                                <span class="rouble"></span>
                            </span>
                        <?php endif; ?>
                        <span class="js-order-sum"><?=$orderPriceFormat?></span>
                        <span class="rouble"></span>
                    </button>
                </div>
            </div>

            <div class="order-form__aside">
                <div class="order-cart">
                    <div class="js-order-cart">
                        <?php foreach ($arResult['BASKET_ITEMS'] as $item): ?>
                            <div class="order-list">
                                <div class="order-list__list">
                                    <div class="order-list__item order-item">
                                        <div class="order-item__image">
                                            <img src="<?=$item['image']?>" alt="">
                                        </div>
                                        <div class="order-item__content">
                                            <div class="order-item__title">
                                                <?=$item['name']?>
                                                <?php if (!empty($item['cover'])): ?>
                                                    <span class="">(Упаковка: <?=$item['cover']?>)</span>
                                                <?php endif; ?>
                                                <?php if (!empty($item['subscribeParams'])): ?>
                                                    <span class="">
                                                        (<?=$item['subscribeParams']['type']?>
                                                        ,
                                                        <?=$item['subscribeParams']['delivery']?>)
                                                    </span>
                                                <?php endif; ?>
                                            </div>
                                            <?php if ($item['sum'] > 0): ?>
                                                <div class="order-item__price">
                                                    <?php if ($item['sum'] !== $item['oldSum']): ?>
                                                        <span class="promo-order__submit__old-price">
                                                            <span><?=$item['oldSumFormat']?></span>
                                                            <span class="rouble"></span>
                                                        </span>
                                                    <?php endif; ?>
                                                    <span><?=$item['sumFormat']?></span>
                                                    <span class="rouble"></span>
                                                </div>
                                            <?php endif; ?>
                                            <?php if (empty($item['subscribeParams']) && !$item['bookmate']): ?>
                                                <div class="order-item__quantity">
                                                    <div class="quantity-control">
                                                        <button
                                                                class="quantity-control__minus js-order-item-minus"
                                                                type="button"
                                                                data-id="<?=$item['id']?>"
                                                                data-productid="<?=$item['productId']?>"
                                                        >
                                                            -
                                                        </button>
                                                        <input
                                                                class="count quantity-control__input"
                                                                type="text"
                                                                name="quantity"
                                                                value="<?=$item['quantity']?>"
                                                                data-id="<?=$item['id']?>"
                                                                data-productid="<?=$item['productId']?>"
                                                            <?=$item['upsale'] ? 'data-max="5"' : ''?>
                                                        >
                                                        <button
                                                                class="quantity-control__plus js-order-item-plus"
                                                                type="button"
                                                                data-id="<?=$item['id']?>"
                                                                data-productid="<?=$item['productId']?>"
                                                        >
                                                            +
                                                        </button>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                            <div class="order-item__remove">
                                                <button
                                                        class="order-item__remove-button js-order-item-delete"
                                                        type="button"
                                                        data-id="<?=$item['id']?>"
                                                        data-productid="<?=$item['productId']?>"
                                                >
                                                    Удалить
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="order-summary">
                        <div class="order-summary__item">
                            <div class="order-summary__label">Товары</div>
                            <div class="order-summary__value">
                                <b class="js-goods-sum">
                                    <?php if ($goodsPrice !== $oldGoodsPrice): ?>
                                        <span class="promo-order__submit__old-price">
                                            <span><?=$oldGoodsPriceFormat?></span>
                                            <span class="rouble"></span>
                                        </span>
                                    <?php endif; ?>
                                    <span><?=$goodsPriceFormat?></span>
                                    <span class="rouble"></span>
                                </b>
                            </div>
                        </div>
                        <div class="order-summary__item">
                            <div class="order-summary__label">Доставка</div>
                            <div class="order-summary__value">
                                <span class="text-muted">Бесплатно</span>
                            </div>
                        </div>
                        <div class="order-summary__item">
                            <div class="order-summary__label">Комплимент</div>
                            <div class="order-summary__value">
                                <b class="js-upsale-sum">
                                    <?php if ($upsalePrice !== $oldUpsalePrice): ?>
                                        <span class="promo-order__submit__old-price">
                                            <span><?=$oldUpsalePriceFormat?></span>
                                            <span class="rouble"></span>
                                        </span>
                                    <?php endif; ?>
                                    <span><?=$upsalePriceFormat?></span>
                                    <span class="rouble"></span>
                                </b>
                            </div>
                        </div>
                        <div class="order-summary__item">
                            <div class="order-summary__label">Промокод</div>
                            <div class="order-summary__value js-promocode">
                                <?php if (empty($arResult['coupon'])): ?>
                                    <button class="order-summary__coupon-link js-order-promocode-apply">
                                        Применить
                                    </button>
                                <?php else: ?>
                                    <div>
                                        <div class="promo-order__coupon__discount">
                                            <span><?=$arResult['coupon']['coupon']?></span>
                                            (
                                            <strong><?=$arResult['coupon']['coupon']?></strong>
                                            )
                                            <div class="promo-order__coupon__discount__cancel js-order-promocode-cancel">
                                                Отменить
                                            </div>
                                        </div>
                                        <div class="promo-order__coupon__sum">
                                            -
                                            <span><?=$discountFormat?></span>
                                            <span class="rouble"></span>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="promo-order__coupon js-order-promocode-form"></div>
                        <div class="order-summary__item order-summary__item--total">
                            <div class="order-summary__label">Итого</div>
                            <div class="order-summary__value js-order-sum">
                                <?php if ($orderPrice !== $oldOrderPrice): ?>
                                    <span class="promo-order__submit__old-price">
                                        <span><?=$oldOrderPriceFormat?></span>
                                        <span class="rouble"></span>
                                    </span>
                                <?php endif; ?>
                                <span><?=$orderPriceFormat?></span>
                                <span class="rouble"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
<?php endif; ?>

<script id="orderCartTmpl" type="text/x-jsrender">
    {{for items}}
        <div class="order-list">
            <div class="order-list__list">
                <div class="order-list__item order-item">
                    <div class="order-item__image">
                        <img src="{{:image}}" alt="">
                    </div>
                    <div class="order-item__content">
                        <div class="order-item__title">
                            {{:name}}
                            {{if cover}}
                                <span class="">(Упаковка: {{:cover}})</span>
                            {{/if}}
                            {{if subscribe}}
                                <span class="">
                                    ({{:subscribeParams.type}}, {{:subscribeParams.delivery}})
                                </span>
                            {{/if}}
                        </div>
                        {{if sum > 0}}
                            <div class="order-item__price">
                                {{if sum !== oldSum}}
                                    <span class="promo-order__submit__old-price">
                                        <span>{{:oldSumFormat}}</span>
                                        <span class="rouble"></span>
                                    </span>
                                {{/if}}
                                <span>{{:sumFormat}}</span> <span class="rouble"></span>
                            </div>
                        {{/if}}
                        {{if !subscribe && !bookmate}}
                            <div class="order-item__quantity">
                                <div class="quantity-control">
                                    <button
                                            class="quantity-control__minus js-order-item-minus"
                                            type="button"
                                            data-id="{{:id}}"
                                            data-productid="{{:productId}}"
                                    >
                                        -
                                    </button>
                                    <input
                                            class="count quantity-control__input"
                                            type="text"
                                            name="quantity"
                                            value="{{:quantity}}"
                                            data-id="{{:id}}"
                                            data-productid="{{:productId}}"
                                            {{if upsale}}data-max="5"{{/if}}
                                    >
                                    <button
                                            class="quantity-control__plus js-order-item-plus"
                                            type="button"
                                            data-id="{{:id}}"
                                            data-productid="{{:productId}}"
                                    >
                                        +
                                    </button>
                                </div>
                            </div>
                        {{/if}}
                        <div class="order-item__remove">
                            <button
                                    class="order-item__remove-button js-order-item-delete"
                                    type="button"
                                    data-id="{{:id}}"
                                    data-productid="{{:productId}}"
                            >
                                Удалить
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    {{/for}}
</script>

<script id="goodsPriceTmpl" type="text/x-jsrender">
    {{if goodsPrice !== oldGoodsPrice}}
        <span class="promo-order__submit__old-price">
            <span>{{:oldGoodsPriceFormat}}</span>
            <span class="rouble"></span>
        </span>
    {{/if}}
    <span>{{:goodsPriceFormat}}</span>
    <span class="rouble"></span>
</script>

<script id="upsalePriceTmpl" type="text/x-jsrender">
    {{if upsalePrice !== oldUpsalePrice}}
        <span class="promo-order__submit__old-price">
            <span>{{:oldUpsalePriceFormat}}</span>
            <span class="rouble"></span>
        </span>
    {{/if}}
    <span>{{:upsalePriceFormat}}</span>
    <span class="rouble"></span>
</script>

<script id="orderPriceTmpl" type="text/x-jsrender">
    {{if sum !== oldSum}}
        <span class="promo-order__submit__old-price">
            <span>{{:oldSumFormat}}</span>
            <span class="rouble"></span>
        </span>
    {{/if}}
    <span>{{:sumFormat}}</span>
    <span class="rouble"></span>
</script>

<script id="orderButtonTmpl" type="text/x-jsrender">
    Оплатить
    {{if sum !== oldSum}}
        <span class="promo-order__submit__old-price">
            <span>{{:oldSumFormat}}</span>
            <span class="rouble"></span>
        </span>
    {{/if}}
    <span>{{:sumFormat}}</span>
    <span class="rouble"></span>
</script>

<script id="orderPromocodeApplyTmpl" type="text/x-jsrender">
    <button class="order-summary__coupon-link js-order-promocode-apply">Применить</button>
</script>

<script id="orderPromocodeTmpl" type="text/x-jsrender">
    <div>
        <div class="promo-order__coupon__discount">
            <span>{{:coupon.coupon}}</span> (<strong>{{:coupon.coupon}}</strong>)
            <div class="promo-order__coupon__discount__cancel js-order-promocode-cancel">Отменить</div>
        </div>
        <div class="promo-order__coupon__sum">
            -<span>{{:discountFormat}}</span> <span class="rouble"></span>
        </div>
    </div>
</script>

<script id="orderPromocodeFormTmpl" type="text/x-jsrender">
    <div class="promo-order__coupon__group">
        <div class="promo-order__coupon__cell promo-order__coupon__cell--input">
            <div class="input__wrapper">
                <input type="text" name="coupon" class="input" placeholder="Введите промокод">
            </div>
            <p class="promo-order__error js-order-promocode-error" style="display: none;"></p>
        </div>
        <div class="promo-order__coupon__cell promo-order__coupon__cell--button">
            <button class="btn btn__main promo-order__coupon__button js-order-promocode-add">
                <span class="btn__text promo-order__coupon__button__text promo-order__coupon__button__text--desktop">
                    Применить
                </span>
                <span class="btn__text promo-order__coupon__button__text promo-order__coupon__button__text--mobile">
                    &#10003;
                </span>
                <span class="btn__loader">
                    <span class="spinner"></span>
                </span>
            </button>
        </div>
    </div>
</script>