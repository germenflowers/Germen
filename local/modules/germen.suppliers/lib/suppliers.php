<?php

namespace Germen\Suppliers;

use Bitrix\Main\Entity;
use Bitrix\Main\SystemException;

/**
 * Class SuppliersTable
 * @package Germen\Suppliers
 */
class SuppliersTable extends Entity\DataManager
{
    /**
     * @return string
     */
    public static function getTableName(): string
    {
        return 'germen_suppliers';
    }

    /**
     * @return array
     * @throws SystemException
     */
    public static function getMap(): array
    {
        return array(
            new Entity\IntegerField('id', array('primary' => true, 'autocomplete' => true)),
            new Entity\IntegerField('supplier_id', array('required' => true)),
            new Entity\IntegerField('order_id', array('required' => true)),
            new Entity\TextField(
                'products_id',
                array(
                    'required' => true,
                    'save_data_modification' => static function () {
                        return array(
                            static function ($value) {
                                return json_encode($value);
                            },
                        );
                    },
                    'fetch_data_modification' => static function () {
                        return array(
                            static function ($value) {
                                return json_decode($value, true);
                            },
                        );
                    },
                )
            ),
            new Entity\TextField(
                'compliments_id',
                array(
                    'save_data_modification' => static function () {
                        return array(
                            static function ($value) {
                                return json_encode($value);
                            },
                        );
                    },
                    'fetch_data_modification' => static function () {
                        return array(
                            static function ($value) {
                                return json_decode($value, true);
                            },
                        );
                    },
                )
            ),
            new Entity\DatetimeField('delivery_time', array('required' => true)),
            new Entity\IntegerField('paid'),
            new Entity\TextField('comment'),
            new Entity\TextField('note'),
            new Entity\TextField('status'),
            new Entity\TextField('token'),
            new Entity\TextField('url'),
        );
    }
}
