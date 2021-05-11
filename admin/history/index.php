<?php

/**
 * @global CMain $APPLICATION
 */

require_once $_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php';

$APPLICATION->SetTitle('История');
?>
<div class="history">
    <div class="history__top">
        <h2 class="history__title">История</h2>
        <div class="history__search">
            <input type="search" placeholder="Поиск">
            <svg width="19" height="22" aria-hidden="true">
                <use xlink:href="img/sprite.svg#search"></use>
            </svg>
        </div>
        <div class="history__filter">
            <button class="history__filter-btn" type="button">
                <svg width="20" height="22" aria-hidden="true">
                    <use xlink:href="img/sprite.svg#filter"></use>
                </svg>
                Фильтры
            </button>
        </div>
        <div class="history__filters filters">
            <div class="filters__block filters__block--date">
                <label for="filterDate">Дата заказа</label>
                <input type="date" id="filterDate" placeholder="Выберите дату" value="" onChange="this.setAttribute('value', this.value)">
            </div>
            <div class="filters__block filters__block--select">
                <label for="filterStatus">Статус</label>
                <select type="date" id="filterStatus">
                    <option value="">Выберите статус</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                </select>
            </div>
            <div class="filters__block">
                <label for="filterStatus">Сумма</label>
                <div class="filters__inputs">
                    <input type="number" placeholder="от" min="0">
                    <input type="number" placeholder="до" min="0">
                </div>
            </div>
        </div>
    </div>
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
                <div class="history__table" id="history-accordion">
                    <h3 class="tr">
                        <div class="td history__table-order">№934012</div>
                        <div class="td history__table-composition">Chàmomile, 2 х LOVE +2</div>
                        <div class="td history__table-date">27.01.2021</div>
                        <div class="td history__table-time">12:10</div>
                        <div class="td history__table-sum">12 900 ₽</div>
                        <div class="td--arrow"></div>
                    </h3>
                    <div>
                        <table class="history__table--inner">
                            <tr>
                                <td class="td td--inner history__table-composition history__table-composition--inner">
                                    Chàmomile
                                    <span>Helper, Доставка</span>
                                </td>
                                <td class="td td--inner history__table-ammont">1</td>
                                <td class="td td--inner history__table-sum--inner">3 350 ₽</td>
                            </tr>
                            <tr>
                                <td class="td td--inner history__table-composition">LOVE</td>
                                <td class="td td--inner history__table-ammont">2</td>
                                <td class="td td--inner history__table-sum--inner">3 240 ₽</td>
                            </tr>
                        </table>
                        <div class="history__table-comment">
                            <svg width="21" height="40" aria-hidden="true">
                                <use xlink:href="img/sprite.svg#comment"></use>
                            </svg>
                            Упакуйте, пожалуйста, хорошо 🙏
                        </div>
                        <div class="history__table-notes">
                            <div class="history__table-note1">Приготовлен в 11:54</div>
                            <div class="history__table-note2">Телефон: +7 905 932 3493</div>
                        </div>
                    </div>
                    <h3 class="tr">
                        <div class="td history__table-order">№934011</div>
                        <div class="td history__table-composition">Stockholm, Juls</div>
                        <div class="td history__table-date">27.01.2021</div>
                        <div class="td history__table-time">12:00</div>
                        <div class="td history__table-sum">7 350 ₽</div>
                        <div class="td--arrow"></div>
                    </h3>
                    <div>
                        <table class="history__table--inner">
                            <tr>
                                <td class="td td--inner history__table-composition history__table-composition--inner">
                                    Chàmomile
                                    <span>Helper, Доставка</span>
                                </td>
                                <td class="td td--inner history__table-ammont">1</td>
                                <td class="td td--inner history__table-sum--inner">3 350 ₽</td>
                            </tr>
                            <tr>
                                <td class="td td--inner history__table-composition">LOVE</td>
                                <td class="td td--inner history__table-ammont">2</td>
                                <td class="td td--inner history__table-sum--inner">3 240 ₽</td>
                            </tr>
                        </table>
                        <div class="history__table-comment">
                            <svg width="21" height="40" aria-hidden="true">
                                <use xlink:href="img/sprite.svg#comment"></use>
                            </svg>
                            Упакуйте, пожалуйста, хорошо 🙏
                        </div>
                        <div class="history__table-notes">
                            <div class="history__table-note1">Приготовлен в 11:54</div>
                            <div class="history__table-note2">Телефон: +7 905 932 3493</div>
                        </div>
                    </div>
                    <h3 class="tr">
                        <div class="td history__table-order">№934010</div>
                        <div class="td history__table-composition">Love #1</div>
                        <div class="td history__table-date">27.01.2021</div>
                        <div class="td history__table-time">11:50</div>
                        <div class="td history__table-sum">3 200 ₽</div>
                        <div class="td--arrow"></div>
                    </h3>
                    <div>
                        <table class="history__table--inner">
                            <tr>
                                <td class="td td--inner history__table-composition history__table-composition--inner">
                                    Chàmomile
                                    <span>Helper, Доставка</span>
                                </td>
                                <td class="td td--inner history__table-ammont">1</td>
                                <td class="td td--inner history__table-sum--inner">3 350 ₽</td>
                            </tr>
                            <tr>
                                <td class="td td--inner history__table-composition">LOVE</td>
                                <td class="td td--inner history__table-ammont">2</td>
                                <td class="td td--inner history__table-sum--inner">3 240 ₽</td>
                            </tr>
                        </table>
                        <div class="history__table-comment">
                            <svg width="21" height="40" aria-hidden="true">
                                <use xlink:href="img/sprite.svg#comment"></use>
                            </svg>
                            Упакуйте, пожалуйста, хорошо 🙏
                        </div>
                        <div class="history__table-notes">
                            <div class="history__table-note1">Приготовлен в 11:54</div>
                            <div class="history__table-note2">Телефон: +7 905 932 3493</div>
                        </div>
                    </div>
                    <h3 class="tr">
                        <div class="td history__table-order">№934009</div>
                        <div class="td history__table-composition">Eustoma Rose M</div>
                        <div class="td history__table-date">27.01.2021</div>
                        <div class="td history__table-time">11:40</div>
                        <div class="td history__table-sum">3 250 ₽</div>
                        <div class="td--arrow"></div>
                    </h3>
                    <div>
                        <table class="history__table--inner">
                            <tr>
                                <td class="td td--inner history__table-composition history__table-composition--inner">
                                    Chàmomile
                                    <span>Helper, Доставка</span>
                                </td>
                                <td class="td td--inner history__table-ammont">1</td>
                                <td class="td td--inner history__table-sum--inner">3 350 ₽</td>
                            </tr>
                            <tr>
                                <td class="td td--inner history__table-composition">LOVE</td>
                                <td class="td td--inner history__table-ammont">2</td>
                                <td class="td td--inner history__table-sum--inner">3 240 ₽</td>
                            </tr>
                        </table>
                        <div class="history__table-comment">
                            <svg width="21" height="40" aria-hidden="true">
                                <use xlink:href="img/sprite.svg#comment"></use>
                            </svg>
                            Упакуйте, пожалуйста, хорошо 🙏
                        </div>
                        <div class="history__table-notes">
                            <div class="history__table-note1">Приготовлен в 11:54</div>
                            <div class="history__table-note2">Телефон: +7 905 932 3493</div>
                        </div>
                    </div>
                    <h3 class="tr">
                        <div class="td history__table-order">№934009</div>
                        <div class="td history__table-composition">Round</div>
                        <div class="td history__table-date">27.01.2021</div>
                        <div class="td history__table-time">11:40</div>
                        <div class="td history__table-sum">3 250 ₽</div>
                        <div class="td--arrow"></div>
                    </h3>
                    <div></div>
                    <h3 class="tr">
                        <div class="td history__table-order">№934008</div>
                        <div class="td history__table-composition">Tolya</div>
                        <div class="td history__table-date">27.01.2021</div>
                        <div class="td history__table-time">11:40</div>
                        <div class="td history__table-sum">3 250 ₽</div>
                        <div class="td--arrow"></div>
                    </h3>
                    <div></div>
                    <h3 class="tr">
                        <div class="td history__table-order">№934007</div>
                        <div class="td history__table-composition">Yan</div>
                        <div class="td history__table-date">27.01.2021</div>
                        <div class="td history__table-time">11:40</div>
                        <div class="td history__table-sum">3 250 ₽</div>
                        <div class="td--arrow"></div>
                    </h3>
                    <div></div>
                    <h3 class="tr">
                        <div class="td history__table-order">№934006</div>
                        <div class="td history__table-composition">Eustoma pink L</div>
                        <div class="td history__table-date">27.01.2021</div>
                        <div class="td history__table-time">11:40</div>
                        <div class="td history__table-sum">3 250 ₽</div>
                        <div class="td--arrow"></div>
                    </h3>
                    <div></div>
                    <h3 class="tr">
                        <div class="td history__table-order">№934005</div>
                        <div class="td history__table-composition">Iris</div>
                        <div class="td history__table-date">27.01.2021</div>
                        <div class="td history__table-time">11:40</div>
                        <div class="td history__table-sum">3 250 ₽</div>
                        <div class="td--arrow"></div>
                    </h3>
                    <div></div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="history">
    <div class="history__top">
        <h2 class="history__title">История</h2>
        <div class="history__search">
            <input type="search" placeholder="Поиск">
            <svg width="19" height="22" aria-hidden="true">
                <use xlink:href="img/sprite.svg#search"></use>
            </svg>
        </div>
        <div class="history__filter">
            <button class="history__filter-btn" type="button">
                <span>2</span>
                Фильтры
            </button>
        </div>
        <div class="history__filters filters">
            <div class="filters__block filters__block--date">
                <label for="filterDate">Дата заказа</label>
                <input type="date" id="filterDate" placeholder="Выберите дату" value="2018-07-22" onChange="this.setAttribute('value', this.value)">
            </div>
            <div class="filters__block filters__block--select">
                <label for="filterStatus">Статус</label>
                <select type="date" id="filterStatus" required>
                    <option value="">Выберите статус</option>
                    <option value="1">Создан</option>
                    <option value="2" selected>Отменен</option>
                </select>
            </div>
            <div class="filters__block">
                <label for="filterStatus">Сумма</label>
                <div class="filters__inputs">
                    <input type="number" placeholder="от" min="0" value="100">
                    <input type="number" placeholder="до" min="0" value="500">
                </div>
            </div>
        </div>
    </div>
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
                <div class="history__table" id="history-accordion">
                    <h3 class="tr">
                        <div class="td history__table-order">№934012</div>
                        <div class="td history__table-composition">Chàmomile, 2 х LOVE +2</div>
                        <div class="td history__table-date">27.01.2021</div>
                        <div class="td history__table-time">12:10</div>
                        <div class="td history__table-sum">12 900 ₽</div>
                        <div class="td--arrow"></div>
                    </h3>
                    <div>
                        <table class="history__table--inner">
                            <tr>
                                <td class="td td--inner history__table-composition history__table-composition--inner">
                                    Chàmomile
                                    <span>Helper, Доставка</span>
                                </td>
                                <td class="td td--inner history__table-ammont">1</td>
                                <td class="td td--inner history__table-sum--inner">3 350 ₽</td>
                            </tr>
                            <tr>
                                <td class="td td--inner history__table-composition">LOVE</td>
                                <td class="td td--inner history__table-ammont">2</td>
                                <td class="td td--inner history__table-sum--inner">3 240 ₽</td>
                            </tr>
                        </table>
                        <div class="history__table-comment">
                            <svg width="21" height="40" aria-hidden="true">
                                <use xlink:href="img/sprite.svg#comment"></use>
                            </svg>
                            Упакуйте, пожалуйста, хорошо 🙏
                        </div>
                        <div class="history__table-notes">
                            <div class="history__table-note1">Приготовлен в 11:54</div>
                            <div class="history__table-note2">Телефон: +7 905 932 3493</div>
                        </div>
                    </div>
                    <h3 class="tr">
                        <div class="td history__table-order">№934011</div>
                        <div class="td history__table-composition">Stockholm, Juls</div>
                        <div class="td history__table-date">27.01.2021</div>
                        <div class="td history__table-time">12:00</div>
                        <div class="td history__table-sum">7 350 ₽</div>
                        <div class="td--arrow"></div>
                    </h3>
                    <div>
                        <table class="history__table--inner">
                            <tr>
                                <td class="td td--inner history__table-composition history__table-composition--inner">
                                    Chàmomile
                                    <span>Helper, Доставка</span>
                                </td>
                                <td class="td td--inner history__table-ammont">1</td>
                                <td class="td td--inner history__table-sum--inner">3 350 ₽</td>
                            </tr>
                            <tr>
                                <td class="td td--inner history__table-composition">LOVE</td>
                                <td class="td td--inner history__table-ammont">2</td>
                                <td class="td td--inner history__table-sum--inner">3 240 ₽</td>
                            </tr>
                        </table>
                        <div class="history__table-comment">
                            <svg width="21" height="40" aria-hidden="true">
                                <use xlink:href="img/sprite.svg#comment"></use>
                            </svg>
                            Упакуйте, пожалуйста, хорошо 🙏
                        </div>
                        <div class="history__table-notes">
                            <div class="history__table-note1">Приготовлен в 11:54</div>
                            <div class="history__table-note2">Телефон: +7 905 932 3493</div>
                        </div>
                    </div>
                    <h3 class="tr">
                        <div class="td history__table-order">№934010</div>
                        <div class="td history__table-composition">Love #1</div>
                        <div class="td history__table-date">27.01.2021</div>
                        <div class="td history__table-time">11:50</div>
                        <div class="td history__table-sum">3 200 ₽</div>
                        <div class="td--arrow"></div>
                    </h3>
                    <div>
                        <table class="history__table--inner">
                            <tr>
                                <td class="td td--inner history__table-composition history__table-composition--inner">
                                    Chàmomile
                                    <span>Helper, Доставка</span>
                                </td>
                                <td class="td td--inner history__table-ammont">1</td>
                                <td class="td td--inner history__table-sum--inner">3 350 ₽</td>
                            </tr>
                            <tr>
                                <td class="td td--inner history__table-composition">LOVE</td>
                                <td class="td td--inner history__table-ammont">2</td>
                                <td class="td td--inner history__table-sum--inner">3 240 ₽</td>
                            </tr>
                        </table>
                        <div class="history__table-comment">
                            <svg width="21" height="40" aria-hidden="true">
                                <use xlink:href="img/sprite.svg#comment"></use>
                            </svg>
                            Упакуйте, пожалуйста, хорошо 🙏
                        </div>
                        <div class="history__table-notes">
                            <div class="history__table-note1">Приготовлен в 11:54</div>
                            <div class="history__table-note2">Телефон: +7 905 932 3493</div>
                        </div>
                    </div>
                    <h3 class="tr">
                        <div class="td history__table-order">№934009</div>
                        <div class="td history__table-composition">Eustoma Rose M</div>
                        <div class="td history__table-date">27.01.2021</div>
                        <div class="td history__table-time">11:40</div>
                        <div class="td history__table-sum">3 250 ₽</div>
                        <div class="td--arrow"></div>
                    </h3>
                    <div>
                        <table class="history__table--inner">
                            <tr>
                                <td class="td td--inner history__table-composition history__table-composition--inner">
                                    Chàmomile
                                    <span>Helper, Доставка</span>
                                </td>
                                <td class="td td--inner history__table-ammont">1</td>
                                <td class="td td--inner history__table-sum--inner">3 350 ₽</td>
                            </tr>
                            <tr>
                                <td class="td td--inner history__table-composition">LOVE</td>
                                <td class="td td--inner history__table-ammont">2</td>
                                <td class="td td--inner history__table-sum--inner">3 240 ₽</td>
                            </tr>
                        </table>
                        <div class="history__table-comment">
                            <svg width="21" height="40" aria-hidden="true">
                                <use xlink:href="img/sprite.svg#comment"></use>
                            </svg>
                            Упакуйте, пожалуйста, хорошо 🙏
                        </div>
                        <div class="history__table-notes">
                            <div class="history__table-note1">Приготовлен в 11:54</div>
                            <div class="history__table-note2">Телефон: +7 905 932 3493</div>
                        </div>
                    </div>
                    <h3 class="tr">
                        <div class="td history__table-order">№934009</div>
                        <div class="td history__table-composition">Round</div>
                        <div class="td history__table-date">27.01.2021</div>
                        <div class="td history__table-time">11:40</div>
                        <div class="td history__table-sum">3 250 ₽</div>
                        <div class="td--arrow"></div>
                    </h3>
                    <div></div>
                    <h3 class="tr">
                        <div class="td history__table-order">№934008</div>
                        <div class="td history__table-composition">Tolya</div>
                        <div class="td history__table-date">27.01.2021</div>
                        <div class="td history__table-time">11:40</div>
                        <div class="td history__table-sum">3 250 ₽</div>
                        <div class="td--arrow"></div>
                    </h3>
                    <div></div>
                    <h3 class="tr">
                        <div class="td history__table-order">№934007</div>
                        <div class="td history__table-composition">Yan</div>
                        <div class="td history__table-date">27.01.2021</div>
                        <div class="td history__table-time">11:40</div>
                        <div class="td history__table-sum">3 250 ₽</div>
                        <div class="td--arrow"></div>
                    </h3>
                    <div></div>
                    <h3 class="tr">
                        <div class="td history__table-order">№934006</div>
                        <div class="td history__table-composition">Eustoma pink L</div>
                        <div class="td history__table-date">27.01.2021</div>
                        <div class="td history__table-time">11:40</div>
                        <div class="td history__table-sum">3 250 ₽</div>
                        <div class="td--arrow"></div>
                    </h3>
                    <div></div>
                    <h3 class="tr">
                        <div class="td history__table-order">№934005</div>
                        <div class="td history__table-composition">Iris</div>
                        <div class="td history__table-date">27.01.2021</div>
                        <div class="td history__table-time">11:40</div>
                        <div class="td history__table-sum">3 250 ₽</div>
                        <div class="td--arrow"></div>
                    </h3>
                    <div></div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
require_once $_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php';
?>