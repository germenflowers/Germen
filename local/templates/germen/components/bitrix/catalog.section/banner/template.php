<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$item = $arResult['ITEMS'][0];

$img = array('src' => '');
if (!empty($item['PROPERTIES']['BANNER_IMG']['VALUE'])) {
    $img = CFile::ResizeImageGet(
        $item['PROPERTIES']['BANNER_IMG']['VALUE'],
        array('width' => 1400, 'height' => 376),
        BX_RESIZE_IMAGE_PROPORTIONAL
    );
} elseif (!empty($item['PREVIEW_PICTURE'])) {
    $img = CFile::ResizeImageGet(
        $item['PREVIEW_PICTURE'],
        array('width' => 1400, 'height' => 376),
        BX_RESIZE_IMAGE_PROPORTIONAL
    );
}
?>
<div class="promo-catalog promo-catalog--main">
    <div class="promo-catalog__header">
        <div class="head-h2 promo-catalog__title"><?=$arParams['BLOCK_TITLE']?></div>
        <?php if ($arParams['SHOW_SECTION_DESC']): ?>
            <div class="promo-catalog__desc promo-section-desc"><?=$arResult['DESCRIPTION']?></div>
        <?php endif; ?>
    </div>
    <?php if (!empty($item)): ?>
        <div class="promo-catalog__banner">
            <a href="#" class="js-detail" data-id="<?=$item['ID']?>">
                <picture>
                    <source srcset="<?=$img['src']?>" media="(max-width: 768px)">
                    <img src="<?=$img['src']?>" alt="<?=$item['NAME']?>">
                </picture>
            </a>
            <div class="promo-catalog__banner-block">
                <h3>
                    <a href="#" class="js-detail" data-id="<?=$item['ID']?>">
                        <?php if (empty($item['PROPERTIES']['BANNER_TITLE']['VALUE'])): ?>
                            <?=$item['NAME']?>
                        <?php else: ?>
                            <?=$item['PROPERTIES']['BANNER_TITLE']['VALUE']?>
                        <?php endif; ?>
                    </a>
                </h3>
                <p>
                    <?php if (empty($item['PROPERTIES']['BANNER_TEXT']['VALUE']['TEXT'])): ?>
                        <?=$item['PREVIEW_TEXT']?>
                    <?php else: ?>
                        <?=$item['PROPERTIES']['BANNER_TEXT']['VALUE']['TEXT']?>
                    <?php endif; ?>
                </p>
                <span class="promo-catalog__banner-price">
                    <?=number_format($item['ITEM_PRICES'][0]['PRICE'], 0, '', ' ')?>
                    <span class="rouble"></span>
                </span>
                <a
                        href="/order/?id=<?=$item['ID']?>"
                        class="promo-item__delivery"
                        style="background-color: #<?=$item['BUTTON_PARAMS']['background']?>;"
                >
                    <div class="promo-item__delivery__text"><?=$item['BUTTON_PARAMS']['text']?></div>
                    <div class="promo-item__delivery__time">
                        <?php if ($item['BUTTON_PARAMS']['showIcon']): ?>
                            <svg class="promo-item__delivery__icon">
                                <use xlink:href="<?=SITE_TEMPLATE_PATH?>/icons/icons.svg?v=<?=VERSION_SPRITE__ICONS?>#properties-car-mini"></use>
                            </svg>
                        <?php endif; ?>
                        <div class="promo-item__delivery__time__text js-prod_time">
                            <?=$item['BUTTON_PARAMS']['time']?>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    <?php endif; ?>
</div>