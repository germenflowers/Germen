<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php';

global $USER;
if ($USER->IsAdmin()) {
    if (!CModule::IncludeModule('iblock')) {
        echo 'Не удалось подключить модуль iblock<br>';
        die;
    }

    $CIBlockProperty = new CIBlockProperty();
    $CIBlockPropertyEnum = new CIBlockPropertyEnum();
    $properties = array(
        array(
            'IBLOCK_ID' => 1,
            'NAME' => 'Банер',
            'CODE' => 'BANNER',
            'PROPERTY_TYPE' => 'L',
            'LIST_TYPE' => 'C',
            'ITEMS' => array(
                array(
                    'VALUE' => 'Да',
                    'XML_ID' => 'Y',
                ),
            ),
        ),
        array(
            'IBLOCK_ID' => 1,
            'NAME' => 'Картинка банера',
            'CODE' => 'BANNER_IMG',
            'PROPERTY_TYPE' => 'F',
            'FILE_TYPE' => 'jpg, gif, bmp, png, jpeg',
        ),
        array(
            'IBLOCK_ID' => 1,
            'NAME' => 'Заголовок банера',
            'CODE' => 'BANNER_TITLE',
        ),
        array(
            'IBLOCK_ID' => 1,
            'NAME' => 'Текст банера',
            'CODE' => 'BANNER_TEXT',
            'USER_TYPE' => 'HTML',
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
}