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

$dateValue = '';
if (!empty($arResult['filter']['date'])) {
    $dateValue = date('Y-m-d', strtotime($arResult['filter']['date']));
}
?>
<div class="history__filter">
    <button class="history__filter-btn js-history-filter-button" type="button">
        <svg width="20" height="22" aria-hidden="true">
            <use xlink:href="<?=SITE_TEMPLATE_PATH?>/img/sprite.svg#filter"></use>
        </svg>
        Фильтры
    </button>
</div>
<div class="history__filters filters js-history-filters">
    <div class="filters__block filters__block--date">
        <label for="filterDate">Дата заказа</label>
        <input
            type="date"
            name="historyFilterDate"
            value="<?=$dateValue?>"
            id="filterDate"
            class="js-history-filter-date"
            placeholder="Выберите дату"
            onChange="this.setAttribute('value', this.value)"
        >
    </div>
    <div class="filters__block filters__block--select">
        <label for="filterStatus">Статус</label>
        <select type="date" name="historyFilterStatus" id="filterStatus" class="js-history-filter-status">
            <option value="">Выберите статус</option>
            <?php foreach ($arResult['statuses'] as $status): ?>
                <?php
                $checked = $status['id'] === $arResult['filter']['status'];
                ?>
                <option value="<?=$status['id']?>" <?=$checked ? 'selected=selected' : ''?>>
                    <?=$status['name']?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>
    <div class="filters__block">
        <label>Сумма</label>
        <div class="filters__inputs">
            <input
                type="number"
                name="historyFilterPriceMin"
                value="<?=empty($arResult['filter']['priceMin']) ? '' : $arResult['filter']['priceMin']?>"
                placeholder="от"
                min="0"
                class="js-history-filter-price-min"
            >
            <input
                type="number"
                name="historyFilterPriceMax"
                value="<?=empty($arResult['filter']['priceMax']) ? '' : $arResult['filter']['priceMax']?>"
                placeholder="до"
                min="0"
                class="js-history-filter-price-max"
            >
        </div>
    </div>
</div>