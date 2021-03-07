<?php

namespace Germen;

use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;
use Bitrix\Main\ArgumentException;
use Bitrix\Sale\Basket;

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

        if (empty($params['priceCode'])) {
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

            if (!empty($row['PROPERTY_BUTTON_VALUE']) && !empty($params['buyButtons'][$row['PROPERTY_BUTTON_VALUE']])) {
                $buttonParams = $params['buyButtons'][$row['PROPERTY_BUTTON_VALUE']];
            }

            $price = new Price();
            $userGroups = $price->getUserGroups();
            $price->setPricesIdByName($params['priceCode']);
            $pricesId = $price->getPricesId();
            $prices = $price->getItemPrices((int)$row['ID'], $params['iblockId'], $pricesId, $userGroups);

            $banner = array(
                'id' => (int)$row['ID'],
                'name' => $row['NAME'],
                'title' => empty($row['PROPERTY_BANNER_TITLE_VALUE']) ? $row['NAME'] : $row['PROPERTY_BANNER_TITLE_VALUE'],
                'text' => empty($row['PROPERTY_BANNER_TEXT_VALUE']['TEXT']) ? $row['PREVIEW_TEXT'] : $row['PROPERTY_BANNER_TEXT_VALUE']['TEXT'],
                'image' => $image,
                'price' => (int)$row['PRICE_1'],
                'priceFormat' => number_format((int)$row['PRICE_1'], 0, '', ' '),
                'buttonParams' => $buttonParams,
                'prices' => $prices,
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

    /**
     * @return array
     */
    public static function getInformationBanner(): array
    {
        $banner = array();

        $filter = array(
            'IBLOCK_ID' => IBLOCK_ID__INFORMATION_BANNER,
            'CODE' => 'informatsionnyy-banner',
            'ACTIVE' => 'Y',
        );
        $select = array('IBLOCK_ID', 'ID', 'NAME', 'PREVIEW_TEXT');
        $result = \CIBlockElement::GetList(array(), $filter, false, false, $select);
        if ($row = $result->Fetch()) {
            $banner = array(
                'title' => $row['NAME'],
                'text' => $row['PREVIEW_TEXT'],
            );
        }

        return $banner;
    }

    /**
     * @return array
     */
    public static function getInformationBannerCached(): array
    {
        return Tools::returnResultCache(60 * 60, 'getInformationBanner', array(__CLASS__, 'getInformationBanner'));
    }

    /**
     * @return int
     * @throws LoaderException
     */
    public static function getCartItemsCount(): int
    {
        if (!Loader::includeModule('sale')) {
            return 0;
        }

        $count = 0;
        $filter = array('FUSER_ID' => \CSaleBasket::GetBasketUserID(), 'ORDER_ID' => false, 'DELAY' => 'N');
        $select = array('QUANTITY');
        $result = \CSaleBasket::GetList(array(), $filter, false, false, $select);
        while ($row = $result->Fetch()) {
            $count += (int)$row['QUANTITY'];
        }

        return $count;
    }

    /**
     * @return array
     * @throws ArgumentException
     */
    public static function getCartItems(): array
    {
        $cartItems = array();

        $result = Basket::getList(
            array(
                'filter' => array(
                    'FUSER_ID' => \CSaleBasket::GetBasketUserID(),
                    'ORDER_ID' => false,
                    'DELAY' => 'N',
                ),
                'select' => array('ID', 'PRODUCT_ID', 'QUANTITY'),
            )
        );
        while ($row = $result->Fetch()) {
            $cartItems[(int)$row['ID']] = array(
                'id' => (int)$row['ID'],
                'productId' => (int)$row['PRODUCT_ID'],
                'quantity' => (int)$row['QUANTITY'],
                'upsale' => false,
                'bookmate' => false,
                'subscribe' => false,
            );
        }

        $CSaleBasket = new \CSaleBasket();

        $filter = array('BASKET_ID' => array_keys($cartItems), 'CODE' => 'UPSALE', 'VALUE' => true);
        $select = array();
        $result = $CSaleBasket->GetPropsList(array(), $filter, false, false, $select);
        while ($row = $result->Fetch()) {
            $cartItems[(int)$row['BASKET_ID']]['upsale'] = true;
        }

        $filter = array('BASKET_ID' => array_keys($cartItems), 'CODE' => 'BOOKMATE', 'VALUE' => true);
        $select = array();
        $result = $CSaleBasket->GetPropsList(array(), $filter, false, false, $select);
        while ($row = $result->Fetch()) {
            $cartItems[(int)$row['BASKET_ID']]['bookmate'] = true;
        }

        $filter = array('BASKET_ID' => array_keys($cartItems), 'CODE' => 'SUBSCRIBE', 'VALUE' => true);
        $select = array();
        $result = $CSaleBasket->GetPropsList(array(), $filter, false, false, $select);
        while ($row = $result->Fetch()) {
            $cartItems[(int)$row['BASKET_ID']]['subscribe'] = true;
        }

        return $cartItems;
    }

    /**
     * @param array $items
     * @param array $imageParams
     * @return array
     */
    public static function getCartItemsData(
        array $items,
        array $imageParams = array('width' => 64, 'height' => 64)
    ): array {
        $response = array();

        $productsId = array();
        foreach ($items as $item) {
            if (!in_array((int)$item['PRODUCT_ID'], $productsId, true)) {
                $productsId[] = (int)$item['PRODUCT_ID'];
            }

            $img = array('src' => '');
            if (!empty($item['PREVIEW_PICTURE'])) {
                $img = \CFile::ResizeImageGet(
                    $item['PREVIEW_PICTURE'],
                    array('width' => $imageParams['width'], 'height' => $imageParams['height']),
                    BX_RESIZE_IMAGE_PROPORTIONAL
                );
            } elseif (!empty($item['DETAIL_PICTURE'])) {
                $img = \CFile::ResizeImageGet(
                    $item['DETAIL_PICTURE'],
                    array('width' => 64, 'height' => 64),
                    BX_RESIZE_IMAGE_PROPORTIONAL
                );
            }

            $cover = '';
            foreach ($item['PROPS'] as $property) {
                if ($property['CODE'] === 'COVER') {
                    $cover = $property['VALUE'];
                }
            }

            $upsale = false;
            foreach ($item['PROPS'] as $property) {
                if ($property['CODE'] === 'UPSALE') {
                    $upsale = true;
                }
            }

            $bookmate = false;
            foreach ($item['PROPS'] as $property) {
                if ($property['CODE'] === 'BOOKMATE') {
                    $bookmate = true;
                }
            }

            $subscribe = false;
            foreach ($item['PROPS'] as $property) {
                if ($property['CODE'] === 'SUBSCRIBE') {
                    $subscribe = true;
                }
            }

            $subscribeParams = array();
            if ($subscribe) {
                foreach ($item['PROPS'] as $property) {
                    if ($property['CODE'] === 'TYPE') {
                        $subscribeParams['type'] = $property['VALUE'];
                    }

                    if ($property['CODE'] === 'DELIVERY') {
                        $subscribeParams['delivery'] = $property['VALUE'];
                    }

                    if ($property['CODE'] === 'SIZE') {
                        $subscribeParams['size'] = $property['VALUE'];
                    }
                }
            }

            if ($subscribeParams['type'] === 'Монобукеты') {
                $img = array('src' => SITE_TEMPLATE_PATH.'/img/mono.jpg');
            } elseif ($subscribeParams['type'] === 'Составные букеты') {
                $img = array('src' => SITE_TEMPLATE_PATH.'/img/compose.jpg');
            }

            $price = (int)$item['PRICE'];
            $oldPrice = (int)$item['BASE_PRICE'];
            $sum = (int)$item['SUM_VALUE'];
            $oldSum = (int)$item['SUM_FULL_PRICE'];
            if (empty($sum)) {
                $sum = (int)$item['SUM_NUM'];
                $oldSum = (int)$item['SUM_BASE'];
            }

            $response[] = array(
                'id' => (int)$item['ID'],
                'productId' => (int)$item['PRODUCT_ID'],
                'name' => $item['NAME'],
                'image' => $img['src'],
                'link' => $item['DETAIL_PAGE_URL'],
                'price' => $price,
                'priceFormat' => number_format($price, 0, '', '&nbsp;'),
                'oldPrice' => $oldPrice,
                'oldPriceFormat' => number_format($oldPrice, 0, '', '&nbsp;'),
                'sum' => $sum,
                'sumFormat' => number_format($sum, 0, '', '&nbsp;'),
                'oldSum' => $oldSum,
                'oldSumFormat' => number_format($oldSum, 0, '', '&nbsp;'),
                'quantity' => (int)$item['QUANTITY'],
                'canBuy' => $item['CAN_BUY'] === 'Y',
                'cover' => $cover,
                'upsale' => $upsale,
                'bookmate' => $bookmate,
                'subscribe' => !empty($subscribeParams),
                'subscribeParams' => $subscribeParams,
            );
        }

        /*
        if (!empty($productsId)) {
            $filter = array('ID' => $productsId);
            $select = array('IBLOCK_ID', 'ID', 'PROPERTY_CML2_ARTICLE');
            $result = \CIBlockElement::GetList(array(), $filter, false, false, $select);
            while ($row = $result->Fetch()) {
                foreach ($response as $key => $item) {
                    if ($item['productId'] === (int)$row['ID']) {
                        $response[$key]['vendorCode'] = $row['PROPERTY_CML2_ARTICLE_VALUE'];
                        break;
                    }
                }
            }
        }
        */

        return $response;
    }

    /**
     * @return array
     */
    public static function getUpsaleBookmateProducts(): array
    {
        $products = array();

        global $USER;

        $order = array('SORT' => 'ASC');
        $filter = array('IBLOCK_ID' => IBLOCK_ID__UPSALE, 'ACTIVE' => 'Y');
        $select = array(
            'IBLOCK_ID',
            'ID',
            'NAME',
            'PREVIEW_TEXT',
            'PREVIEW_PICTURE',
            'PROPERTY_IS_BOOKMATE',
        );
        $result = \CIBlockElement::GetList($order, $filter, false, false, $select);
        while ($row = $result->fetch()) {
            $price = \CCatalogProduct::GetOptimalPrice($row['ID'], 1, $USER->GetUserGroupArray());

            $image['src'] = array();
            if (!empty($row['PREVIEW_PICTURE'])) {
                $image = \CFile::ResizeImageGet(
                    $row['PREVIEW_PICTURE'],
                    array('width' => 174, 'height' => 174),
                    BX_RESIZE_IMAGE_PROPORTIONAL
                );
            }

            $item = array(
                'id' => (int)$row['ID'],
                'name' => $row['NAME'],
                'text' => $row['PREVIEW_TEXT'],
                'price' => (int)$price['DISCOUNT_PRICE'],
                'image' => $image['src'],
            );

            if ($row['PROPERTY_IS_BOOKMATE_VALUE'] === 'Y') {
                $products['bookmateProducts'][] = $item;
            } else {
                $products['upsaleProducts'][] = $item;
            }
        }

        return $products;
    }
}