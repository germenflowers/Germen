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

    /**
     * @param array $productsId
     * @return array
     */
    public function getItems($productsId): array
    {
        $items = array();

        $properties = array('IMAGES', 'COMPOSITION');
        $filter = array('IBLOCK_ID' => $this->iblockId, 'ID' => $productsId);
        $select = array('IBLOCK_ID', 'ID', 'NAME', 'PREVIEW_PICTURE', 'DETAIL_PICTURE');
        $result = \CIBlockElement::GetList(array(), $filter, false, false, $select);
        while ($rowResult = $result->getNextElement(true, false)) {
            $row = $rowResult->getFields();
            foreach ($properties as $code) {
                $row['PROPERTIES'][$code] = $rowResult->getProperty($code);
            }

            $imageId = 0;
            if (!empty((int)$row['PREVIEW_PICTURE'])) {
                $imageId = (int)$row['PREVIEW_PICTURE'];
            } elseif (!empty((int)$row['DETAIL_PICTURE'])) {
                $imageId = (int)$row['DETAIL_PICTURE'];
            } elseif (!empty((int)$row['PROPERTIES']['IMAGES']['VALUE'][0])) {
                $imageId = (int)$row['PROPERTIES']['IMAGES']['VALUE'][0];
            }

            $image = array('src' => '');
            if (!empty($imageId)) {
                $image = \CFile::ResizeImageGet(
                    $imageId,
                    array('width' => 700, 'height' => 700),
                    BX_RESIZE_IMAGE_PROPORTIONAL
                );
            }

            $items[(int)$row['ID']] = array(
                'id' => (int)$row['ID'],
                'name' => $row['NAME'],
                'image' => $image['src'],
                'composition' => $row['PROPERTIES']['COMPOSITION']['~VALUE']['TEXT'],
            );
        }

        return $items;
    }
}
