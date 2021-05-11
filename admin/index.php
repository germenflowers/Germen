<?php

/**
 * @global CMain $APPLICATION
 */

require_once $_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php';

$APPLICATION->SetTitle('Заказы');
?>
    <div class="aside">
        <div class="aside__dropdown">
            <select name="orders" id="orders-select">
                <option selected="selected">Новые заказы
                    <span>14</span>
                </option>
                <option>Собранные
                    <span>1</span>
                </option>
                <option>Отмененные
                    <span>9</span>
                </option>
            </select>
        </div>
        <div class="aside__orders-list">
            <div class="aside__order">
                <div class="order order--is-opened snap-content">
                    <div class="order__title">№934013</div>
                    <div class="order__note">
                        <svg width="15" height="22" aria-hidden="true">
                            <use xlink:href="img/sprite.svg#clock"></use>
                        </svg>
                        Собрать как можно скорее
                    </div>
                    <div class="order__state">
                        <div class="order__in-process">
                            <div class="circle-timer" data-time-limit="180"></div>
                        </div>
                    </div>
                </div>
                <div class="order__cancel snap-drawers">
                    <div class="snap-drawer snap-drawer-right" data-target="#deleteModal">Отменить</div>
                </div>
            </div>
            <div class="aside__order">
                <div class="order snap-content">
                    <div class="order__title">№934014</div>
                    <div class="order__note">
                        <svg width="15" height="22" aria-hidden="true">
                            <use xlink:href="img/sprite.svg#clock"></use>
                        </svg>
                        Собрать к 13:04
                    </div>
                    <div class="order__state">
                        <div class="order__in-process">
                            <div class="circle-timer" data-time-limit="3000"></div>
                        </div>
                    </div>
                </div>
                <div class="order__cancel snap-drawers">
                    <div class="snap-drawer snap-drawer-right" data-target="#deleteModal">Отменить</div>
                </div>
            </div>
            <div class="aside__order">
                <div class="order snap-content">
                    <div class="order__title">№934015</div>
                    <div class="order__note">
                        <svg width="15" height="22" aria-hidden="true">
                            <use xlink:href="img/sprite.svg#clock"></use>
                        </svg>
                        Собрать к 12:08
                    </div>
                    <div class="order__state">
                        <div class="order__done">
                            <svg width="17" height="15" aria-hidden="true">
                                <use xlink:href="img/sprite.svg#tick"></use>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="order__cancel snap-drawers">
                    <div class="snap-drawer snap-drawer-right" data-target="#deleteModal">Отменить</div>
                </div>
            </div>
            <div class="aside__order">
                <div class="order snap-content">
                    <div class="order__title">№934016</div>
                    <div class="order__note">
                        <svg width="15" height="22" aria-hidden="true">
                            <use xlink:href="img/sprite.svg#clock"></use>
                        </svg>
                        Собрать к 12:13
                    </div>
                    <div class="order__state">
                        <div class="order__in-process">
                            <div class="circle-timer" data-time-limit="5000"></div>
                        </div>
                    </div>
                </div>
                <div class="order__cancel snap-drawers">
                    <div class="snap-drawer snap-drawer-right" data-target="#deleteModal">Отменить</div>
                </div>
            </div>
            <div class="aside__order">
                <div class="order snap-content">
                    <div class="order__title">№934017</div>
                    <div class="order__note">
                        <svg width="15" height="22" aria-hidden="true">
                            <use xlink:href="img/sprite.svg#clock"></use>
                        </svg>
                        Собрать к 12:21
                    </div>
                    <div class="order__state">
                        <div class="order__done">
                            <svg width="17" height="15" aria-hidden="true">
                                <use xlink:href="img/sprite.svg#tick"></use>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="order__cancel snap-drawers">
                    <div class="snap-drawer snap-drawer-right" data-target="#deleteModal">Отменить</div>
                </div>
            </div>
            <div class="aside__order">
                <div class="order snap-content">
                    <div class="order__title">№934018</div>
                    <div class="order__note">
                        <svg width="15" height="22" aria-hidden="true">
                            <use xlink:href="img/sprite.svg#clock"></use>
                        </svg>
                        Собрать к 12:23
                    </div>
                    <div class="order__state">
                        <div class="order__in-process">
                            <div class="circle-timer" data-time-limit="8000"></div>
                        </div>
                    </div>
                </div>
                <div class="order__cancel snap-drawers">
                    <div class="snap-drawer snap-drawer-right" data-target="#deleteModal">Отменить</div>
                </div>
            </div>
        </div>
    </div>
    <div class="steps">
        <div class="steps__item is-active"> Принять заказ</div>
        <div class="steps__arrow"></div>
        <div class="steps__item">Заказ готов</div>
        <div class="steps__arrow"></div>
        <div class="steps__item">Передать курьеру</div>
    </div>
    <div class="order-cover">
        <div class="order-summary">
            <div class="order-summary__top">
                <div class="order-summary__schedule order-summary__block">
                    <div class="order-summary__label">Срок приготовления</div>
                    <div class="order-summary__data">12:54
                        <span class="gray">/ 07:32</span>
                    </div>
                </div>
                <div class="order-summary__order-number order-summary__block">
                    <div class="order-summary__label">Номер заказа</div>
                    <div class="order-summary__data">#23497</div>
                </div>
                <div class="order-summary__total-price order-summary__block">
                    <div class="order-summary__label">Итого за день</div>
                    <div class="order-summary__data">2 800
                        <span>₽</span>
                    </div>
                </div>
                <div class="order-summary__state order-summary__block">
                    <div class="order-summary__label order-summary__label--green">Новый</div>
                </div>
            </div>
            <div class="order-summary__bottom">
                <div class="order-summary__comment">Погрейте, пожалуйста, круассан и поставьте напитки в
                    капхолдер 🙏
                </div>
                <div class="order-summary__total-price">700
                    <span>₽</span>
                </div>
            </div>
        </div>
        <div class="order-list">
            <div class="order-item">
                <div class="order-item__img">
                    <img src="" alt="">
                </div>
                <div class="order-item__info">
                    <div class="order-item__info-top">
                        <div class="order-item__name">Chàmomile</div>
                        <div class="order-item__ammount">1</div>
                        <div class="order-item__price">300 ₽</div>
                    </div>
                    <div class="order-item__extra">Helper, Доставка</div>
                    <div class="order-item__block">
                        <div class="order-item__block-title">Состав</div>
                        <div class="order-item__block-text">25 пионов сорта ян флеминг</div>
                    </div>
                    <div class="order-item__block">
                        <div class="order-item__block-title">Комментарий</div>
                        <div class="order-item__block-text">Сборка разноуровневая, упаковка светлая</div>
                    </div>
                    <div class="order-item__block">
                        <div class="order-item__block-title">Текст открытки</div>
                        <div class="order-item__block-text">Цветы для моего руководителя, наставника,
                            учителя, примера и доброго человека. С днём рождения Ирина Александровна!!! от
                            Рубена.
                        </div>
                    </div>
                </div>
            </div>
            <div class="order-item">
                <div class="order-item__img">
                    <img src="" alt="">
                </div>
                <div class="order-item__info">
                    <div class="order-item__info-top">
                        <div class="order-item__name">Lola</div>
                        <div class="order-item__ammount">1</div>
                        <div class="order-item__price">400 ₽</div>
                    </div>
                    <div class="order-item__block">
                        <div class="order-item__block-title">Состав</div>
                        <div class="order-item__block-text">25 пионов сорта ян флеминг</div>
                    </div>
                    <div class="order-item__block">
                        <div class="order-item__block-title">Комментарий</div>
                        <div class="order-item__block-text">Сборка разноуровневая, упаковка светлая</div>
                    </div>
                    <div class="order-item__block">
                        <div class="order-item__block-title">Текст открытки</div>
                        <div class="order-item__block-text">Цветы для моего руководителя, наставника,
                            учителя, примера и доброго человека. С днём рождения Ирина Александровна!!! от
                            Рубена.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

<!--    <div class="aside">-->
<!--        <div class="aside__dropdown">-->
<!--            <select name="orders" id="orders-select">-->
<!--                <option selected="selected">Новые заказы-->
<!--                    <span>14</span>-->
<!--                </option>-->
<!--                <option>Собранные-->
<!--                    <span>1</span>-->
<!--                </option>-->
<!--                <option>Отмененные-->
<!--                    <span>9</span>-->
<!--                </option>-->
<!--            </select>-->
<!--        </div>-->
<!--        <div class="aside__orders-list">-->
<!--            <div class="aside__order">-->
<!--                <div class="order order--is-opened snap-content">-->
<!--                    <div class="order__title">№934013</div>-->
<!--                    <div class="order__note">-->
<!--                        <svg width="15" height="22" aria-hidden="true">-->
<!--                            <use xlink:href="img/sprite.svg#clock"></use>-->
<!--                        </svg>-->
<!--                        Собрать как можно скорее-->
<!--                    </div>-->
<!--                    <div class="order__state">-->
<!--                        <div class="order__in-process">-->
<!--                            <div class="circle-timer" data-time-limit="180"></div>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
<!--                <div class="order__cancel snap-drawers">-->
<!--                    <div class="snap-drawer snap-drawer-right" data-target="#deleteModal">Отменить</div>-->
<!--                </div>-->
<!--            </div>-->
<!--            <div class="aside__order">-->
<!--                <div class="order snap-content">-->
<!--                    <div class="order__title">№934014</div>-->
<!--                    <div class="order__note">-->
<!--                        <svg width="15" height="22" aria-hidden="true">-->
<!--                            <use xlink:href="img/sprite.svg#clock"></use>-->
<!--                        </svg>-->
<!--                        Собрать к 13:04-->
<!--                    </div>-->
<!--                    <div class="order__state">-->
<!--                        <div class="order__in-process">-->
<!--                            <div class="circle-timer" data-time-limit="3000"></div>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
<!--                <div class="order__cancel snap-drawers">-->
<!--                    <div class="snap-drawer snap-drawer-right" data-target="#deleteModal">Отменить</div>-->
<!--                </div>-->
<!--            </div>-->
<!--            <div class="aside__order">-->
<!--                <div class="order snap-content">-->
<!--                    <div class="order__title">№934015</div>-->
<!--                    <div class="order__note">-->
<!--                        <svg width="15" height="22" aria-hidden="true">-->
<!--                            <use xlink:href="img/sprite.svg#clock"></use>-->
<!--                        </svg>-->
<!--                        Собрать к 12:08-->
<!--                    </div>-->
<!--                    <div class="order__state">-->
<!--                        <div class="order__done">-->
<!--                            <svg width="17" height="15" aria-hidden="true">-->
<!--                                <use xlink:href="img/sprite.svg#tick"></use>-->
<!--                            </svg>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
<!--                <div class="order__cancel snap-drawers">-->
<!--                    <div class="snap-drawer snap-drawer-right" data-target="#deleteModal">Отменить</div>-->
<!--                </div>-->
<!--            </div>-->
<!--            <div class="aside__order">-->
<!--                <div class="order snap-content">-->
<!--                    <div class="order__title">№934016</div>-->
<!--                    <div class="order__note">-->
<!--                        <svg width="15" height="22" aria-hidden="true">-->
<!--                            <use xlink:href="img/sprite.svg#clock"></use>-->
<!--                        </svg>-->
<!--                        Собрать к 12:13-->
<!--                    </div>-->
<!--                    <div class="order__state">-->
<!--                        <div class="order__in-process">-->
<!--                            <div class="circle-timer" data-time-limit="5000"></div>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
<!--                <div class="order__cancel snap-drawers">-->
<!--                    <div class="snap-drawer snap-drawer-right" data-target="#deleteModal">Отменить</div>-->
<!--                </div>-->
<!--            </div>-->
<!--            <div class="aside__order">-->
<!--                <div class="order snap-content">-->
<!--                    <div class="order__title">№934017</div>-->
<!--                    <div class="order__note">-->
<!--                        <svg width="15" height="22" aria-hidden="true">-->
<!--                            <use xlink:href="img/sprite.svg#clock"></use>-->
<!--                        </svg>-->
<!--                        Собрать к 12:21-->
<!--                    </div>-->
<!--                    <div class="order__state">-->
<!--                        <div class="order__done">-->
<!--                            <svg width="17" height="15" aria-hidden="true">-->
<!--                                <use xlink:href="img/sprite.svg#tick"></use>-->
<!--                            </svg>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
<!--                <div class="order__cancel snap-drawers">-->
<!--                    <div class="snap-drawer snap-drawer-right" data-target="#deleteModal">Отменить</div>-->
<!--                </div>-->
<!--            </div>-->
<!--            <div class="aside__order">-->
<!--                <div class="order snap-content">-->
<!--                    <div class="order__title">№934018</div>-->
<!--                    <div class="order__note">-->
<!--                        <svg width="15" height="22" aria-hidden="true">-->
<!--                            <use xlink:href="img/sprite.svg#clock"></use>-->
<!--                        </svg>-->
<!--                        Собрать к 12:23-->
<!--                    </div>-->
<!--                    <div class="order__state">-->
<!--                        <div class="order__in-process">-->
<!--                            <div class="circle-timer" data-time-limit="8000"></div>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
<!--                <div class="order__cancel snap-drawers">-->
<!--                    <div class="snap-drawer snap-drawer-right" data-target="#deleteModal">Отменить</div>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
<!--    <div class="steps">-->
<!--        <div class="steps__item"> Принять заказ</div>-->
<!--        <div class="steps__arrow"></div>-->
<!--        <div class="steps__item">Заказ готов</div>-->
<!--        <div class="steps__arrow"></div>-->
<!--        <div class="steps__item is-active">Передать курьеру</div>-->
<!--    </div>-->
<!--    <div class="order-cover">-->
<!--        <div class="order-status">-->
<!--            <div class="order-status__img">-->
<!--                <svg width="75" height="75" aria-hidden="true">-->
<!--                    <use xlink:href="img/sprite.svg#bouquet"></use>-->
<!--                </svg>-->
<!--            </div>-->
<!--            <div class="order-status__title">№934013 готов</div>-->
<!--            <div class="order-status__text">Передайте заказ курьеру</div>-->
<!--        </div>-->
<!--    </div>-->
<!---->
<!--    <div class="aside">-->
<!--        <div class="aside__dropdown">-->
<!--            <select name="orders" id="orders-select">-->
<!--                <option selected="selected">Новые заказы-->
<!--                    <span>14</span>-->
<!--                </option>-->
<!--                <option>Собранные-->
<!--                    <span>1</span>-->
<!--                </option>-->
<!--                <option>Отмененные-->
<!--                    <span>9</span>-->
<!--                </option>-->
<!--            </select>-->
<!--        </div>-->
<!--        <div class="aside__orders-list">-->
<!--            <div class="aside__order">-->
<!--                <div class="order order--is-opened snap-content">-->
<!--                    <div class="order__title">№934013</div>-->
<!--                    <div class="order__note">-->
<!--                        <svg width="15" height="22" aria-hidden="true">-->
<!--                            <use xlink:href="img/sprite.svg#clock"></use>-->
<!--                        </svg>-->
<!--                        Собрать как можно скорее-->
<!--                    </div>-->
<!--                    <div class="order__state">-->
<!--                        <div class="order__in-process">-->
<!--                            <div class="circle-timer" data-time-limit="180"></div>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
<!--                <div class="order__cancel snap-drawers">-->
<!--                    <div class="snap-drawer snap-drawer-right" data-target="#deleteModal">Отменить</div>-->
<!--                </div>-->
<!--            </div>-->
<!--            <div class="aside__order">-->
<!--                <div class="order snap-content">-->
<!--                    <div class="order__title">№934014</div>-->
<!--                    <div class="order__note">-->
<!--                        <svg width="15" height="22" aria-hidden="true">-->
<!--                            <use xlink:href="img/sprite.svg#clock"></use>-->
<!--                        </svg>-->
<!--                        Собрать к 13:04-->
<!--                    </div>-->
<!--                    <div class="order__state">-->
<!--                        <div class="order__in-process">-->
<!--                            <div class="circle-timer" data-time-limit="3000"></div>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
<!--                <div class="order__cancel snap-drawers">-->
<!--                    <div class="snap-drawer snap-drawer-right" data-target="#deleteModal">Отменить</div>-->
<!--                </div>-->
<!--            </div>-->
<!--            <div class="aside__order">-->
<!--                <div class="order snap-content">-->
<!--                    <div class="order__title">№934015</div>-->
<!--                    <div class="order__note">-->
<!--                        <svg width="15" height="22" aria-hidden="true">-->
<!--                            <use xlink:href="img/sprite.svg#clock"></use>-->
<!--                        </svg>-->
<!--                        Собрать к 12:08-->
<!--                    </div>-->
<!--                    <div class="order__state">-->
<!--                        <div class="order__done">-->
<!--                            <svg width="17" height="15" aria-hidden="true">-->
<!--                                <use xlink:href="img/sprite.svg#tick"></use>-->
<!--                            </svg>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
<!--                <div class="order__cancel snap-drawers">-->
<!--                    <div class="snap-drawer snap-drawer-right" data-target="#deleteModal">Отменить</div>-->
<!--                </div>-->
<!--            </div>-->
<!--            <div class="aside__order">-->
<!--                <div class="order snap-content">-->
<!--                    <div class="order__title">№934016</div>-->
<!--                    <div class="order__note">-->
<!--                        <svg width="15" height="22" aria-hidden="true">-->
<!--                            <use xlink:href="img/sprite.svg#clock"></use>-->
<!--                        </svg>-->
<!--                        Собрать к 12:13-->
<!--                    </div>-->
<!--                    <div class="order__state">-->
<!--                        <div class="order__in-process">-->
<!--                            <div class="circle-timer" data-time-limit="5000"></div>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
<!--                <div class="order__cancel snap-drawers">-->
<!--                    <div class="snap-drawer snap-drawer-right" data-target="#deleteModal">Отменить</div>-->
<!--                </div>-->
<!--            </div>-->
<!--            <div class="aside__order">-->
<!--                <div class="order snap-content">-->
<!--                    <div class="order__title">№934017</div>-->
<!--                    <div class="order__note">-->
<!--                        <svg width="15" height="22" aria-hidden="true">-->
<!--                            <use xlink:href="img/sprite.svg#clock"></use>-->
<!--                        </svg>-->
<!--                        Собрать к 12:21-->
<!--                    </div>-->
<!--                    <div class="order__state">-->
<!--                        <div class="order__done">-->
<!--                            <svg width="17" height="15" aria-hidden="true">-->
<!--                                <use xlink:href="img/sprite.svg#tick"></use>-->
<!--                            </svg>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
<!--                <div class="order__cancel snap-drawers">-->
<!--                    <div class="snap-drawer snap-drawer-right" data-target="#deleteModal">Отменить</div>-->
<!--                </div>-->
<!--            </div>-->
<!--            <div class="aside__order">-->
<!--                <div class="order snap-content">-->
<!--                    <div class="order__title">№934018</div>-->
<!--                    <div class="order__note">-->
<!--                        <svg width="15" height="22" aria-hidden="true">-->
<!--                            <use xlink:href="img/sprite.svg#clock"></use>-->
<!--                        </svg>-->
<!--                        Собрать к 12:23-->
<!--                    </div>-->
<!--                    <div class="order__state">-->
<!--                        <div class="order__in-process">-->
<!--                            <div class="circle-timer" data-time-limit="8000"></div>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
<!--                <div class="order__cancel snap-drawers">-->
<!--                    <div class="snap-drawer snap-drawer-right" data-target="#deleteModal">Отменить</div>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
<!--    <div class="steps">-->
<!--        <div class="steps__item is-active steps__item--small-text">Принять заказ с истекающим временем</div>-->
<!--        <div class="steps__arrow"></div>-->
<!--        <div class="steps__item">Заказ готов</div>-->
<!--        <div class="steps__arrow"></div>-->
<!--        <div class="steps__item">Передать курьеру</div>-->
<!--    </div>-->
<!--    <div class="order-cover">-->
<!--        <div class="order-summary">-->
<!--            <div class="order-summary__top">-->
<!--                <div class="order-summary__schedule order-summary__block">-->
<!--                    <div class="order-summary__label">Срок приготовления</div>-->
<!--                    <div class="order-summary__data">12:54-->
<!--                        <span class="gray">/ 07:32</span>-->
<!--                    </div>-->
<!--                </div>-->
<!--                <div class="order-summary__order-number order-summary__block">-->
<!--                    <div class="order-summary__label">Номер заказа</div>-->
<!--                    <div class="order-summary__data">#23497</div>-->
<!--                </div>-->
<!--                <div class="order-summary__total-price order-summary__block">-->
<!--                    <div class="order-summary__label">Итого за день</div>-->
<!--                    <div class="order-summary__data">2 800-->
<!--                        <span>₽</span>-->
<!--                    </div>-->
<!--                </div>-->
<!--                <div class="order-summary__state order-summary__block">-->
<!--                    <div class="order-summary__label order-summary__label--green">Новый</div>-->
<!--                </div>-->
<!--            </div>-->
<!--            <div class="order-summary__bottom">-->
<!--                <div class="order-summary__comment">Погрейте, пожалуйста, круассан и поставьте напитки в-->
<!--                    капхолдер 🙏-->
<!--                </div>-->
<!--                <div class="order-summary__total-price">700-->
<!--                    <span>₽</span>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
<!--        <div class="order-list">-->
<!--            <div class="order-item">-->
<!--                <div class="order-item__img">-->
<!--                    <img src="" alt="">-->
<!--                </div>-->
<!--                <div class="order-item__info">-->
<!--                    <div class="order-item__info-top">-->
<!--                        <div class="order-item__name">Chàmomile</div>-->
<!--                        <div class="order-item__ammount">1</div>-->
<!--                        <div class="order-item__price">300 ₽</div>-->
<!--                    </div>-->
<!--                    <div class="order-item__extra">Helper, Доставка</div>-->
<!--                    <div class="order-item__block">-->
<!--                        <div class="order-item__block-title">Состав</div>-->
<!--                        <div class="order-item__block-text">25 пионов сорта ян флеминг</div>-->
<!--                    </div>-->
<!--                    <div class="order-item__block">-->
<!--                        <div class="order-item__block-title">Комментарий</div>-->
<!--                        <div class="order-item__block-text">Сборка разноуровневая, упаковка светлая</div>-->
<!--                    </div>-->
<!--                    <div class="order-item__block">-->
<!--                        <div class="order-item__block-title">Текст открытки</div>-->
<!--                        <div class="order-item__block-text">Цветы для моего руководителя, наставника,-->
<!--                            учителя, примера и доброго человека. С днём рождения Ирина Александровна!!! от-->
<!--                            Рубена.-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
<!--            <div class="order-item">-->
<!--                <div class="order-item__img">-->
<!--                    <img src="" alt="">-->
<!--                </div>-->
<!--                <div class="order-item__info">-->
<!--                    <div class="order-item__info-top">-->
<!--                        <div class="order-item__name">Lola</div>-->
<!--                        <div class="order-item__ammount">1</div>-->
<!--                        <div class="order-item__price">400 ₽</div>-->
<!--                    </div>-->
<!--                    <div class="order-item__block">-->
<!--                        <div class="order-item__block-title">Состав</div>-->
<!--                        <div class="order-item__block-text">25 пионов сорта ян флеминг</div>-->
<!--                    </div>-->
<!--                    <div class="order-item__block">-->
<!--                        <div class="order-item__block-title">Комментарий</div>-->
<!--                        <div class="order-item__block-text">Сборка разноуровневая, упаковка светлая</div>-->
<!--                    </div>-->
<!--                    <div class="order-item__block">-->
<!--                        <div class="order-item__block-title">Текст открытки</div>-->
<!--                        <div class="order-item__block-text">Цветы для моего руководителя, наставника,-->
<!--                            учителя, примера и доброго человека. С днём рождения Ирина Александровна!!! от-->
<!--                            Рубена.-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
<!---->
<!--    <div class="aside">-->
<!--        <div class="aside__dropdown">-->
<!--            <select name="orders" id="orders-select">-->
<!--                <option selected="selected">Новые заказы-->
<!--                    <span>14</span>-->
<!--                </option>-->
<!--                <option>Собранные-->
<!--                    <span>1</span>-->
<!--                </option>-->
<!--                <option>Отмененные-->
<!--                    <span>9</span>-->
<!--                </option>-->
<!--            </select>-->
<!--        </div>-->
<!--        <div class="aside__orders-list">-->
<!--            <div class="aside__order">-->
<!--                <div class="order order--is-opened snap-content">-->
<!--                    <div class="order__title">№934013</div>-->
<!--                    <div class="order__note">-->
<!--                        <svg width="15" height="22" aria-hidden="true">-->
<!--                            <use xlink:href="img/sprite.svg#clock"></use>-->
<!--                        </svg>-->
<!--                        Собрать как можно скорее-->
<!--                    </div>-->
<!--                    <div class="order__state">-->
<!--                        <div class="order__in-process">-->
<!--                            <div class="circle-timer" data-time-limit="180"></div>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
<!--                <div class="order__cancel snap-drawers">-->
<!--                    <div class="snap-drawer snap-drawer-right" data-target="#deleteModal">Отменить</div>-->
<!--                </div>-->
<!--            </div>-->
<!--            <div class="aside__order">-->
<!--                <div class="order snap-content">-->
<!--                    <div class="order__title">№934014</div>-->
<!--                    <div class="order__note">-->
<!--                        <svg width="15" height="22" aria-hidden="true">-->
<!--                            <use xlink:href="img/sprite.svg#clock"></use>-->
<!--                        </svg>-->
<!--                        Собрать к 13:04-->
<!--                    </div>-->
<!--                    <div class="order__state">-->
<!--                        <div class="order__in-process">-->
<!--                            <div class="circle-timer" data-time-limit="3000"></div>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
<!--                <div class="order__cancel snap-drawers">-->
<!--                    <div class="snap-drawer snap-drawer-right" data-target="#deleteModal">Отменить</div>-->
<!--                </div>-->
<!--            </div>-->
<!--            <div class="aside__order">-->
<!--                <div class="order snap-content">-->
<!--                    <div class="order__title">№934015</div>-->
<!--                    <div class="order__note">-->
<!--                        <svg width="15" height="22" aria-hidden="true">-->
<!--                            <use xlink:href="img/sprite.svg#clock"></use>-->
<!--                        </svg>-->
<!--                        Собрать к 12:08-->
<!--                    </div>-->
<!--                    <div class="order__state">-->
<!--                        <div class="order__done">-->
<!--                            <svg width="17" height="15" aria-hidden="true">-->
<!--                                <use xlink:href="img/sprite.svg#tick"></use>-->
<!--                            </svg>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
<!--                <div class="order__cancel snap-drawers">-->
<!--                    <div class="snap-drawer snap-drawer-right" data-target="#deleteModal">Отменить</div>-->
<!--                </div>-->
<!--            </div>-->
<!--            <div class="aside__order">-->
<!--                <div class="order snap-content">-->
<!--                    <div class="order__title">№934016</div>-->
<!--                    <div class="order__note">-->
<!--                        <svg width="15" height="22" aria-hidden="true">-->
<!--                            <use xlink:href="img/sprite.svg#clock"></use>-->
<!--                        </svg>-->
<!--                        Собрать к 12:13-->
<!--                    </div>-->
<!--                    <div class="order__state">-->
<!--                        <div class="order__in-process">-->
<!--                            <div class="circle-timer" data-time-limit="5000"></div>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
<!--                <div class="order__cancel snap-drawers">-->
<!--                    <div class="snap-drawer snap-drawer-right" data-target="#deleteModal">Отменить</div>-->
<!--                </div>-->
<!--            </div>-->
<!--            <div class="aside__order">-->
<!--                <div class="order snap-content">-->
<!--                    <div class="order__title">№934017</div>-->
<!--                    <div class="order__note">-->
<!--                        <svg width="15" height="22" aria-hidden="true">-->
<!--                            <use xlink:href="img/sprite.svg#clock"></use>-->
<!--                        </svg>-->
<!--                        Собрать к 12:21-->
<!--                    </div>-->
<!--                    <div class="order__state">-->
<!--                        <div class="order__done">-->
<!--                            <svg width="17" height="15" aria-hidden="true">-->
<!--                                <use xlink:href="img/sprite.svg#tick"></use>-->
<!--                            </svg>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
<!--                <div class="order__cancel snap-drawers">-->
<!--                    <div class="snap-drawer snap-drawer-right" data-target="#deleteModal">Отменить</div>-->
<!--                </div>-->
<!--            </div>-->
<!--            <div class="aside__order">-->
<!--                <div class="order snap-content">-->
<!--                    <div class="order__title">№934018</div>-->
<!--                    <div class="order__note">-->
<!--                        <svg width="15" height="22" aria-hidden="true">-->
<!--                            <use xlink:href="img/sprite.svg#clock"></use>-->
<!--                        </svg>-->
<!--                        Собрать к 12:23-->
<!--                    </div>-->
<!--                    <div class="order__state">-->
<!--                        <div class="order__in-process">-->
<!--                            <div class="circle-timer" data-time-limit="8000"></div>-->
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
<!--                <div class="order__cancel snap-drawers">-->
<!--                    <div class="snap-drawer snap-drawer-right" data-target="#deleteModal">Отменить</div>-->
<!--                </div>-->
<!--            </div>-->
<!--        </div>-->
<!--    </div>-->
<!--    <div class="order-cover">-->
<!--        <div class="order-status">-->
<!--            <div class="order-status__img">-->
<!--                <svg width="90" height="59" aria-hidden="true">-->
<!--                    <use xlink:href="img/sprite.svg#car-big"></use>-->
<!--                </svg>-->
<!--            </div>-->
<!--            <div class="order-status__title">№934013 передан курьеру</div>-->
<!--        </div>-->
<!--    </div>-->
<!---->
<!--    <div class="note-block">-->
<!--        <p class="note-block__text">Новых заказов пока нет</p>-->
<!--    </div>-->
<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php';
?>