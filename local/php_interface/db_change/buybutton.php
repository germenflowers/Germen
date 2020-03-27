<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php';

global $USER;
if ($USER->IsAdmin()) {
    if (!CModule::IncludeModule('iblock')) {
        echo 'Не удалось подключить модуль iblock<br>';
        die;
    }

    $CIBlock = new CIBlock();
    $iblock = array(
        'IBLOCK_TYPE_ID' => 'germen',
        'LID' => array('s1'),
        'CODE' => 'buybutton',
        'NAME' => 'Настройка кнопок "Заказать"',
        'LIST_PAGE_URL' => '',
        'SECTION_PAGE_URL' => '',
        'DETAIL_PAGE_URL' => '',
        'INDEX_ELEMENT' => 'N',
        'INDEX_SECTION' => 'N',
        'GROUP_ID' => array(2 => 'R'),
    );
    $filter = array('CODE' => $iblock['CODE'], 'IBLOCK_TYPE_ID' => $iblock['IBLOCK_TYPE_ID']);
    $result = CIBlock::GetList(array(), $filter);
    if ($row = $result->fetch()) {
        $iblockId = $row['ID'];
        echo 'Инфоблок "'.$iblock['NAME'].'" уже существует<br>';
    } elseif ($iblockId = $CIBlock->Add($iblock)) {
        echo 'Успешно создан инфоблок "'.$iblock['NAME'].'"<br>';
    } else {
        echo 'Не удалось создать инфоблок "'.$iblock['NAME'].'"<br>';
        echo 'Error: '.$CIBlock->LAST_ERROR.'<br>';
    }

    if (!empty((int)$iblockId)) {
        $CIBlockProperty = new CIBlockProperty();
        $CIBlockPropertyEnum = new CIBlockPropertyEnum();
        $properties = array(
            array(
                'IBLOCK_ID' => $iblockId,
                'NAME' => 'Фон',
                'CODE' => 'BACKGROUND',
                'USER_TYPE' => 'SASDPalette',
            ),
            array(
                'IBLOCK_ID' => $iblockId,
                'NAME' => 'Текст',
                'CODE' => 'TEXT',
            ),
            array(
                'IBLOCK_ID' => $iblockId,
                'NAME' => 'Время',
                'CODE' => 'TIME',
            ),
            array(
                'IBLOCK_ID' => $iblockId,
                'NAME' => 'Отображать иконку',
                'CODE' => 'SHOW_ICON',
                'PROPERTY_TYPE' => 'L',
                'LIST_TYPE' => 'C',
                'ITEMS' => array(
                    array(
                        'VALUE' => 'Да',
                        'XML_ID' => 'Y',
                        'DEF' => 'Y',
                    ),
                ),
            ),
            array(
                'IBLOCK_ID' => 1,
                'NAME' => 'Вид кнопки',
                'CODE' => 'BUTTON',
                'PROPERTY_TYPE' => 'E',
                'LINK_IBLOCK_ID' => $iblockId,
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

        $showIconValueId = 0;
        $filter = array('IBLOCK_ID' => $iblockId, 'CODE' => 'SHOW_ICON');
        $result = CIBlockProperty::GetList(array(), $filter);
        if ($row = $result->fetch()) {
            $filter = array('PROPERTY_ID' => $row['ID'], 'XML_ID' => 'Y');
            $result = CIBlockPropertyEnum::GetList(array(), $filter);
            if ($row = $result->fetch()) {
                $showIconValueId = $row['ID'];
            }
        }

        $CIBlockElement = new CIBlockElement;
        $elements = array(
            array(
                'IBLOCK_ID' => $iblockId,
                'NAME' => 'По умолчанию',
                'CODE' => 'default',
                'PROPERTY_VALUES' => array(
                    'BACKGROUND' => 'fee6d2',
                    'TEXT' => 'Заказать',
                    'TIME' => '120 мин.',
                    'SHOW_ICON' => $showIconValueId,
                ),
            ),
        );
        foreach ($elements as $fields) {
            $filter = array('IBLOCK_ID' => $fields['IBLOCK_ID'], 'CODE' => $fields['CODE']);
            $select = array('IBLOCK_ID', 'ID');
            $result = CIBlockElement::GetList(array(), $filter, false, false, $select);
            if ($row = $result->fetch()) {
                echo 'Элемент "'.$fields['NAME'].'" уже существует<br>';
            } elseif ($CIBlockElement->Add($fields)) {
                echo 'Успешно создан элемент "'.$fields['NAME'].'"<br>';
            } else {
                echo 'Не удалось создать элемент "'.$fields['NAME'].'"<br>';
                echo 'Error: '.$CIBlockElement->LAST_ERROR.'<br>';
            }
        }
    }
}
