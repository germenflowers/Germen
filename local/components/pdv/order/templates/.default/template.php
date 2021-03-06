<?php

/**
 * @global CMain $APPLICATION
 * @var array $arResult
 * @var array $arParams
 */

use Germen\Price;
use Germen\Content;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$this->setFrameMode(true);

$price = new Price();
$userGroups = $price->getUserGroups();
$price->setPricesIdByName(array('BASE'));
$pricesId = $price->getPricesId();
$prices = $price->getItemPrices((int)$arResult['PRODUCT']['ID'], 1, $pricesId, $userGroups);

$price = (int)$prices['PRICES'][0];
$priceFormatted = number_format($price, 0, '', ' ');

$informationBanner = Content::getInformationBannerCached();

$upsaleProducts = array();
foreach ($arResult['UPSALE_PRODUCTS'] as $item) {
    foreach ($arResult['ORDER_UPSALE_PRODUCTS'] as $id => $quantity) {
        if((int)$item['ID'] === (int)$id) {
            $upsaleProducts[] = $item;
        }
    }
}

$jsUpsaleProducts = empty($arResult['ORDER_UPSALE_PRODUCTS']) ? '{}' : json_encode($arResult['ORDER_UPSALE_PRODUCTS']);
?>
<?php if (!$arResult['CONFIRM']): ?>
    <header class="order-header">
        <div class="order-header__logo">
            <a href="/" class="logo"></a>
        </div>
    </header>
    <div class="order-content">
        <form class="order-form" id="form-order" action="<?=$APPLICATION->GetCurPageParam(false)?>" method="post">
            <input
                    type="hidden"
                    class="js-upsale-products"
                    name="upsale_products"
                    value='<?=$jsUpsaleProducts?>'
            >
            <input
                    type="hidden"
                    class="js-main-products"
                    name="main_products"
                    value='<?=json_encode([$arResult['PRODUCT']['ID'] => $arResult['PRODUCT']['QUANTITY']])?>'
            >
            <input
                    type="hidden"
                    class="js-discount-percent"
                    name="discount_percent"
                    value="<?=(int)$arResult['COUPON']['PERCENT']?>"
            >

            <?php if (!empty($arResult['PRODUCT']['type'])): ?>
                <?php
                $prop = '';
                foreach ($arResult['ORDER_PROPS'] as $orderProperty) {
                    if ($orderProperty['CODE'] === 'TYPE_BOUQUET') {
                        $prop = $orderProperty;
                        break;
                    }
                }
                ?>
                <input type="hidden" name="ORDER_PROP_<?=$prop['ID']?>" value="<?=$arResult['PRODUCT']['type']?>">
            <?php endif; ?>

            <?php if (!empty($arResult['PRODUCT']['delivery'])): ?>
                <?php
                $prop = '';
                foreach ($arResult['ORDER_PROPS'] as $orderProperty) {
                    if ($orderProperty['CODE'] === 'TYPE_BOUQUET_2') {
                        $prop = $orderProperty;
                        break;
                    }
                }
                ?>
                <input type="hidden" name="ORDER_PROP_<?=$prop['ID']?>" value="<?=$arResult['PRODUCT']['delivery']?>">
            <?php endif; ?>

            <div class="order-form__main">
                <div class="order-form__title">Оформление заказа</div>

                <?php foreach ($arResult['ERROR'] as $error): ?>
                    <div class="order-error"><?=$error?></div>
                <?php endforeach; ?>

                <?php if (!empty($arResult["UPSALE_PRODUCTS"]) || !empty($arResult["BOOKMATE_PRODUCTS"])): ?>
                    <div class="order-form__section js-upsale-products-block">
                        <?php if (!empty($arResult["UPSALE_PRODUCTS"])): ?>
                            <div class="order-form__subtitle order-form__subtitle--offers">Комплименты</div>
                            <div class="order-offers js-order-offers-slider">
                                <div class="order-offers__list">
                                    <?php foreach ($arResult["UPSALE_PRODUCTS"] as $upSaleProduct): ?>
                                        <div
                                                class="order-offers__item js-upsale-block"
                                                data-id="<?=$upSaleProduct["ID"]?>"
                                                data-price="<?=$upSaleProduct["PRICE"]?>"
                                        >
                                            <div class="order-offer">
                                                <button
                                                        class="order-offer__add js-add-basket"
                                                        type="button"
                                                        aria-label="Добавить к заказу"
                                                >
                                                    +
                                                </button>
                                                <div class="order-offer__image js-image-block">
                                                    <img
                                                            src="<?=$upSaleProduct["NOT_RETINA_PICTURE"]["src"]?>"
                                                            srcset="<?=$upSaleProduct["NOT_RETINA_PICTURE"]["src"]?> 1x, <?=$upSaleProduct["RETINA_PICTURE"]["src"]?> 2x"
                                                            alt="<?=$upSaleProduct["NAME"]?>"
                                                    />
                                                </div>
                                                <div class="order-offer__title js-name"><?=$upSaleProduct["NAME"]?></div>
                                                <div class="order-offer__price">
                                                    +
                                                    <span class="js-price"><?=$upSaleProduct["PRICE"]?></span>&nbsp;<span class="rouble"></span>
                                                </div>
                                                <div class="order-offer__text"><?=$upSaleProduct["PREVIEW_TEXT"]?></div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <button class="swiper-button-next"></button>
                                <button class="swiper-button-prev"></button>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($arResult["BOOKMATE_PRODUCTS"])): ?>
                            <a class="order-bookmate js-order-bookmate" href="#" title="Добавить бесплатную книгу">
                                <span class="order-bookmate__title">Добавить бесплатную книгу</span>
                                <span class="order-bookmate__text">
                                    Спецпроект с
                                    <span class="order-bookmate__logo">
                                        <span class="sr-only">Bookmate</span>
                                    </span>
                                </span>
                            </a>
                            <div class="order-offers js-order-offers-slider js-order-bookmate-offers hidden">
                                <div class="order-offers__list">
                                    <?php foreach ($arResult["BOOKMATE_PRODUCTS"] as $bookMateProduct): ?>
                                        <div
                                                class="order-offers__item js-bookmate-block"
                                                data-id="<?=$bookMateProduct["ID"]?>"
                                                data-price="<?=$bookMateProduct["PRICE"]?>"
                                        >
                                            <div class="order-offer">
                                                <button
                                                        class="order-offer__add js-add-basket"
                                                        type="button"
                                                        aria-label="Добавить к заказу"
                                                >
                                                    +
                                                </button>
                                                <div class="order-offer__image js-image-block">
                                                    <img
                                                            src="<?=$bookMateProduct["NOT_RETINA_PICTURE"]["src"]?>"
                                                            srcset="<?=$bookMateProduct["NOT_RETINA_PICTURE"]["src"]?> 1x, <?=$bookMateProduct["RETINA_PICTURE"]["src"]?> 2x"
                                                            alt="<?=$upSaleProduct["NAME"]?>"
                                                    />
                                                </div>
                                                <div class="order-offer__title js-name"><?=$bookMateProduct["NAME"]?></div>
                                                <div class="order-offer__text"><?=$bookMateProduct["PREVIEW_TEXT"]?></div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                                <button class="swiper-button-next"></button>
                                <button class="swiper-button-prev"></button>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <div class="order-form__section">
                    <div class="order-form__subtitle">Адрес доставки</div>

                    <?php
                    $prop = $arResult['ORDER_PROPS'][1];
                    ?>
                    <input
                            type="hidden"
                            class="input"
                            name="ORDER_PROP_<?=$prop['ID']?>"
                            value="<?=$prop['VALUE']?>"
                    >

                    <div class="promo-order__row">
                        <?php
                        $prop = $arResult['ORDER_PROPS'][2];
                        ?>
                        <div class="promo-order__block js-address-block">
                            <div class="input__wrapper input__wrapper--mark">
                                <input
                                        id="ORDER_PROP_<?=$prop['ID']?>"
                                        type="text"
                                        class="input"
                                        name="ORDER_PROP_<?=$prop['ID']?>"
                                        placeholder="<?=$prop['NAME']?>"
                                        value="<?=$prop['VALUE']?>"
                                >
                                <div class="list_street">
                                    <ul id="search-street"></ul>
                                </div>
                            </div>
                        </div>

                        <?php
                        $prop = $arResult['ORDER_PROPS'][3];
                        ?>
                        <div class="promo-order__block js-address-block">
                            <div class="input__wrapper">
                                <input
                                        type="text"
                                        class="input"
                                        name="ORDER_PROP_<?=$prop['ID']?>"
                                        placeholder="<?=$prop['NAME']?>"
                                        value="<?=$prop['VALUE']?>"
                                >
                            </div>
                        </div>

                        <?php
                        $prop = $arResult['ORDER_PROPS'][15];
                        ?>
                        <div class="promo-order__block promo-order__block--full">
                            <div class="styled-checkbox__block u-mb-0">
                                <input
                                        class="styled-checkbox"
                                        name="ORDER_PROP_<?=$prop['ID']?>"
                                        id="ORDER_PROP_<?=$prop['ID']?>"
                                        type="checkbox"
                                        value="Y"
                                    <?=empty($prop['VALUE']) ? '' : 'checked'?>
                                >
                                <label for="ORDER_PROP_<?=$prop['ID']?>"><?=$prop['NAME']?></label>

                                <?php if (!empty($prop['DESCRIPTION'])): ?>
                                    <span
                                            class="promo-tooltip promo-tooltip--question"
                                            data-toggle="tooltip"
                                            data-placement="bottom"
                                            title="<?=$prop['DESCRIPTION']?>"
                                    >
                                        <svg class="" width="23px" height="23px">
                                            <use xlink:href="<?=SITE_TEMPLATE_PATH?>/icons/icons.svg?v=1.2#question"></use>
                                        </svg>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="promo-order__block promo-order__block--full js-address-block" style="width: 100%;">
                            <div id="delivery-map" class="order-form__map"></div>
                        </div>
                    </div>
                </div>

                <div class="order-form__section">
                    <?php
                    $prop = $arResult['ORDER_PROPS'][4];
                    ?>
                    <div class="order-form__subtitle">Когда доставить</div>
                    <div class="promo-order__row">
                        <?php for ($i = 1; $i <= $arResult['COUNT_DATE_DELIVERY']; $i++): ?>
                            <div class="promo-order__block promo-order__block--full">
                                <input
                                        type="text"
                                        class="input order-datetime"
                                        autocomplete="off"
                                        name="ORDER_PROP_<?=$prop['ID']?>[<?=$i - 1?>]"
                                        id="ORDER_PROP_<?=$prop['ID']?>_<?=$i - 1?>"
                                        placeholder="<?=$prop['NAME']?>" value="<?=$prop['VALUE'][$i - 1]?>"
                                        data-time="<?=$arResult['DELIVERY_TIME']?>"
                                        <?php if (!empty($arResult['PRODUCT']['ACTIVE_END_DATE']['VALUE'])): ?>
                                            data-maxtime="<?=strtotime($arResult['PRODUCT']['ACTIVE_END_DATE']['VALUE'])?>"
                                        <?php endif; ?>
                                        <?php if (!empty($informationBanner)): ?>
                                            data-mintime="<?=strtotime(date('d.m.Y').' 23:59')?>"
                                        <?php endif; ?>
                                >
                            </div>
                        <?php endfor; ?>
                    </div>
                </div>

                <div class="order-form__section">
                    <div class="order-form__subtitle">Ваш телефон</div>
                    <?php
                    $prop = $arResult['ORDER_PROPS'][5];
                    ?>
                    <div class="promo-order__row">
                        <div class="promo-order__block">
                            <div class="input__wrapper input__wrapper--mark">
                                <input
                                        type="text"
                                        name="ORDER_PROP_<?=$prop['ID']?>"
                                        value="<?=$prop['VALUE']?>"
                                        id="ORDER_PROP_<?=$prop['ID']?>"
                                        class="input js-phone-1"
                                >
                                <input type="checkbox" id="phone-mask-1" checked style="display:none;">
                            </div>
                        </div>

                        <div class="promo-order__block promo-order__block--full">
                            <?php
                            $prop = $arResult['ORDER_PROPS'][6];
                            ?>
                            <div class="styled-checkbox__block styled-checkbox__block--full">
                                <input
                                        class="styled-checkbox"
                                        name="ORDER_PROP_<?=$prop['ID']?>"
                                        id="ORDER_PROP_<?=$prop['ID']?>"
                                        type="checkbox"
                                        value="Y"
                                    <?=empty($prop['VALUE'])?'':'checked'?>
                                >
                                <label for="ORDER_PROP_<?=$prop['ID']?>"><?=$prop['NAME']?></label>
                            </div>

                            <?php
                            $prop = $arResult['ORDER_PROPS'][7];
                            ?>
                            <div class="styled-checkbox__block styled-checkbox__block--full">
                                <input
                                        class="styled-checkbox"
                                        name="ORDER_PROP_<?=$prop['ID']?>"
                                        id="ORDER_PROP_<?=$prop['ID']?>"
                                        type="checkbox"
                                        value="Y"
                                    <?=empty($prop['VALUE'])?'':'checked'?>
                                >
                                <label for="ORDER_PROP_<?=$prop['ID']?>"><?=$prop['NAME']?></label>
                            </div>

                            <?php
                            $prop = $arResult['ORDER_PROPS'][18];
                            ?>
                            <div class="styled-checkbox__block styled-checkbox__block--full u-mb-0">
                                <input
                                        class="styled-checkbox"
                                        name="ORDER_PROP_<?=$prop['ID']?>"
                                        id="ORDER_PROP_<?=$prop['ID']?>"
                                        type="checkbox"
                                        value="Y"
                                    <?=empty($prop['VALUE'])?'':'checked'?>
                                >
                                <label for="ORDER_PROP_<?=$prop['ID']?>"><?=$prop['NAME']?></label>
                                <?php if (!empty($prop['DESCRIPTION'])): ?>
                                    <span
                                            class="promo-tooltip promo-tooltip--question"
                                            data-toggle="tooltip"
                                            data-placement="bottom"
                                            title="<?=$prop['DESCRIPTION']?>"
                                    >
                                        <svg class="" width="23px" height="23px">
                                            <use xlink:href="<?=SITE_TEMPLATE_PATH?>/icons/icons.svg?v=1.1#question"></use>
                                        </svg>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <?php
                foreach ($arResult['ORDER_PROPS'] as $property) {
                    if($property['CODE'] === 'EMAIL') {
                        $prop = $property;
                    }
                }
                ?>
                <?php if (!empty($prop)): ?>
                    <div class="order-form__section">
                        <div class="order-form__subtitle">Отправить электронный чек на E-mail</div>
                        <div class="promo-order__row">
                            <div class="promo-order__block">
                                <div class="input__wrapper input__wrapper--mark">
                                    <input
                                            type="email"
                                            class="input"
                                            name="ORDER_PROP_<?=$prop['ID']?>"
                                            id="ORDER_PROP_<?=$prop['ID']?>" value="<?=$prop['VALUE']?>"
                                            placeholder="E-mail"
                                    >
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="order-form__section">
                    <div class="order-form__subtitle">Данные получателя</div>

                    <div class="promo-order__row">
                        <?php
                        $prop = $arResult['ORDER_PROPS'][8];
                        ?>
                        <div class="promo-order__block">
                            <div class="input__wrapper input__wrapper--mark">
                                <input
                                        type="text"
                                        name="ORDER_PROP_<?=$prop['ID']?>"
                                        value="<?=$prop['VALUE']?>"
                                        id="ORDER_PROP_<?=$prop['ID']?>"
                                        class="input js-phone-2"
                                >
                                <input type="checkbox" id="phone-mask-2" checked style="display:none;">
                            </div>
                        </div>

                        <?php
                        $prop = $arResult['ORDER_PROPS'][9];
                        ?>
                        <div class="promo-order__block">
                            <div class="input__wrapper input__wrapper--mark">
                                <input
                                        type="text"
                                        class="input"
                                        id="ORDER_PROP_<?=$prop['ID']?>"
                                        name="ORDER_PROP_<?=$prop['ID']?>"
                                        value="<?=$prop['VALUE']?>"
                                        placeholder="Имя"
                                >
                            </div>
                        </div>

                        <?php
                        $prop = $arResult['ORDER_PROPS'][14];
                        ?>
                        <div class="promo-order__block promo-order__block--full hidden" id="write_comment">
                            <div class="styled-checkbox__block">
                                <input
                                        class="styled-checkbox"
                                        name="ORDER_PROP_<?=$prop['ID']?>"
                                        id="ORDER_PROP_<?=$prop['ID']?>"
                                        type="checkbox"
                                        value="Y"
                                    <?=empty($prop['VALUE']) ? '' : 'checked'?>
                                >
                                <label for="ORDER_PROP_<?=$prop['ID']?>"><?=$prop['NAME']?></label>
                                <?php if (!empty($prop['DESCRIPTION'])): ?>
                                    <span
                                            class="promo-tooltip promo-tooltip--question"
                                            data-toggle="tooltip"
                                            data-placement="bottom"
                                            title="<?=$prop['DESCRIPTION']?>"
                                    >
                                        <svg class="" width="23px" height="23px">
                                            <use xlink:href="<?=SITE_TEMPLATE_PATH?>/icons/icons.svg?v=<?=VERSION_SPRITE__ICONS?>#question"></use>
                                        </svg>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>

                        <?php
                        $prop = $arResult['ORDER_PROPS'][13];
                        ?>
                        <div class="promo-order__block promo-order__block--full" id="note-wrap">
                            <div class="promo-order__comment">
                                <textarea
                                        name="ORDER_PROP_<?=$prop['ID']?>"
                                        class="textarea"
                                        placeholder="Текст записки"
                                ><?=$prop['VALUE']?></textarea>
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
                            <?php
                            $prop = $arResult['ORDER_PROPS'][10];
                            ?>
                            <div class="styled-checkbox__block">
                                <input
                                        class="styled-checkbox"
                                        name="ORDER_PROP_<?=$prop['ID']?>"
                                        id="ORDER_PROP_<?=$prop['ID']?>"
                                        type="checkbox"
                                        value="Y"
                                    <?=empty($prop['VALUE']) ? '' : 'checked'?>
                                >
                                <label for="ORDER_PROP_<?=$prop['ID']?>"><?=$prop['NAME']?></label>
                                <?php if (!empty($prop['DESCRIPTION'])): ?>
                                    <span
                                            class="promo-tooltip promo-tooltip--question"
                                            data-toggle="tooltip"
                                            data-placement="bottom"
                                            title="<?=$prop['DESCRIPTION']?>"
                                    >
                                        <svg class="" width="23px" height="23px">
                                            <use xlink:href="<?=SITE_TEMPLATE_PATH?>/icons/icons.svg?v=<?=VERSION_SPRITE__ICONS?>#question"></use>
                                        </svg>
                                    </span>
                                <?php endif; ?>
                            </div>

                            <?php
                            $prop = $arResult['ORDER_PROPS'][12];
                            ?>
                            <div class="styled-checkbox__block">
                                <input
                                        class="styled-checkbox"
                                        name="ORDER_PROP_<?=$prop['ID']?>"
                                        id="ORDER_PROP_<?=$prop['ID']?>"
                                        type="checkbox"
                                        value="Y"
                                    <?=empty($prop['VALUE']) ? '' : 'checked'?>
                                >
                                <label for="ORDER_PROP_<?=$prop['ID']?>"><?=$prop['NAME']?></label>
                                <?php if (!empty($prop['DESCRIPTION'])) : ?>
                                    <span class="promo-tooltip promo-tooltip--question"
                                          data-toggle="tooltip"
                                          data-placement="bottom"
                                          title="<?=$prop['DESCRIPTION']?>"
                                    >
                                        <svg class="" width="23px" height="23px">
                                            <use xlink:href="<?=SITE_TEMPLATE_PATH?>/icons/icons.svg?v=<?=VERSION_SPRITE__ICONS?>#question"></use>
                                        </svg>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="order-form__cta">
                    <button class="btn order-form__submit">
                        Оплатить
                        <span class="js-order_total js-total-sum">
                            <?=$priceFormatted?>
                        </span>
                        <span class="rouble"></span>
                    </button>
                </div>

                <input type="hidden" name="order_send" value="Y">
            </div>

            <div class="order-form__aside">
                <div class="order-cart">
                    <div class="order-list js-basket-item js-basket-item-base" style="display: none">
                        <div class="order-list__list">
                            <div
                                    class="order-list__item order-item js-detail js-basket-item-info"
                                    data-id=""
                                    data-price=""
                            >
                                <div class="order-item__image js-image-block">
                                    <img src="" alt="">
                                </div>
                                <div class="order-item__content">
                                    <div class="order-item__title js-name"></div>
                                    <div class="order-item__price js-price-block">
                                        <span class="js-price"></span>&nbsp;<span class="rouble"></span>
                                    </div>
                                    <div class="order-item__quantity js-quantity-control">
                                        <div class="quantity-control">
                                            <button class="quantity-control__minus js-minus" type="button">-</button>
                                            <input
                                                    name="quantity"
                                                    class="quantity-control__input js-quantity-field"
                                                    value="1"
                                            />
                                            <button class="quantity-control__plus js-plus" type="button">+</button>
                                        </div>
                                    </div>
                                    <div class="order-item__remove">
                                        <button class="order-item__remove-button js-remove-basket-item" type="button">
                                            Удалить
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="order-list js-basket-item">
                        <div class="order-list__list">
                            <div
                                    class="order-list__item order-item js-detail js-basket-item-info js-main-basket-item"
                                    data-id="<?=$arResult['PRODUCT']['ID']?>"
                                    data-price="<?=$price?>"
                            >
                                <?php if (!empty($arResult['PRODUCT']['PICTURE'])): ?>
                                    <div class="order-item__image">
                                        <img
                                                src="<?=$arResult['PRODUCT']['PICTURE']?>"
                                                alt="<?=$arResult['PRODUCT']['NAME']?>"
                                        >
                                    </div>
                                <?php endif; ?>
                                <div class="order-item__content">
                                    <div class="order-item__title"><?=$arResult['PRODUCT']['NAME']?></div>
                                    <div class="order-item__price">
                                        <span class="js-price">
                                            <?=$priceFormatted?>
                                        </span>
                                        <span class="rouble"></span>
                                    </div>
                                    <div class="order-item__quantity js-quantity-control">
                                        <div class="quantity-control">
                                            <button class="quantity-control__minus js-minus" type="button">-</button>
                                            <input
                                                    name="quantity"
                                                    class="quantity-control__input js-quantity-field"
                                                    value="<?=$arResult['PRODUCT']['QUANTITY']?>"
                                            />
                                            <button class="quantity-control__plus js-plus" type="button">+</button>
                                        </div>
                                    </div>
                                    <div class="order-item__remove">
                                        <button class="order-item__remove-button js-remove-basket-item" type="button">
                                            Удалить
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php foreach($upsaleProducts as $item): ?>
                        <div class="order-list js-basket-item">
                            <div class="order-list__list">
                                <div
                                        class="order-list__item order-item js-basket-item-info"
                                        data-id="<?=$item['ID']?>"
                                        data-price="<?=$item['PRICE']?>"
                                >
                                    <div class="order-item__image js-image-block">
                                        <img
                                                src="<?=$item['RETINA_PICTURE']['src']?>"
                                                alt="<?=$item['NAME']?>"
                                        >
                                    </div>
                                    <div class="order-item__content">
                                        <div class="order-item__title js-name"><?=$item['NAME']?></div>
                                        <div class="order-item__price js-price-block">
                                            <span class="js-price">
                                                <?=number_format($item['PRICE'], 0, '', ' ')?>
                                            </span>
                                            &nbsp;
                                            <span class="rouble"></span>
                                        </div>
                                        <div class="order-item__quantity js-quantity-control">
                                            <div class="quantity-control">
                                                <button class="quantity-control__minus js-minus" type="button">-</button>
                                                <input name="quantity" class="quantity-control__input js-quantity-field" value="1">
                                                <button class="quantity-control__plus js-plus" type="button">+</button>
                                            </div>
                                        </div>
                                        <div class="order-item__remove">
                                            <button class="order-item__remove-button js-remove-basket-item" type="button">
                                                Удалить
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>

                    <div class="order-summary">
                        <div class="order-summary__item">
                            <div class="order-summary__label">Товары</div>
                            <div class="order-summary__value">
                                <b>
                                    <span class="js-main-sum"><?=$priceFormatted?></span>
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
                                    <span class="js-upsale-sum">0</span>
                                    <span class="rouble"></span>
                                </b>
                            </div>
                        </div>
                        <div class="order-summary__item">
                            <div class="order-summary__label">Промокод</div>
                            <div class="order-summary__value">
                                <button
                                        class="order-summary__coupon-link js-show-coupon-form"
                                    <?=!empty($arResult['COUPON']) && !$arResult['COUPON']["STATUS_ENTER"]["BAD"] ? 'style="display: none"' : ''?>
                                >
                                    Применить
                                </button>
                                <div
                                        class="js-coupon-applied"
                                    <?=empty($arResult['COUPON']) || $arResult['COUPON']["STATUS_ENTER"]["BAD"] ? 'style="display: none"' : ''?>
                                >
                                    <div class="promo-order__coupon__discount">
                                        <span class="js-coupon-name">
                                            <?=!$arResult['COUPON']["STATUS_ENTER"]["BAD"] ? $arResult['COUPON']["DISCOUNT_NAME"] : ""?>
                                        </span>
                                        (
                                        <strong class="js-coupon-value">
                                            <?=!$arResult['COUPON']["STATUS_ENTER"]["BAD"] ? $arResult['COUPON']["COUPON"] : ""?>
                                        </strong>
                                        )
                                        <div class="promo-order__coupon__discount__cancel js-coupon-cancel">Отменить
                                        </div>
                                    </div>
                                    <div class="promo-order__coupon__sum">-
                                        <span class="js-discount-diff"></span>
                                        <span class="rouble"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="promo-order__coupon js-coupon-form" style="display: none;">
                            <div class="promo-order__coupon__group">
                                <div class="promo-order__coupon__cell promo-order__coupon__cell--input">
                                    <div class="input__wrapper">
                                        <input type="text" class="input js-coupon-field" placeholder="Введите промокод">
                                    </div>
                                    <p class="promo-order__error js-coupon-error" style="display: none">
                                        Промо-код не найден :(
                                    </p>
                                </div>
                                <div class="promo-order__coupon__cell promo-order__coupon__cell--button">
                                    <button class="btn btn__main promo-order__coupon__button js-coupon-apply">
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
                                <span class="js-total-sum"><?=$priceFormatted?></span>
                                <span class="rouble"></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
<?php else: ?>
    <div
            class="modal fade"
            id="popup-flowers"
            tabindex="-1"
            role="dialog"
            aria-labelledby="myModalLabel"
            aria-hidden="true"
    >
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="popup-promo">
                    <div class="popup-promo__header">
                        <div class="logo"></div>
                    </div>
                    <div class="popup-promo__close mobile-menu active" data-dismiss="modal" aria-hidden="true">
                        <span></span>
                    </div>
                    <div class="popup-promo__greate">
                        <div class="popup-promo__greate__logo">
                            <a href="/" target="_blank">
                                <img src="<?=SITE_TEMPLATE_PATH?>/img/logo2.png" alt="">
                            </a>
                        </div>
                        <div class="popup-promo__greate__description">
                            <?php if ($arResult['ORDER']['ID'] > 0): ?>
                                <div class="head-h2">Спасибо! Ваш заказ №<?=$arResult['ORDER']['ID']?> принят.</div>
                                <p>Сейчас будет переход к оплате...</p>
                                <p style="font-size: 13px; padding-top: 25px;">
                                    Если этого не произошло, пожалуйста, нажмите на кнопку ниже:
                                    <?php if ($arResult["ORDER"]["IS_ALLOW_PAY"] === 'Y' && !empty($arResult["PAYMENT"])): ?>
                                        <?php foreach ($arResult["PAYMENT"] as $payment): ?>
                                            <?php if (
                                                $payment["PAID"] !== 'Y' &&
                                                !empty($arResult['PAY_SYSTEM_LIST']) &&
                                                array_key_exists($payment["PAY_SYSTEM_ID"], $arResult['PAY_SYSTEM_LIST'])
                                            ): ?>
                                                <?php
                                                $arPaySystem = $arResult['PAY_SYSTEM_LIST'][$payment["PAY_SYSTEM_ID"]];
                                                ?>
                                                <?php if (empty($arPaySystem["ERROR"])): ?>
                                                    <?php if (
                                                    $arPaySystem["NEW_WINDOW"] === "Y" &&
                                                    $arPaySystem["IS_CASH"] !== "Y" &&
                                                    strlen($arPaySystem["ACTION_FILE"]) > 0
                                                ): ?>
                                                    <?php
                                                    $orderAccountNumber = urlencode(urlencode($arResult["ORDER"]["ACCOUNT_NUMBER"]));
                                                    $paymentAccountNumber = $payment["ACCOUNT_NUMBER"];
                                                    ?>
                                                    <script>
                                                      window.open('<?=$arParams["PATH_TO_PAYMENT"]?>?ORDER_ID=<?=$orderAccountNumber?>&PAYMENT_ID=<?=$paymentAccountNumber?>');
                                                    </script>
                                                <?php else: ?>
                                                    <?=$arPaySystem["BUFFERED_OUTPUT"]?>
                                                <?php endif ?>

                                                    <script>
                                                      setTimeout(function () {
                                                        window.location.href = document.forms[0].getAttribute(
                                                          'action')
                                                      }, 1000);
                                                    </script>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </p>
                            <?php else: ?>
                                <div class="head-h2">Извините, заказ №<?=(int)$_REQUEST['ORDER_ID']?> не найден.</div>
                                <p></p>
                            <? endif; ?>
                        </div>
                    </div>
                    <br/>
                    <br/>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>