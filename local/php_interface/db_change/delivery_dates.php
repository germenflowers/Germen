<?php

use \Bitrix\Sale\Order;

require_once $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php';

global $APPLICATION;
global $USER;
if ($USER->IsAdmin()) {
    if (!CModule::IncludeModule('sale')) {
        echo 'Не удалось подключить модуль sale<br>';
        die;
    }

    $personTypeId = 1;

    /**
     * Создаём группу свойств заказа
     */
    $groupId = 0;

    $CSaleOrderPropsGroup = new CSaleOrderPropsGroup();

    $orderPropertiesGroups = array(
        array(
            'NAME' => 'Дата доставки',
            'PERSON_TYPE_ID' => $personTypeId,
        ),
    );
    foreach ($orderPropertiesGroups as $fields) {
        $filter = array('NAME' => $fields['NAME'], 'PERSON_TYPE_ID' => $fields['PERSON_TYPE_ID']);
        $select = array('ID');
        $result = $CSaleOrderPropsGroup->GetList(array(), $filter, false, false, $select);
        if ($row = $result->fetch()) {
            $groupId = $row['ID'];

            echo 'Группа свойств заказа '.$fields['NAME'].' уже существует'."<br>";
        } elseif ($groupId = $CSaleOrderPropsGroup->Add($fields)) {
            echo 'Успешно добавлена группа свойств заказа '.$fields['NAME']."<br>";
        } else {
            echo 'Не удалось добавить группу свойств заказа '.$fields['NAME']."<br>";
            if ($exception = $APPLICATION->GetException()) {
                echo 'Error: '.$exception->GetString()."<br>";
            }
        }
    }

    /**
     * Создаём свойство заказа
     */
    $orderProperties = array(
        'DELIVERY_DATE' => array(
            'NAME' => 'Дата доставки',
            'CODE' => 'DELIVERY_DATE',
            'PERSON_TYPE_ID' => $personTypeId,
            'PROPS_GROUP_ID' => $groupId,
            'TYPE' => 'DATE',
            'SORT' => 10,
            'SETTINGS' => array(
                'TIME' => 'Y',
            ),
        ),
        'DELIVERY_DATE_TO' => array(
            'NAME' => 'Дата доставки (До)',
            'CODE' => 'DELIVERY_DATE_TO',
            'PERSON_TYPE_ID' => $personTypeId,
            'PROPS_GROUP_ID' => $groupId,
            'TYPE' => 'DATE',
            'SORT' => 20,
            'SETTINGS' => array(
                'TIME' => 'Y',
            ),
        ),
        'DELIVERY_DATE_2' => array(
            'NAME' => 'Дата доставки 2',
            'CODE' => 'DELIVERY_DATE_2',
            'PERSON_TYPE_ID' => $personTypeId,
            'PROPS_GROUP_ID' => $groupId,
            'TYPE' => 'DATE',
            'SORT' => 30,
            'SETTINGS' => array(
                'TIME' => 'Y',
            ),
        ),
        'DELIVERY_DATE_3' => array(
            'NAME' => 'Дата доставки 3',
            'CODE' => 'DELIVERY_DATE_3',
            'PERSON_TYPE_ID' => $personTypeId,
            'PROPS_GROUP_ID' => $groupId,
            'TYPE' => 'DATE',
            'SORT' => 50,
            'SETTINGS' => array(
                'TIME' => 'Y',
            ),
        ),
        'DELIVERY_DATE_4' => array(
            'NAME' => 'Дата доставки 4',
            'CODE' => 'DELIVERY_DATE_4',
            'PERSON_TYPE_ID' => $personTypeId,
            'PROPS_GROUP_ID' => $groupId,
            'TYPE' => 'DATE',
            'SORT' => 70,
            'SETTINGS' => array(
                'TIME' => 'Y',
            ),
        ),
        'DELIVERY_DATE_5' => array(
            'NAME' => 'Дата доставки 5',
            'CODE' => 'DELIVERY_DATE_5',
            'PERSON_TYPE_ID' => $personTypeId,
            'PROPS_GROUP_ID' => $groupId,
            'TYPE' => 'DATE',
            'SORT' => 90,
            'SETTINGS' => array(
                'TIME' => 'Y',
            ),
        ),
        'DELIVERY_DATE_6' => array(
            'NAME' => 'Дата доставки 6',
            'CODE' => 'DELIVERY_DATE_6',
            'PERSON_TYPE_ID' => $personTypeId,
            'PROPS_GROUP_ID' => $groupId,
            'TYPE' => 'DATE',
            'SORT' => 110,
            'SETTINGS' => array(
                'TIME' => 'Y',
            ),
        ),
        'DELIVERY_DATE_7' => array(
            'NAME' => 'Дата доставки 7',
            'CODE' => 'DELIVERY_DATE_7',
            'PERSON_TYPE_ID' => $personTypeId,
            'PROPS_GROUP_ID' => $groupId,
            'TYPE' => 'DATE',
            'SORT' => 130,
            'SETTINGS' => array(
                'TIME' => 'Y',
            ),
        ),
        'DELIVERY_DATE_8' => array(
            'NAME' => 'Дата доставки 8',
            'CODE' => 'DELIVERY_DATE_8',
            'PERSON_TYPE_ID' => $personTypeId,
            'PROPS_GROUP_ID' => $groupId,
            'TYPE' => 'DATE',
            'SORT' => 150,
            'SETTINGS' => array(
                'TIME' => 'Y',
            ),
        ),
        'DELIVERY_DATE_9' => array(
            'NAME' => 'Дата доставки 9',
            'CODE' => 'DELIVERY_DATE_9',
            'PERSON_TYPE_ID' => $personTypeId,
            'PROPS_GROUP_ID' => $groupId,
            'TYPE' => 'DATE',
            'SORT' => 170,
            'SETTINGS' => array(
                'TIME' => 'Y',
            ),
        ),
        'DELIVERY_DATE_10' => array(
            'NAME' => 'Дата доставки 10',
            'CODE' => 'DELIVERY_DATE_10',
            'PERSON_TYPE_ID' => $personTypeId,
            'PROPS_GROUP_ID' => $groupId,
            'TYPE' => 'DATE',
            'SORT' => 190,
            'SETTINGS' => array(
                'TIME' => 'Y',
            ),
        ),
        'DELIVERY_DATE_11' => array(
            'NAME' => 'Дата доставки 11',
            'CODE' => 'DELIVERY_DATE_11',
            'PERSON_TYPE_ID' => $personTypeId,
            'PROPS_GROUP_ID' => $groupId,
            'TYPE' => 'DATE',
            'SORT' => 210,
            'SETTINGS' => array(
                'TIME' => 'Y',
            ),
        ),
        'DELIVERY_DATE_12' => array(
            'NAME' => 'Дата доставки 12',
            'CODE' => 'DELIVERY_DATE_12',
            'PERSON_TYPE_ID' => $personTypeId,
            'PROPS_GROUP_ID' => $groupId,
            'TYPE' => 'DATE',
            'SORT' => 230,
            'SETTINGS' => array(
                'TIME' => 'Y',
            ),
        ),
    );
    foreach ($orderProperties as $fields) {
        $filter = array(
            'CODE' => $fields['CODE'],
            'PERSON_TYPE_ID' => $fields['PERSON_TYPE_ID'],
            'PROPS_GROUP_ID' => $fields['PROPS_GROUP_ID'],
        );
        $select = array('ID');
        $result = CSaleOrderProps::GetList(array(), $filter, false, false, $select);
        if ($row = $result->fetch()) {
            $orderProperties[$fields['CODE']]['ID'] = $row['ID'];

            echo 'Свойство заказа '.$fields['NAME'].' уже существует'."<br>";
        } elseif ($orderProperties[$fields['CODE']]['ID'] = CSaleOrderProps::Add($fields)) {
            echo 'Успешно добавлено свойство заказа '.$fields['NAME']."<br>";
        } else {
            echo 'Не удалось добавить свойство заказа '.$fields['NAME']."<br>";
            if ($exception = $APPLICATION->GetException()) {
                echo 'Error: '.$exception->GetString()."<br>";
            }
        }
    }

    $items = array();
    $filter = array(
        'CODE' => 'TIME',
        '!VALUE' => false,
    );
    $select = array('ID', 'ORDER_ID', 'VALUE_ORIG');
    $result = CSaleOrderPropsValue::GetList(array(), $filter, false, false, $select);
    while ($row = $result->Fetch()) {
        if (is_array($row['VALUE_ORIG'])) {
            $values = array();
            foreach ($row['VALUE_ORIG'] as $value) {
                $dateTimeString = validateAndFormatDateTime(trim($value));
                if (empty($dateTimeString)) {
                    continue;
                }

                $values[] = $dateTimeString;
            }
        } else {
            $dateTimeString = validateAndFormatDateTime(trim($row['VALUE_ORIG']));

            if (empty($dateTimeString)) {
                $values = array();
            } else {
                $values = array($dateTimeString);
            }
        }

        if (!empty($values)) {
            $items[] = array(
                'orderId' => $row['ORDER_ID'],
                'values' => $values,
            );
        }
    }

    $currentValues = array();
    $filter = array(
        'CODE' => array(
            'DELIVERY_DATE',
            'DELIVERY_DATE_2',
            'DELIVERY_DATE_3',
            'DELIVERY_DATE_4',
            'DELIVERY_DATE_5',
            'DELIVERY_DATE_6',
            'DELIVERY_DATE_7',
            'DELIVERY_DATE_8',
            'DELIVERY_DATE_9',
            'DELIVERY_DATE_10',
            'DELIVERY_DATE_11',
            'DELIVERY_DATE_12',
        ),
        '!VALUE' => false,
    );
    $select = array('ORDER_ID', 'CODE', 'VALUE');
    $result = CSaleOrderPropsValue::GetList(array(), $filter, false, false, $select);
    while ($row = $result->Fetch()) {
        $currentValues[$row['ORDER_ID']][$row['CODE']] = $row['VALUE'];
    }

    foreach ($items as $item) {
        if (empty($item['orderId'])) {
            continue;
        }

        foreach ($item['values'] as $key => $value) {
            $propertyCode = 'DELIVERY_DATE';
            if ($key !== 0) {
                $propertyCode = 'DELIVERY_DATE'.'_'.++$key;
            }

            if (empty($orderProperties[$propertyCode]['ID'])) {
                continue;
            }

            if (empty($orderProperties[$propertyCode]['NAME'])) {
                continue;
            }

            if (!empty($currentValues[$item['orderId']][$propertyCode])) {
                continue;
            }

            CSaleOrderPropsValue::Add(
                array(
                    'NAME' => $orderProperties[$propertyCode]['NAME'],
                    'CODE' => $propertyCode,
                    'ORDER_PROPS_ID' => $orderProperties[$propertyCode]['ID'],
                    'ORDER_ID' => $item['orderId'],
                    'VALUE' => $value,
                )
            );
        }
    }
}

/**
 * @param string $dateTimeString
 * @return string
 */
function validateAndFormatDateTime(string $dateTimeString): string
{
    $result = validateDateTime($dateTimeString, 'd.m.Y H:i');

    if ($result['error']) {
        $result = validateDateTime($dateTimeString, 'd.m.Y H.i');
    }

    if ($result['error']) {
        $result = validateDateTime($dateTimeString, 'd.m.Y H-i');
    }

    if ($result['error']) {
        $result = validateDateTime($dateTimeString, 'd.m.Y до H:i');
    }

    if ($result['error']) {
        $result = validateDateTime($dateTimeString, 'd.m.Y в H:i');
    }

    if ($result['error']) {
        $result = validateDateTime($dateTimeString, 'd.m.Y');
    }

    if ($result['error']) {
        $result = validateDateTime($dateTimeString, 'd.m.Y H. i');
    }

    if ($result['error']) {
        $dateTimeString = '';
    } else {
        $dateTimeString = $result['dateTimeObject']->format('d.m.Y H:i');
    }

    return $dateTimeString;
}

/**
 * @param string $dateTimeString
 * @param string $format
 * @return array
 */
function validateDateTime(string $dateTimeString, string $format): array
{
    $error = false;

    $date = DateTime::createFromFormat($format, $dateTimeString);
    $date_errors = DateTime::getLastErrors();
    if ($date_errors['warning_count'] + $date_errors['error_count'] > 0) {
        $error = true;
    }

    return array(
        'error' => $error,
        'dateTimeObject' => $date,
    );
}