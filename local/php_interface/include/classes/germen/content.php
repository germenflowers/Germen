<?php

namespace Germen;

/**
 * Class Content
 * @package Germen
 */
class Content
{
    /**
     * Content constructor.
     */
    public function __construct()
    {
    }

    /**
     * @param array $params
     * @return array
     */
    public static function getBanner(array $params): array
    {
        $banner = array();

        if (empty($params['iblockId'])) {
            return $banner;
        }

        if (empty($params['defaultButton'])) {
            return $banner;
        }

        $order = array('sort' => 'asc', 'id' => 'desc');
        $filter = array(
            'IBLOCK_ID' => $params['iblockId'],
            'ACTIVE' => 'Y',
            '=AVAILABLE' => 'Y',
            '>PRICE' => 0,
            '!PROPERTY_BANNER' => false,
        );

        if ($params['popular']) {
            $filter['!PROPERTY_POPULAR'] = false;
        } elseif (!empty($params['sectionId'])) {
            $filter['IBLOCK_SECTION_ID'] = $params['sectionId'];
        }

        $select = array(
            'IBLOCK_ID',
            'ID',
            'NAME',
            'PRICE_1',
            'PREVIEW_PICTURE',
            'PROPERTY_BANNER_TITLE',
            'PROPERTY_BANNER_IMG',
            'PROPERTY_BANNER_TEXT',
            'PROPERTY_BUTTON',
        );
        $result = \CIBlockElement::GetList($order, $filter, false, false, $select);
        if ($row = $result->Fetch()) {
            $image = array('src' => '');
            if (!empty($row['PROPERTY_BANNER_IMG_VALUE'])) {
                $image = \CFile::ResizeImageGet(
                    $row['PROPERTY_BANNER_IMG_VALUE'],
                    array('width' => 1400, 'height' => 376),
                    BX_RESIZE_IMAGE_PROPORTIONAL
                );
            } elseif (!empty($row['PREVIEW_PICTURE'])) {
                $image = \CFile::ResizeImageGet(
                    $row['PREVIEW_PICTURE'],
                    array('width' => 1400, 'height' => 376),
                    BX_RESIZE_IMAGE_PROPORTIONAL
                );
            }

            $buttonParams = $params['defaultButton'];
            if (!empty($item['PROPERTY_BUTTON_VALUE']) && !empty($buyButtons[$item['PROPERTY_BUTTON_VALUE']])) {
                $buttonParams = $buyButtons[$item['PROPERTY_BUTTON_VALUE']];
            }

            $banner = array(
                'id' => (int)$row['ID'],
                'name' => $row['NAME'],
                'title' => empty($row['PROPERTY_BANNER_TITLE_VALUE']) ? $row['NAME'] : $row['PROPERTY_BANNER_TITLE_VALUE'],
                'text' => empty($row['PROPERTY_BANNER_TEXT_VALUE']['TEXT']) ? $row['PREVIEW_TEXT'] : $row['PROPERTY_BANNER_TEXT_VALUE']['TEXT'],
                'image' => $image,
                'price' => (int)$row['PRICE_1'],
                'priceFormat' => number_format((int)$row['PRICE_1'], 0, '', ' '),
                'buttonParams' => $buttonParams,
            );
        }

        return $banner;
    }

    /**
     * @param array $params
     * @return array
     */
    public static function getBannerCached(array $params): array
    {
        return Tools::returnResultCache(
            60 * 60,
            'getBanner'.implode('|', $params),
            array(__CLASS__, 'getBanner'),
            $params
        );
    }
}