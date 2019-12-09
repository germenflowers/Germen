<?
foreach( $arResult["ITEMS"] as $key => $arElement ) {
    $arResult["ITEMS"][$key]['PICTURE'] = '';

    $renderImage = CFile::ResizeImageGet($arElement['PREVIEW_PICTURE'], Array("width" => 600, "height" => 600));
    $arResult["ITEMS"][$key]['PICTURE'] = $renderImage['src'];
    unset($renderImage);
}

$arParams["SHOW_SECTION_DESC"] = $arParams["SHOW_SECTION_DESC"] === "Y" && !empty($arResult["DESCRIPTION"]);
