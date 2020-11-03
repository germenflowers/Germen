<?php

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

$this->setFrameMode(true);
?>
<?php if (!empty($arResult['ITEMS'])): ?>
    <div class="subscribe-page__labels subscribe-page__labels--desktop">
        <div class="subscribe-page__labels-list">
            <?php foreach ($arResult['ITEMS'] as $item): ?>
                <div class="subscribe-page__labels-item">
                    <svg width="30" height="30" aria-hidden="true">
                        <use xlink:href="<?=SITE_TEMPLATE_PATH?>/img/sprites/flower_subscription.svg#<?=$item['CODE']?>"></use>
                    </svg>
                    <span><?=$item['NAME']?></span>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>