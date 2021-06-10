<?php

namespace Germen\Admin;

/**
 * Class Content
 * @package Germen\Admin
 */
class Content
{
    /**
     * Content constructor.
     */
    public function __construct()
    {
    }

    /**
     * @param $basketItems
     * @return string
     */
    public static function getHistoryListItemTitle($basketItems): string
    {
        $i = 0;
        $otherCounter = 0;
        $main = array();
        foreach ($basketItems as $basketItem) {
            $i++;

            if ($i < 3) {
                if ($basketItem['quantity'] > 1) {
                    $main[] = $basketItem['quantity'].' х '.$basketItem['name'];
                } else {
                    $main[] = $basketItem['name'];
                }
            } else {
                $otherCounter++;
            }
        }

        $title = implode(', ', $main);
        if (!empty($otherCounter)) {
            $title .= ' +'.$otherCounter;
        }

        return $title;
    }

    /**
     * @param $properties
     * @return string
     */
    public static function getHistoryListBasketItemPropertiesString($properties): string
    {
        $propertiesTmp = array();
        foreach ($properties as $property) {
            if ($property['code'] === 'SUBSCRIBE' && !empty($property['value'])) {
                $property['value'] = 'Да';
            }

            if ($property['code'] === 'UPSALE' && !empty($property['value'])) {
                $property['value'] = 'Да';
            }

            if ($property['code'] === 'BOOKMATE' && !empty($property['value'])) {
                $property['value'] = 'Да';
            }

            $propertiesTmp[] = $property['name'].': '.$property['value'];
        }

        return implode(', ', $propertiesTmp);
    }

    /**
     * @param int $orderId
     * @param int $propertyId
     * @param string $propertyCode
     * @param string $propertyName
     * @param string $value
     * @return bool
     */
    public static function addOrderProperty(
        int $orderId,
        int $propertyId,
        string $propertyCode,
        string $propertyName,
        string $value
    ): bool {
        $CSaleOrderPropsValue = new \CSaleOrderPropsValue();

        $fields = array(
            'NAME' => $propertyName,
            'CODE' => $propertyCode,
            'ORDER_PROPS_ID' => $propertyId,
            'ORDER_ID' => $orderId,
            'VALUE' => $value,
        );

        $result = $CSaleOrderPropsValue->GetList(array(), array('ORDER_ID' => $orderId, 'ORDER_PROPS_ID' => $propertyId));
        if ($row = $result->Fetch()) {
            return (bool)$CSaleOrderPropsValue->Update($row['ID'], $fields);
        }

        return (bool)$CSaleOrderPropsValue->Add($fields);
    }
}
