<?php

use Bitrix\Main\ArgumentException;
use Bitrix\Main\LoaderException;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\SystemException;
use Germen\Suppliers\Notification;

/**
 * Class agents
 */
class agents
{
    /**
     * @return string
     * @throws ArgumentException
     * @throws LoaderException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    public static function sendNotification(): string
    {
        $notification = new Notification;
        $notification->send();

        return 'agents::sendNotification();';
    }
}
