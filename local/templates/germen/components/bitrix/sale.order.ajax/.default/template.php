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

$goodsPrice = 0;
$upsalePrice = 0;
foreach ($arResult['BASKET_ITEMS'] as $item) {
    if ($item['upsale']) {
        $hasUpsale = true;

        $upsalePrice += $item['sum'];
    } else {
        $goodsPrice += $item['sum'];
    }

    if ($item['bookmate']) {
        $hasBookmate = true;
    }
}

$orderPriceFormat = number_format($orderPrice, 0, '', ' ');
$goodsPriceFormat = number_format($goodsPrice, 0, '', ' ');
$upsalePriceFormat = number_format($upsalePrice, 0, '', ' ');
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
        <form class="order-form" id="form-order" name="orderForm" action="" method="post">
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
                    <input class="input" type="hidden" name="ORDER_PROP_1" value="1">

                    <div class="order-form__subtitle">Адрес доставки</div>
                    <div class="promo-order__row">
                        <div class="promo-order__block">
                            <div class="input__wrapper input__wrapper--mark">
                                <input
                                        class="input"
                                        type="text"
                                        name="ORDER_PROP_2"
                                        value=""
                                        placeholder="Улица и дом"
                                >
                                <div class="list_street">
                                    <ul id="search-street"></ul>
                                </div>
                            </div>
                        </div>
                        <div class="promo-order__block">
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
                                        checked
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

                        <div class="promo-order__block promo-order__block--full" style="width: 100%;">
                            <div class="order-form__map" id="delivery-map"></div>
                        </div>
                    </div>
                </div>

                <div class="order-form__section">
                    <div class="order-form__subtitle">Когда доставить</div>
                    <div class="promo-order__row">
                        <div class="promo-order__block promo-order__block--full">
                            <input
                                    class="input order-datetime"
                                    id="ORDER_PROP_1_1"
                                    type="text"
                                    name="ORDER_PROP_1[1]"
                                    value=""
                                    placeholder="Когда доставить"
                                    autocomplete="off"
                                    data-time=""
                                    data-maxtime=""
                                    data-mintime=""
                            >
                        </div>
                    </div>
                </div>

                <div class="order-form__section">
                    <div class="order-form__subtitle">Ваш телефон</div>
                    <div class="promo-order__row">
                        <div class="promo-order__block">
                            <div class="input__wrapper input__wrapper--mark">
                                <input
                                        class="input js-phone-1"
                                        id="ORDER_PROP_5"
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
                                        checked
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
                                        checked
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
                                        checked
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
                                        id="ORDER_PROP_19"
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
                                        id="ORDER_PROP_8"
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
                                        id="ORDER_PROP_9"
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
                                        checked
                                >
                                <label for="ORDER_PROP_14">Придумайте вместо меня!</label>
                            </div>
                        </div>
                        <div class="promo-order__block promo-order__block--full">
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
                                        checked
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
                                        checked
                                >
                                <label for="ORDER_PROP_12">Добавить записку</label>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="order-form__cta">
                    <button class="btn order-form__submit">
                        Оплатить
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
                                <b>
                                    <span class="js-goods-sum"><?=$goodsPriceFormat?></span>
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
                                <b>
                                    <span class="js-upsale-sum"><?=$upsalePriceFormat?></span>
                                    <span class="rouble"></span>
                                </b>
                            </div>
                        </div>

                        <div class="order-summary__item">
                            <div class="order-summary__label">Промокод</div>
                            <div class="order-summary__value">
                                <button class="order-summary__coupon-link">
                                    Применить
                                </button>
                                <div>
                                    <div class="promo-order__coupon__discount">
                                        <span>coupon-name</span>
                                        (
                                        <strong>coupon-value</strong>
                                        )
                                        <div class="promo-order__coupon__discount__cancel">Отменить</div>
                                    </div>
                                    <div class="promo-order__coupon__sum">
                                        -
                                        <span></span>
                                        <span class="rouble"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="promo-order__coupon">
                            <div class="promo-order__coupon__group">
                                <div class="promo-order__coupon__cell promo-order__coupon__cell--input">
                                    <div class="input__wrapper">
                                        <input type="text" class="input" placeholder="Введите промокод">
                                    </div>
                                    <p class="promo-order__error">Промо-код не найден :(</p>
                                </div>
                                <div class="promo-order__coupon__cell promo-order__coupon__cell--button">
                                    <button class="btn btn__main promo-order__coupon__button">
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
                        </div>

                        <div class="order-summary__item order-summary__item--total">
                            <div class="order-summary__label">Итого</div>
                            <div class="order-summary__value">
                                <span class="js-order-sum"><?=$orderPriceFormat?></span>
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
