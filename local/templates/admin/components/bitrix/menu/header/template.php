<?php

/**
 * @var array $arParams
 * @var array $arResult
 */

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$this->setFrameMode(true);
?>
<div class="header__tabs js-tabs" id="header-tabs">
    <ul class="header__tabs-header js-tabs__header">
        <?php foreach ($arResult as $item): ?>
            <li class="header__tabs-item">
                <?php if ((int)$item['SELECTED'] === 1): ?>
                    <a class="header__tabs-link active js-tabs__title" href="<?=$item['LINK']?>"><?=$item['TEXT']?></a>
                <?php else: ?>
                    <a class="header__tabs-link js-tabs__title" href="<?=$item['LINK']?>"><?=$item['TEXT']?></a>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>
</div>