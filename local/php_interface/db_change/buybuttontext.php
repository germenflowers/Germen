<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php';

global $USER;
if ($USER->IsAdmin()) {
    if (!CModule::IncludeModule('iblock')) {
        echo 'Не удалось подключить модуль iblock<br>';
        die;
    }
}

$filter = array('CODE' => 'buybutton', 'IBLOCK_TYPE_ID' => 'germen');
$result = CIBlock::GetList(array(), $filter);
if ($row = $result->fetch()) {
    $iblockId = $row['ID'];
}

$CIBlockProperty = new CIBlockProperty();
$CIBlockPropertyEnum = new CIBlockPropertyEnum();
$properties = array(
    array(
        'IBLOCK_ID' => $iblockId,
        'NAME' => 'Цвет текста',
        'CODE' => 'TEXT_COLOR',
        'USER_TYPE' => 'SASDPalette',
    ),
);
foreach ($properties as $fields) {
    $filter = array('IBLOCK_ID' => $fields['IBLOCK_ID'], 'CODE' => $fields['CODE']);
    $result = CIBlockProperty::GetList(array(), $filter);
    if ($row = $result->fetch()) {
        echo 'Свойство "'.$fields['NAME'].'" уже существует<br>';
    } elseif ($propertyId = $CIBlockProperty->Add($fields)) {
        echo 'Успешно создано свойство "'.$fields['NAME'].'"<br>';

        if (isset($fields['ITEMS']) && !empty($fields['ITEMS'])) {
            foreach ($fields['ITEMS'] as $enumFields) {
                $enumFields['PROPERTY_ID'] = $propertyId;
                if ($CIBlockPropertyEnum::Add($enumFields)) {
                    echo 'Успешно создано значение "'.$enumFields['VALUE'].'" свойства "'.$fields['NAME'].'"<br>';
                } else {
                    echo 'Не удалось создать значение "'.$enumFields['VALUE'].'" свойства "'.$fields['NAME'].'"<br>';
                }
            }
        }
    } else {
        echo 'Не удалось создать свойство "'.$fields['NAME'].'"<br>';
        echo 'Error: '.$CIBlockProperty->LAST_ERROR.'<br>';
    }
}