<?php

namespace Germen\Suppliers;

use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;
use Bitrix\Main\SystemException;

/**
 * Class Compliment
 * @package Germen\Suppliers
 */
class Compliment
{
    private $iblockId = IBLOCK_ID__UPSALE;

    /**
     * Compliment constructor.
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
     * @param array $complimentsId
     * @return array
     */
    public function getItems($complimentsId): array
    {
        $items = array();

        $filter = array('IBLOCK_ID' => $this->iblockId, 'ID' => $complimentsId);
        $select = array('IBLOCK_ID', 'ID', 'NAME', 'PREVIEW_PICTURE', 'PREVIEW_TEXT');
        $result = \CIBlockElement::GetList(array(), $filter, false, false, $select);
        while ($row = $result->Fetch()) {
            $imageId = 0;
            if (!empty((int)$row['PREVIEW_PICTURE'])) {
                $imageId = (int)$row['PREVIEW_PICTURE'];
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
                'text' => $row['PREVIEW_TEXT'],
            );
        }

        return $items;
    }
}
