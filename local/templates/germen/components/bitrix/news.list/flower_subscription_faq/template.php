<?php

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
    die();
}

$this->setFrameMode(true);
?>
<?php if (!empty($arResult['ITEMS'])): ?>
    <div class="faq">
        <h2 class="faq__title">Частые вопросы</h2>
        <div class="faq__accord js-Accordion" id="accordion">
            <?php foreach ($arResult['ITEMS'] as $item): ?>
                <button class="faq__btn">
                    <?=$item['NAME']?>
                    <svg width="14" height="15" aria-hidden="true">
                        <use xlink:href="<?=SITE_TEMPLATE_PATH?>/img/sprites/flower_subscription.svg#cross"></use>
                    </svg>
                </button>
                <div class="faq__answer">
                    <span><?=$item['PREVIEW_TEXT']?></span>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
<?php endif; ?>