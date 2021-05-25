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

$component = $this->getComponent();
?>
<?php if ((int)$arResult['CURRENT_PAGE'] !== (int)$arResult['PAGE_COUNT']): ?>
    <span
            class="pagenavigation-params js-pagenavigation-params"
            data-id="<?=$arResult['ID']?>"
            data-page="<?=$arResult['CURRENT_PAGE']?>"
            data-count="<?=$arResult['PAGE_COUNT']?>"
            data-size="<?=$arResult['PAGE_SIZE']?>"
            data-url="<?=$component->replaceUrlTemplate($arResult['CURRENT_PAGE'] + 1)?>"
    ></span>
<?php endif; ?>