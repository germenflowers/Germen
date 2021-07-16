<?php

namespace PDV\Handlers;

use \Bitrix\Main\Event;
use \Bitrix\Main\SystemException;
use \Bitrix\Main\ArgumentException;
use \Bitrix\Main\ObjectPropertyException;
use \Bitrix\Sale\Internals\OrderPropsValueTable;
use \PDV\Smsaero;

/**
 * Class Sale
 * @package PDV\Handlers
 */
class Sale
{
    public const link_rate = '';

    /**
     * @param Event $event
     * @throws ArgumentException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    public function changeStatus(Event $event): void
    {
        $order = $event->getParameter("ENTITY");
        $value = $event->getParameter("VALUE");

        $orderId = $order->GetId();

        $phone = '';
        $rsPropsValue = OrderPropsValueTable::getList(
            array(
                'filter' => array('ORDER_ID' => $orderId, 'CODE' => 'PHONE'),
                'select' => array('CODE', 'VALUE'),
            )
        );
        if ($arPropsValue = $rsPropsValue->fetch()) {
            $phone = trim(str_replace(array('+', '-', '(', ')', ' '), '', $arPropsValue['VALUE']));
        }

        if (!empty($phone)) {
            $textSms = '';

            $rsElem = \CIBlockElement::GetList(
                array('sort' => 'asc', 'id' => 'desc'),
                array('IBLOCK_ID' => IBLOCK_ID__SMS, '=CODE' => $value),
                false,
                array('nPageSize' => 1),
                array('PREVIEW_TEXT')
            );
            if ($arElem = $rsElem->GetNext()) {
                $textSms = str_replace(
                    array('#ORDER_ID#', '#LINK#', '#PROMO_CODE#', '#COUNT_SALE#', '#PROMO_DATE#'),
                    array($orderId, self::link_rate, '#PROMO_CODE#', '#COUNT_SALE#', '#PROMO_DATE#'),
                    $arElem['PREVIEW_TEXT']
                );
            }

            if (!empty($textSms)) {
                if ($handle = fopen($_SERVER["DOCUMENT_ROOT"].'/upload/logChangeOrder.txt', 'ab+')) {
                    fwrite($handle, $phone." ".$textSms."\n");
                    fclose($handle);
                }

                (new Smsaero)->sendSMS($phone, $textSms);
            }
        }
    }

    /**
     * @param Event $event
     * @throws ArgumentException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    public function orderCancel(Event $event): void
    {
        $order = $event->getParameter("ENTITY");
        $cancel = $order->getField("CANCELED");

        $orderId = $order->GetId();

        $phone = '';
        $rsPropsValue = OrderPropsValueTable::getList(
            array(
                'filter' => array('ORDER_ID' => $orderId, 'CODE' => 'PHONE'),
                'select' => array('CODE', 'VALUE'),
            )
        );
        if ($arPropsValue = $rsPropsValue->fetch()) {
            $phone = trim(str_replace(array('+', '-', '(', ')', ' '), '', $arPropsValue['VALUE']));
        }

        if (!empty($phone) && $cancel === 'Y') {
            $textSms = '';

            $rsElem = \CIBlockElement::GetList(
                array('sort' => 'asc', 'id' => 'desc'),
                array('IBLOCK_ID' => IBLOCK_ID__SMS, '=CODE' => 'CANCELED'),
                false,
                array('nPageSize' => 1),
                array('PREVIEW_TEXT')
            );
            if ($arElem = $rsElem->GetNext()) {
                $textSms = str_replace(
                    array('#ORDER_ID#', '#LINK#', '#PROMO_CODE#', '#COUNT_SALE#', '#PROMO_DATE#'),
                    array($orderId, self::link_rate, '#PROMO_CODE#', '#COUNT_SALE#', '#PROMO_DATE#'),
                    $arElem['PREVIEW_TEXT']
                );
            }

            if ($handle = fopen($_SERVER["DOCUMENT_ROOT"].'/upload/logChangeOrder.txt', 'ab+')) {
                fwrite($handle, $phone." ".$textSms."\n");
                fclose($handle);
            }

            (new Smsaero)->sendSMS($phone, $textSms);
        }
    }

    /**
     * @param Event $event
     * @throws ArgumentException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    public function orderPaid(Event $event): void
    {
        $order = $event->getParameter("ENTITY");
        $paid = $order->getField("PAYED");

        $orderId = $order->GetId();

        $phone = '';
        $rsPropsValue = OrderPropsValueTable::getList(
            array(
                'filter' => array('ORDER_ID' => $orderId, 'CODE' => 'PHONE'),
                'select' => array('CODE', 'VALUE'),
            )
        );
        if ($arPropsValue = $rsPropsValue->fetch()) {
            $phone = trim(str_replace(array('+', '-', '(', ')', ' '), '', $arPropsValue['VALUE']));
        }

        if (!empty($phone) && $paid === 'Y') {
            $textSms = '';

            $rsElem = \CIBlockElement::GetList(
                array('sort' => 'asc', 'id' => 'desc'),
                array('IBLOCK_ID' => IBLOCK_ID__SMS, '=CODE' => 'PAYED'),
                false,
                array('nPageSize' => 1),
                array('PREVIEW_TEXT')
            );
            if ($arElem = $rsElem->GetNext()) {
                $textSms = str_replace(
                    array('#ORDER_ID#', '#LINK#', '#PROMO_CODE#', '#COUNT_SALE#', '#PROMO_DATE#'),
                    array($orderId, self::link_rate, '#PROMO_CODE#', '#COUNT_SALE#', '#PROMO_DATE#'),
                    $arElem['PREVIEW_TEXT']
                );
            }

            if ($handle = fopen($_SERVER["DOCUMENT_ROOT"].'/upload/logChangeOrder.txt', 'ab+')) {
                fwrite($handle, $phone." ".$textSms."\n");
                fclose($handle);
            }

            (new Smsaero)->sendSMS($phone, $textSms);
        }
    }
}
