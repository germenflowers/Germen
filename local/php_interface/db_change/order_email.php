<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php';

global $USER;
if ($USER->IsAdmin()) {
    if (!CModule::IncludeModule('sale')) {
        echo 'Не удалось подключить модуль sale<br>';
        die;
    }

    $aOrderProperties = array(
        array(
            "NAME" => "E-mail",
            "CODE" => "EMAIL",
            "PERSON_TYPE_ID" => 1,
            "PROPS_GROUP_ID" => 1,
            "TYPE" => "STRING",
            'IS_EMAIL' => 'Y',
        ),
    );
    foreach ($aOrderProperties as $aFields) {
        $aFilter = array(
            "CODE" => $aFields["CODE"],
            "PERSON_TYPE_ID" => $aFields["PERSON_TYPE_ID"],
            "PROPS_GROUP_ID" => $aFields["PROPS_GROUP_ID"],
        );
        $aSelect = array("ID");
        $oDbRes = CSaleOrderProps::GetList(array(), $aFilter, false, false, $aSelect);
        if ($aDbRes = $oDbRes->fetch()) {
            echo "Свойство заказа \"".$aFields["NAME"]."\" уже существует<br>";
        } elseif (CSaleOrderProps::Add($aFields)) {
            echo "Успешно добавлено свойство заказа \"".$aFields["NAME"]."\"<br>";
        } else {
            echo "Не удалось добавить свойство заказа \"".$aFields["NAME"]."\"<br>";
            if ($oException = $APPLICATION->GetException()) {
                echo "Error: ".$oException->GetString()."<br>";
            }
        }
    }
}