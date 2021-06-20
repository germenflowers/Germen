<?php

/**
 * @global CMain $APPLICATION
 */

use \Bitrix\Main\Loader;
use \Bitrix\Main\Context;
use \Germen\Couriers\Courier;
use \Germen\Couriers\Order;

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

if (!Loader::includeModule('germen.couriers')) {
    die(json_encode(array('status' => 'error', 'message' => 'Не подключен модуль germen.couriers')));
}

if (empty($post['token'])) {
    die(json_encode(array('status' => 'error', 'message' => 'Permission Denied')));
}

$result = array('status' => 'error', 'message' => '');

if ($post['action'] === 'takeOrder') {
    if (empty($post['checkOrder'])) {
        die(json_encode(array('status' => 'error', 'message' => 'Необходимо проверить состав заказа')));
    }

    $data = (new Courier())->getByToken($_REQUEST['token']);

    if ((new Order())->setStatus($data['order']['id'], 'CA')) {
        $result = array('status' => 'success', 'message' => '');
    }
}

die(json_encode($result));