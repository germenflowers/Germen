<?php

namespace Germen\Suppliers;

use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;
use Bitrix\Main\SystemException;

/**
 * Class Product
 * @package Germen\Suppliers
 */
class Product
{
    private $iblockId = IBLOCK_ID__CATALOG;

    /**
     * Product constructor.
     * @throws SystemException
     * @throws LoaderException
     */
    public function __construct()
    {
        if (!Loader::includeModule('iblock')) {
            throw new SystemException('Не подключен модуль iblock');
        }
    }

    /**
     * @param array $productsId
     * @return array
     */
    public function getItemsId($productsId): array
    {
        $itemsId = array();

        $filter = array('IBLOCK_ID' => $this->iblockId, 'ID' => $productsId);
        $select = array('IBLOCK_ID', 'ID');
        $result = \CIBlockElement::GetList(array(), $filter, false, false, $select);
        while ($row = $result->Fetch()) {
            $itemsId[] = (int)$row['ID'];
        }

        return $itemsId;
    }

    /**
     * @param array $productsId
     * @return array
     */
    public function getSuppliersProductsId($productsId): array
    {
        $suppliersProductsId = array();

        $filter = array('IBLOCK_ID' => $this->iblockId, 'ID' => $productsId);
        $select = array('IBLOCK_ID', 'ID', 'PROPERTY_SUPPLIER');
        $result = \CIBlockElement::GetList(array(), $filter, false, false, $select);
        while ($row = $result->Fetch()) {
            if (empty((int)$row['PROPERTY_SUPPLIER_VALUE'])) {
                continue;
            }

            $suppliersProductsId[(int)$row['PROPERTY_SUPPLIER_VALUE']][] = (int)$row['ID'];
        }

        return $suppliersProductsId;
    }
}
