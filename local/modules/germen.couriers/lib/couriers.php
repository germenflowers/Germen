<?php

namespace Germen\Couriers;

use Bitrix\Main\Entity;
use Bitrix\Main\SystemException;

/**
 * Class CouriersTable
 * @package Germen\Couriers
 */
class CouriersTable extends Entity\DataManager
{
    /**
     * @return string
     */
    public static function getTableName(): string
    {
        return 'germen_couriers';
    }

    /**
     * @return array
     * @throws SystemException
     */
    public static function getMap(): array
    {
        return array(
            new Entity\IntegerField('id', array('primary' => true, 'autocomplete' => true)),
            new Entity\IntegerField('courier_id', array('required' => true)),
            new Entity\IntegerField('order_id', array('required' => true)),
            new Entity\DatetimeField('date_create'),
            new Entity\TextField('phone'),
            new Entity\TextField('email'),
            new Entity\TextField('token'),
            new Entity\TextField('url'),
            new Entity\IntegerField('send_notification'),
        );
    }
}
