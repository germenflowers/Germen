<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php';

global $USER;
if ($USER->IsAdmin()) {
    if (!CModule::IncludeModule('iblock')) {
        echo 'Не удалось подключить модуль iblock<br>';
        die;
    }

    $oCUserTypeEntity = new CUserTypeEntity();
    $aUserProperties = array(
        array(
            'ENTITY_ID' => 'IBLOCK_1_SECTION',
            'FIELD_NAME' => 'UF_PAGE_SIZE',
            'USER_TYPE_ID' => 'integer',
            'TEXT' => 'Количество элементов на странице',
        ),
    );
    foreach ($aUserProperties as $aFields) {
        $aFilter = array('ENTITY_ID' => $aFields['ENTITY_ID'], 'FIELD_NAME' => $aFields['FIELD_NAME']);
        $oDbRes = CUserTypeEntity::GetList(array(), $aFilter);
        if ($aDbRes = $oDbRes->Fetch()) {
            echo 'Пользовательское свойство "'.$aFields['FIELD_NAME'].'" уже существует <br>';
        } else {
            $aFields['EDIT_FORM_LABEL'] = array('ru' => $aFields['TEXT'], 'en' => $aFields['TEXT']);
            $aFields['LIST_COLUMN_LABEL'] = array('ru' => $aFields['TEXT'], 'en' => $aFields['TEXT']);
            $aFields['LIST_FILTER_LABEL'] = array('ru' => $aFields['TEXT'], 'en' => $aFields['TEXT']);
            $aFields['ERROR_MESSAGE'] = array('ru' => $aFields['TEXT'], 'en' => $aFields['TEXT']);
            $aFields['HELP_MESSAGE'] = array('ru' => $aFields['TEXT'], 'en' => $aFields['TEXT']);
            unset($aFields['TEXT']);

            if ($oCUserTypeEntity->Add($aFields)) {
                echo 'Успешно создано пользовательское свойство "'.$aFields['FIELD_NAME'].'" <br>';
            } else {
                echo 'Не удалось создать пользовательское свойство "'.$aFields['FIELD_NAME'].'" <br>';
                if ($oException = $APPLICATION->GetException()) {
                    echo 'Error: '.$oException->GetString().'<br>';
                }
            }
        }
    }

    $CIBlockSection = new CIBlockSection;

    $filter = array('IBLOCK_ID' => 1);
    $select = array('IBLOCK_ID', 'ID');
    $result = CIBlockSection::GetList(array(), $filter, false, $select);
    while ($row = $result->fetch()) {
        $CIBlockSection->Update($row['ID'], array('UF_PAGE_SIZE' => 4));
    }
}