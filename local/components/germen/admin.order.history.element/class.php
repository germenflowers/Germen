<?php

use \Bitrix\Main\Loader;
use \Bitrix\Main\LoaderException;
use \Bitrix\Main\ArgumentException;
use \Bitrix\Iblock\Component\Tools as IblockComponentTools;
use \Bitrix\Sale\Order;
use \Germen\Tools;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

/**
 * Class GermenAdminRegions
 */
class GermenAdminOrderHistoryElement extends CBitrixComponent
{
    /**
     * @param $arParams
     * @return array
     */
    public function onPrepareComponentParams($arParams): array
    {
        if (empty($arParams['SET_STATUS_404'])) {
            $arParams['SET_STATUS_404'] = 'Y';
        }

        if (empty($arParams['SHOW_404'])) {
            $arParams['SHOW_404'] = 'Y';
        }

        if (empty($arParams['CACHE_FILTER'])) {
            $arParams['CACHE_FILTER'] = 'Y';
        }

        if (empty($arParams['CACHE_GROUPS'])) {
            $arParams['CACHE_GROUPS'] = 'Y';
        }

        if (empty($arParams['CACHE_TIME'])) {
            $arParams['CACHE_TIME'] = 3600;
        }

        if (empty($arParams['CACHE_TYPE'])) {
            $arParams['CACHE_TYPE'] = 'A';
        }

        return $arParams;
    }

    /**
     * @throws ArgumentException
     * @throws LoaderException
     */
    public function executeComponent(): void
    {
        $this->checkModules();

        if (empty((int)$this->arParams['ID'])) {
            IblockComponentTools::process404(
                '',
                $this->arParams['SET_STATUS_404'] === 'Y',
                $this->arParams['SET_STATUS_404'] === 'Y',
                $this->arParams['SHOW_404'] === 'Y',
                $this->arParams['FILE_404']
            );
        }

        if ($this->arParams['CACHE_TYPE'] === 'Y' || $this->arParams['CACHE_TYPE'] === 'A') {
            $order = $this->getOrderCached((int)$this->arParams['ID']);
        } else {
            $order = $this->getOrder((int)$this->arParams['ID']);
        }

        if (empty($order)) {
            IblockComponentTools::process404(
                '',
                $this->arParams['SET_STATUS_404'] === 'Y',
                $this->arParams['SET_STATUS_404'] === 'Y',
                $this->arParams['SHOW_404'] === 'Y',
                $this->arParams['FILE_404']
            );
        }

        $this->arResult = $order;

        $this->includeComponentTemplate();
    }

    /**
     * @return void
     * @throws LoaderException
     * @throws Exception
     */
    private function checkModules(): void
    {
        if (!Loader::includeModule('iblock')) {
            throw new \Exception('Не установлен модуль iblock');
        }

        if (!Loader::includeModule('sale')) {
            throw new \Exception('Не установлен модуль sale');
        }
    }

    /**
     * @param int $id
     * @return array
     * @throws ArgumentException
     */
    public function getOrder(int $id): array
    {
        $order = array();

        $result = Order::getList(
            array(
                'filter' => array('ID' => $id),
                'select' => array('ID'),
            )
        );
        if ($row = $result->Fetch()) {
            $order = array(
                'id' => (int)$row['ID'],
            );
        }

        return $order;
    }

    /**
     * @param int $id
     * @return array
     */
    private function getOrderCached(int $id): array
    {
        return Tools::returnResultCache(
            $this->arParams['CACHE_TIME'],
            'GermenAdminOrderHistoryElementGetOrder',
            array($this, 'getOrder'),
            $id
        );
    }
}
