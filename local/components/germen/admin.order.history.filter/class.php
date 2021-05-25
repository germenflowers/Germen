<?php

use \Bitrix\Main\Loader;
use \Bitrix\Main\LoaderException;
use \Germen\Tools;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

/**
 * Class GermenAdminOrderHistoryFilter
 */
class GermenAdminOrderHistoryFilter extends CBitrixComponent
{
    private $nav;

    /**
     * @param array $arParams
     * @return array
     */
    public function onPrepareComponentParams($arParams): array
    {
        if (empty($arParams['FILTER_NAME'])) {
            $arParams['FILTER_NAME'] = 'filter';
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
     * @throws LoaderException
     */
    public function executeComponent(): void
    {
        $this->checkModules();

        $this->arResult['filter'] = $this->initFilter();

        if ($this->arParams['CACHE_TYPE'] === 'Y' || $this->arParams['CACHE_TYPE'] === 'A') {
            $this->arResult['statuses'] = $this->getStatusesCached();
        } else {
            $this->arResult['statuses'] = $this->getStatuses();
        }

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
     * @return array
     */
    public function getStatuses(): array
    {
        $statuses = array();

        $order = array('SORT' => 'ASC');
        $filter = array('TYPE' => 'O', 'LID' => 'ru');
        $select = array('ID', 'NAME');
        $result = \CSaleStatus::GetList($order, $filter, false, false, $select);
        while ($row = $result->Fetch()) {
            $statuses[$row['ID']] = array(
                'id' => $row['ID'],
                'name' => $row['NAME'],
            );
        }

        return $statuses;
    }

    /**
     * @return array
     */
    private function getStatusesCached(): array
    {
        return Tools::returnResultCache(
            $this->arParams['CACHE_TIME'],
            'GermenAdminOrderHistoryFilterGetStatuses',
            array($this, 'getStatuses')
        );
    }

    /**
     * @return array
     */
    private function initFilter(): array
    {
        if (empty($this->arParams['FILTER_NAME'])) {
            return array();
        }

        global ${$this->arParams['FILTER_NAME']};

        if (empty(${$this->arParams['FILTER_NAME']})) {
            return array();
        }

        if (isset(${$this->arParams['FILTER_NAME']}['priceMin'])) {
            ${$this->arParams['FILTER_NAME']}['priceMin'] = (int)${$this->arParams['FILTER_NAME']}['priceMin'];
        }

        if (isset(${$this->arParams['FILTER_NAME']}['priceMax'])) {
            ${$this->arParams['FILTER_NAME']}['priceMax'] = (int)${$this->arParams['FILTER_NAME']}['priceMax'];
        }

        return ${$this->arParams['FILTER_NAME']};
    }
}
