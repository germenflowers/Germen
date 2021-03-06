<?php

use \Bitrix\Main\Loader;
use \Bitrix\Main\Page\Asset;
use \Bitrix\Main\Page\AssetLocation;
use \Bitrix\Main\Text\Emoji;
use \Bitrix\Sale\Order;
use \Bitrix\Main\LoaderException;
use \Bitrix\Main\SystemException;
use \Bitrix\Main\NotImplementedException;
use \Bitrix\Main\ObjectPropertyException;
use \Bitrix\Main\ArgumentException;
use \Bitrix\Main\ArgumentNullException;

/**
 * Class onAdminSaleOrderView
 */
class onAdminSaleOrderView
{
    /**
     * @param array $params
     * @return array
     * @throws ArgumentException
     * @throws ArgumentNullException
     * @throws LoaderException
     * @throws NotImplementedException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    public static function onInit(array $params): array
    {
        self::changeCommentAndLetter((int)$params['ID']);

        return array(
            'TABSET' => 'onAdminSaleOrderView',
            'GetTabs' => array('onAdminSaleOrderView', 'getTab'),
            'ShowTab' => array('onAdminSaleOrderView', 'showTab'),
            'Action' => array('onAdminSaleOrderView', 'action'),
            'Check' => array('onAdminSaleOrderView', 'check'),
        );
    }

    /**
     * Действие после сохранения заказа.
     * Сообщение $GLOBALS['APPLICATION']->ThrowException('', 'ERROR');
     * @param array $arArgs
     * @return bool
     */
    public static function action(array $arArgs): bool
    {
        return true;
    }

    /**
     * Проверки перед сохранением.
     * @param array $arArgs
     * @return bool
     */
    public static function check(array $arArgs): bool
    {
        return true;
    }

    /**
     * @param array $arArgs
     * @return array
     */
    public static function getTab(array $arArgs): array
    {
        return array();
    }

    /**
     * @param string $divName
     * @param array $arArgs
     * @param array $bVarsFromForm
     */
    public static function showTab(string $divName, array $arArgs, array $bVarsFromForm): void
    {
        $orderId = (int)$arArgs['ID'];
    }

    /**
     * @param int $orderId
     * @throws ArgumentException
     * @throws ArgumentNullException
     * @throws LoaderException
     * @throws NotImplementedException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    private static function changeCommentAndLetter(int $orderId): void
    {
        if (!($orderId > 0 && Loader::includeModule('sale'))) {
            return;
        }

        $order = Order::load($orderId);
        if (is_null($order)) {
            return;
        }

        $properties = $order->getPropertyCollection()->getArray();

        $comment = Emoji::decode($order->getField('USER_DESCRIPTION'));
        $letter = '';

        foreach ($properties['properties'] as $property) {
            if ($property['CODE'] === 'TEXT_SCRAP') {
                $letter = Emoji::decode($property['VALUE'][0]);
            }
        }

        Asset::getInstance()->addString(
            "<script>
                BX.addCustomEvent('onAfterSaleOrderTailsLoaded', BX.delegate(function(data) {
                    let userCommentElement = $('#sale-adm-user-description-view'),
                    letterPropertyElement = $('.adm-detail-content-cell-l:contains(Текст записки:)').siblings('.adm-detail-content-cell-r'),
                    comment = '".$comment."',
                    letter = '".$letter."';

                    if (typeof userCommentElement !== 'undefined' && userCommentElement.length > 0 && comment !== '') {
                        userCommentElement.text(comment);
                    }
                    
                    if (typeof letterPropertyElement !== 'undefined' && letterPropertyElement.length > 0 && letter !== '') {
                        letterPropertyElement.html('<div>' + letter + '</div>');
                    }
                }));
            </script>",
            AssetLocation::AFTER_JS
        );
    }
}
