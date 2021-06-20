<?php

use \Bitrix\Main\Event;
use \Bitrix\Main\EventResult;
use \Bitrix\Sale\ResultError;
use \Germen\Couriers\Iblock\Courier as IblockCourier;
use \Germen\Couriers\Courier;

/**
 * Class germenCouriersEventHandlers
 */
class germenCouriersEventHandlers
{
    /**
     * @param Event $event
     * @return EventResult
     */
    public static function OnSaleOrderBeforeSaved(Event $event): EventResult
    {
        return self::onSetStatusCourier($event);
    }

    /**
     * @param Event $event
     * @return EventResult
     */
    private static function onSetStatusCourier(Event $event): EventResult
    {
        $order = $event->getParameter('ENTITY');
        $statusId = $order->getField('STATUS_ID');
        $propertyCollection = $order->getPropertyCollection();
        $properties = $propertyCollection->getArray();

        /*
        Проверка админки
        if ($_REQUEST['action'] === 'saveStatus' || $_REQUEST['sale_order_edit_active_tab'] === 'tab_order') {
        }
        */

        if ($statusId === 'SC') {
            if (!$order->isPaid()) {
                return new EventResult(
                    EventResult::ERROR,
                    new ResultError(
                        'Заказ не оплачен',
                        'SALE_EVENT_NOT_PAID'
                    )
                );
            }

            $courierId = 0;
            foreach ($properties['properties'] as $property) {
                if ($property['CODE'] === 'COURIER') {
                    $courierId = (int)$property['VALUE'][0];
                }
            }

            if (empty($courierId)) {
                return new EventResult(
                    EventResult::ERROR,
                    new ResultError(
                        'Не указан курьер',
                        'SALE_EVENT_EMPTY_COURIER'
                    )
                );
            }

            if (empty((new IblockCourier())->getById($courierId))) {
                return new EventResult(
                    EventResult::ERROR,
                    new ResultError(
                        'Указанный курьер не существует',
                        'SALE_EVENT_NO_EXIST_COURIER'
                    )
                );
            }

            $courier = new Courier();

            try {
                $courier->create((int)$order->getId());
            } catch (Exception $e) {
                return new EventResult(
                    EventResult::ERROR,
                    new ResultError(
                        $e->getMessage(),
                        'SALE_EVENT_CREATE_COURIER'
                    )
                );
            }

            try {
                $courier->send((int)$order->getId());
            } catch (Exception $e) {
                return new EventResult(
                    EventResult::ERROR,
                    new ResultError(
                        $e->getMessage(),
                        'SALE_EVENT_SEND_COURIER'
                    )
                );
            }
        }

        return new EventResult(
            EventResult::SUCCESS, null
        );
    }
}
