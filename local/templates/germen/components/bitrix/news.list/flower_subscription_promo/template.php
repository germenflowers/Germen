<?php

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

$this->setFrameMode(true);
?>
<?php if (!empty($arResult['ITEMS'])): ?>
    <div class="benefits">
        <div class="benefits__list">
            <?php foreach ($arResult['ITEMS'] as $item): ?>
                <div class="benefits__list-item">
                    <svg width="40" height="40" aria-hidden="true">
                        <use xlink:href="<?=SITE_TEMPLATE_PATH?>/img/sprites/flower_subscription.svg#<?=$item['CODE']?>"></use>
                    </svg>
                    <h3 class="benefits__title"><?=$item['NAME']?></h3>
                    <div class="benefits__text"><?=$item['PREVIEW_TEXT']?></div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>