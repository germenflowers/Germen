<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$arResult['response'] = array('status' => 'error', 'message' => 'Server error');

if (!empty($_REQUEST['action']) && $_REQUEST['action'] === 'location' && !empty($_REQUEST['value'])) {
    require_once $_SERVER['DOCUMENT_ROOT'].'/bitrix/components/bitrix/sale.location.selector.search/class.php';

    $arResult['response'] = array(
        'status' => 'success',
        'locations' => processLocations($_REQUEST['value']),
    );
}

/**
 * @param string $search
 * @return array
 */
function processLocations(string $search): array
{
    $locations = array();

    $parameters = array(
        'select' => array(
            'CODE',
            'TYPE_ID',
            'VALUE' => 'ID',
            'DISPLAY' => 'NAME.NAME',
        ),
        'additionals' => array('PATH'),
        'filter' => array(
            '=PHRASE' => $search,
            '=NAME.LANGUAGE_ID' => 'ru',
            '=SITE_ID' => 's1',
        ),
        'version' => 2,
        'PAGE_SIZE' => 0,
        'PAGE' => 0,
    );
    $data = CBitrixLocationSelectorSearchComponent::processSearchRequestV2($parameters);

    foreach ($data['ITEMS'] as $item) {
        if ((int)$item['TYPE_ID'] !== 5 || (empty($item['VALUE']) && empty($item['CODE']))) {
            continue;
        }

        if (empty($item['VALUE'])) {
            $item['VALUE'] = $item['CODE'];
        }

        $country = array('id' => 0, 'name' => '');
        $region = array('id' => 0, 'name' => '');

        if (count($item['PATH']) === 1) {
            $country = processLocation($data['ETC']['PATH_ITEMS'][$item['PATH'][0]]);
            if (empty($country)) {
                continue;
            }
        } elseif (count($item['PATH']) === 2) {
            $region = processLocation($data['ETC']['PATH_ITEMS'][$item['PATH'][0]]);
            if (empty($region)) {
                continue;
            }

            $country = processLocation($data['ETC']['PATH_ITEMS'][$item['PATH'][1]]);
            if (empty($country)) {
                continue;
            }
        }

        $location = implode(', ', array_filter(array($item['DISPLAY'], $region['name'], $country['name'])));

        $locations[] = array(
//            'id' => (int)$item['VALUE'],
            'id' => $item['CODE'],
            'countryId' => (int)$country['id'],
            'regionId' => (int)$region['id'],
            'cityId' => (int)$item['VALUE'],
            'countryName' => $country['name'],
            'regionName' => $region['name'],
            'cityName' => $item['DISPLAY'],
            'location' => $location,
            'locationHtml' => getLocationHtml($search, $item['DISPLAY'], $region['name'], $country['name']),
        );
    }

    return $locations;
}

/**
 * @param array $location
 * @return array
 */
function processLocation(array $location): array
{
    if (empty($location['VALUE']) && empty($location['CODE'])) {
        return array();
    }

    if (empty($location['VALUE'])) {
        $location['VALUE'] = $location['CODE'];
    }

    return array(
        'id' => (int)$location['VALUE'],
        'name' => $location['DISPLAY'],
    );
}

/**
 * @param string $search
 * @param string $city
 * @param string $region
 * @param string $country
 * @return string
 */
function getLocationHtml(string $search, string $city, string $region, string $country): string
{
    $items = array_filter(array($city, $region, $country));
    foreach ($items as &$item) {
        $search = mb_strtolower($search);
        $search = mb_strtoupper(mb_substr($search, 0, 1)).mb_substr($search, 1, mb_strlen($search));

        if (stripos($item, $search) !== false) {
            $item = str_replace($search, '<span>'.$search.'</span>', $item);
            break;
        }
    }
    unset($item);

    return implode(', ', $items);
}
