<?php

/**
 * @global CMain $APPLICATION
 * @global CMain $USER
 */

use \Bitrix\Main\Context;
use \Bitrix\Main\Loader;
use \Germen\Admin\Rights;
use \Germen\Content;

if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || $_SERVER['HTTP_X_REQUESTED_WITH'] !== 'XMLHttpRequest') {
    die('No direct script access allowed');
}

const NO_AGENT_CHECK = true;
const DisableEventsCheck = true;
const NO_KEEP_STATISTIC = true;
const NO_AGENT_STATISTIC = true;
const NOT_CHECK_PERMISSIONS = true;
const STOP_STATISTICS = true;
const PERFMON_STOP = true;
const SM_SAFE_MODE = true;

require_once $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php';

$request = Context::getCurrent()->getRequest();
$get = $request->getQueryList();
$post = $request->getPostList();

if (!$request->isAjaxRequest()) {
    die(json_encode(array('status' => 'error', 'message' => 'No direct script access allowed')));
}

if (!Loader::includeModule('sale')) {
    die(json_encode(array('status' => 'error', 'message' => 'Server Error')));
}

if ($USER->IsAuthorized() === false) {
    die(json_encode(array('status' => 'error', 'message' => 'Forbidden')));
}

$rights = new Rights();
if (!$rights->checkHistoryRights('view')) {
    die(json_encode(array('status' => 'error', 'message' => 'Forbidden')));
}

$response = array('status' => 'error');

/**
 * @return bool|mixed
 */
function getItems()
{
    global $APPLICATION;

    $elements = 20;
    if (!empty($get['elements'])) {
        $elements = (int)$get['elements'];
    }

    $APPLICATION->RestartBuffer();
    $APPLICATION->IncludeComponent(
        'germen:admin.order.history.list',
        'ajax',
        array(
            'FILTER_NAME' => 'historyFilter',
            'PAGER_ID' => 'admin-order-history',
            'PAGE_ELEMENT_COUNT' => $elements,
            'SET_STATUS_404' => 'Y',
            'SHOW_404' => 'Y',
            'FILE_404' => '',
            'DISPLAY_BOTTOM_PAGER' => 'Y',
            'DISPLAY_TOP_PAGER' => 'N',
            'PAGER_BASE_LINK' => '',
            'PAGER_BASE_LINK_ENABLE' => 'N',
            'PAGER_DESC_NUMBERING' => 'N',
            'PAGER_DESC_NUMBERING_CACHE_TIME' => 36000,
            'PAGER_PARAMS_NAME' => '',
            'PAGER_SHOW_ALL' => 'N',
            'PAGER_SHOW_ALWAYS' => 'N',
            'PAGER_TEMPLATE' => 'ajax',
            'PAGER_TITLE' => '',
            'CACHE_FILTER' => 'Y',
            'CACHE_GROUPS' => 'Y',
            'CACHE_TIME' => 3600,
            'CACHE_TYPE' => 'A',
        )
    );

    return Content::getStorage('historyItems');
}

/**
 * @param array $filter
 * @return bool
 */
function initHistoryFilter(array $filter): bool
{
    if (empty($filter)) {
        return false;
    }

    if (!empty($filter['date'])) {
        $filter['date'] = date('d.m.Y', strtotime($filter['date']));
    }

    global $historyFilter;

    $historyFilter = $filter;

    return true;
}

if ($get['action'] === 'pagenavigation') {
    if (!empty($get['filter'])) {
        initHistoryFilter($get['filter']);
    }

    $data = getItems();

    $response = array('status' => 'success', 'items' => $data['items'], 'pagenavigation' => $data['pagenavigation']);

    die(json_encode($response));
}

if ($get['action'] === 'filter') {
    if (!empty($get['filter'])) {
        initHistoryFilter($get['filter']);
    }

    $data = getItems();

    $response = array('status' => 'success', 'items' => $data['items'], 'pagenavigation' => $data['pagenavigation']);

    die(json_encode($response));
}

die(json_encode($response));
