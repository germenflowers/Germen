<?php

use Bitrix\Main\Event;
use Bitrix\Main\EventResult;
use Bitrix\Main\ArgumentException;
use Bitrix\Main\ArgumentNullException;
use Bitrix\Main\LoaderException;
use Bitrix\Main\NotImplementedException;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\SystemException;
use Germen\Suppliers\Supplier;
use Germen\Suppliers\Notification;

/**
 * Class eventHandlers
 */
class eventHandlers
{
    /**
     * @param Event $event
     * @throws ArgumentException
     * @throws ArgumentNullException
     * @throws LoaderException
     * @throws NotImplementedException
     * @throws SystemException
     */
    public static function OnSaleOrderSaved(Event $event): void
    {
        $order = $event->getParameter('ENTITY');

        $supplier = new Supplier;
        $result = $supplier->create($order->getId());

        if ($result['error']) {
            $order->setField('COMMENTS', $result['message']);
            $event->addResult(new EventResult(EventResult::SUCCESS, $order));
        }
    }

    /**
     * @param Event $event
     */
    public static function OnSaleOrderBeforeSaved(Event $event): void
    {

    }

    /**
     * @param Event $event
     * @throws ArgumentException
     * @throws LoaderException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    public static function OnSaleOrderPaid(Event $event): void
    {
        $order = $event->getParameter('ENTITY');

        if ($order->isPaid()) {
            $notification = new Notification;
            $notification->send($order->getId());
        }
    }
}
