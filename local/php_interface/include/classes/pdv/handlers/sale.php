<?
namespace PDV\Handlers;

use \Bitrix\Main\Loader,
    \Bitrix\Main\Event,
    \Bitrix\Sale\Internals\OrderPropsValueTable;

class Sale {
    const link_rate = '';

    public function changeStatus( Event $event ) {
        $order = $event->getParameter("ENTITY");
        $value = $event->getParameter("VALUE");

        $orderId = $order->GetId();

        $phone = '';
        $rsPropsValue = OrderPropsValueTable::getList(
            array(
                'filter' => array('ORDER_ID' => $orderId, 'CODE' => 'PHONE'),
                'select' => array('CODE', 'VALUE')
            )
        );
        if ( $arPropsValue = $rsPropsValue->fetch() )
            $phone = trim(str_replace(array('+','-','(',')',' '), '', $arPropsValue['VALUE']));

        if ( !empty($phone) ) {
            $textSms = '';

            $rsElem = \CIBlockElement::GetList(
                array('sort' => 'asc', 'id' => 'desc'),
                array('IBLOCK_ID' => IBLOCK_ID__SMS, '=CODE' => $value),
                false,
                array('nPageSize' => 1),
                array('PREVIEW_TEXT')
            );
            if ( $arElem = $rsElem->GetNext() ) {
                $textSms = str_replace(
                    array('#ORDER_ID#', '#LINK#', '#PROMO_CODE#', '#COUNT_SALE#', '#PROMO_DATE#'),
                    array($orderId, self::link_rate, '#PROMO_CODE#', '#COUNT_SALE#', '#PROMO_DATE#'),
                    $arElem['PREVIEW_TEXT']
                );
            }

            if ( !empty($textSms) ) {
                if ( $handle = fopen($_SERVER["DOCUMENT_ROOT"].'/upload/logChangeOrder.txt', 'a+') ) {
                    fwrite($handle, $phone." ".$textSms."\n");
                    fclose($handle);
                }

                \PDV\Smsaero::sendSMS( $phone, $textSms );
            }
        }
    }

    public function orderCancel( Event $event ) {
        $order = $event->getParameter("ENTITY");
        $cancel = $order->getField("CANCELED");

        $orderId = $order->GetId();

        $phone = '';
        $rsPropsValue = OrderPropsValueTable::getList(
            array(
                'filter' => array('ORDER_ID' => $orderId, 'CODE' => 'PHONE'),
                'select' => array('CODE', 'VALUE')
            )
        );
        if ( $arPropsValue = $rsPropsValue->fetch() )
            $phone = trim(str_replace(array('+','-','(',')',' '), '', $arPropsValue['VALUE']));

        if ( !empty($phone) && $cancel == 'Y' ) {
            $rsElem = \CIBlockElement::GetList(
                array('sort' => 'asc', 'id' => 'desc'),
                array('IBLOCK_ID' => IBLOCK_ID__SMS, '=CODE' => 'CANCELED'),
                false,
                array('nPageSize' => 1),
                array('PREVIEW_TEXT')
            );
            if ( $arElem = $rsElem->GetNext() ) {
                $textSms = str_replace(
                    array('#ORDER_ID#', '#LINK#', '#PROMO_CODE#', '#COUNT_SALE#', '#PROMO_DATE#'),
                    array($orderId, self::link_rate, '#PROMO_CODE#', '#COUNT_SALE#', '#PROMO_DATE#'),
                    $arElem['PREVIEW_TEXT']
                );
            }

            if ( $handle = fopen($_SERVER["DOCUMENT_ROOT"].'/upload/logChangeOrder.txt', 'a+') ) {
                fwrite($handle, $phone." ".$textSms."\n");
                fclose($handle);
            }

            \PDV\Smsaero::sendSMS( $phone, $textSms );
        }
    }

    public function orderPaid( Event $event ) {
        $order = $event->getParameter("ENTITY");
        $paid = $order->getField("PAYED");

        $orderId = $order->GetId();

        $phone = '';
        $rsPropsValue = OrderPropsValueTable::getList(
            array(
                'filter' => array('ORDER_ID' => $orderId, 'CODE' => 'PHONE'),
                'select' => array('CODE', 'VALUE')
            )
        );
        if ( $arPropsValue = $rsPropsValue->fetch() )
            $phone = trim(str_replace(array('+','-','(',')',' '), '', $arPropsValue['VALUE']));

        if ( !empty($phone) && $paid == 'Y' ) {
            $rsElem = \CIBlockElement::GetList(
                array('sort' => 'asc', 'id' => 'desc'),
                array('IBLOCK_ID' => IBLOCK_ID__SMS, '=CODE' => 'PAYED'),
                false,
                array('nPageSize' => 1),
                array('PREVIEW_TEXT')
            );
            if ( $arElem = $rsElem->GetNext() ) {
                $textSms = str_replace(
                    array('#ORDER_ID#', '#LINK#', '#PROMO_CODE#', '#COUNT_SALE#', '#PROMO_DATE#'),
                    array($orderId, self::link_rate, '#PROMO_CODE#', '#COUNT_SALE#', '#PROMO_DATE#'),
                    $arElem['PREVIEW_TEXT']
                );
            }

            if ( $handle = fopen($_SERVER["DOCUMENT_ROOT"].'/upload/logChangeOrder.txt', 'a+') ) {
                fwrite($handle, $phone." ".$textSms."\n");
                fclose($handle);
            }

            \PDV\Smsaero::sendSMS( $phone, $textSms );
        }
    }
}