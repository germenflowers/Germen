<?php

/**
 * @global CMain $APPLICATION
 * @global CMain $USER
 */

use \Bitrix\Main\Context;
use \Bitrix\Main\Loader;
use \Germen\Admin\Rights;

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
if (!$rights->checkOrdersRights('view')) {
    die(json_encode(array('status' => 'error', 'message' => 'Forbidden')));
}

$response = array('status' => 'error');

$APPLICATION->IncludeComponent(
    'germen:admin.order.list',
    '',
    array(
        'SET_STATUS_404' => 'Y',
        'SHOW_404' => 'Y',
        'FILE_404' => '',
        'CACHE_FILTER' => 'N',
        'CACHE_GROUPS' => 'Y',
        'CACHE_TIME' => 3600,
        'CACHE_TYPE' => 'N',
    )
);

die(json_encode($response));
