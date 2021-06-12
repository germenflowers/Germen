<?php

namespace Germen\Handlers;

use \Bitrix\Main\Text\Emoji;
use \Germen\Content;

class Sale
{
    /**
     * @param array $fields
     */
    public static function OnBeforeOrderAdd(array &$fields): void
    {
        if (!empty($fields['USER_DESCRIPTION'])) {
            $fields['USER_DESCRIPTION'] = Emoji::encode($fields['USER_DESCRIPTION']);
        }
    }

    /**
     * @param int $orderId
     * @param array $fields
     * @param array $orderFields
     * @param bool $isNew
     */
    public static function OnOrderSave(int $orderId, array $fields, array $orderFields, bool $isNew): void
    {
        self::updateLetterProperty($orderId, $orderFields);
    }

    /**
     * @param int $orderId
     * @param array $orderFields
     * @return bool
     */
    private static function updateLetterProperty(int $orderId, array $orderFields): bool
    {
        $letterProperty = array();
        $filter = array('CODE' => 'TEXT_SCRAP');
        $select = array('ID', 'NAME', 'CODE');
        $result = \CSaleOrderProps::GetList(array(), $filter, false, false, $select);
        while ($row = $result->Fetch()) {
            $letterProperty = array(
                'id' => (int)$row['ID'],
                'name' => $row['NAME'],
                'code' => $row['CODE'],
            );
        }

        if (empty($letterProperty)) {
            return false;
        }

        if (empty($orderFields['ORDER_PROP'][$letterProperty['id']])) {
            return false;
        }

        $value = Emoji::encode($orderFields['ORDER_PROP'][$letterProperty['id']]);

        return Content::addOrderProperty(
            $orderId,
            $letterProperty['id'],
            $letterProperty['code'],
            $letterProperty['name'],
            $value
        );
    }
}
