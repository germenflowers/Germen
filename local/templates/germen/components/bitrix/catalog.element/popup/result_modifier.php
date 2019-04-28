<?php
$arResult["DELIVERY_TIME"] = \PDV\Tools::getTimeByDelivery();
$arResult["IS_ORDER"] = $arParams["IS_ORDER"];

$arResult["IMAGES"] = [];

if (!empty($arResult["PREVIEW_PICTURE"])) {
    $arResult["IMAGES"][] = $arResult["PREVIEW_PICTURE"];
}

$propImages = $arResult["DISPLAY_PROPERTIES"]["IMAGES"];

if (!empty($propImages)) {
    if (count($propImages["VALUE"]) > 1) {
        foreach ($propImages["FILE_VALUE"] as $image) {
            $arResult["IMAGES"][] = $image;
        }
    } else {
        $arResult["IMAGES"][] = $propImages["FILE_VALUE"];
    }
}

$pictureLabels = $arResult["DISPLAY_PROPERTIES"]["PICTURE_LABELS"];

if (!empty($pictureLabels)) {

	$elementIds = array_keys($pictureLabels["LINK_ELEMENT_VALUE"]);

	$rsIcons = \CIBlockElement::GetList(
		[],
		[
			"=ID" => $elementIds,
		],
		false,
		false,
		[
			"ID",
			"IBLOCK_ID",
			"PROPERTY_ICON",
		]
	);

	$arIcons = [];

	while ($arIcon = $rsIcons->GetNext()){
		$arIcons[$arIcon["ID"]] = $arIcon;
	}


    foreach ($pictureLabels["LINK_ELEMENT_VALUE"] as &$pictureLabel) {
        if (!empty($arIcons[$pictureLabel["ID"]])) {
            $fileId = $arIcons[$pictureLabel["ID"]]["PROPERTY_ICON_VALUE"];
            $pictureLabel["ICON"] = \CFile::GetFileArray($fileId);
        }
    }

	$arResult["PICTURE_LABELS"] = $pictureLabels["LINK_ELEMENT_VALUE"];
}
