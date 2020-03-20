<?php

if (!isset($_SERVER['HTTP_X_REQUESTED_WITH']) || $_SERVER['HTTP_X_REQUESTED_WITH'] !== 'XMLHttpRequest') {
    die('No direct script access allowed');
}

use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use Germen\Suppliers\Supplier;

define('NO_KEEP_STATISTIC', true);
define('NO_AGENT_CHECK', true);

require_once $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php';

$request = Application::getInstance()->getContext()->getRequest();
if (!$request->isAjaxRequest()) {
    die(json_encode(array('error' => true, 'No direct script access allowed')));
}

$post = $request->getPostList()->toArray();

if (empty($post['token'])) {
    die(json_encode(array('error' => true, 'message' => 'Permission Denied')));
}

if ($post['action'] !== 'changeStatus') {
    die(json_encode(array('error' => true, 'message' => 'Method Not Allowed')));
}

if (!Loader::includeModule('germen.suppliers')) {
    die(json_encode(array('error' => true, 'Не подключен модуль germen.suppliers')));
}

$supplier = new Supplier;
$data = $supplier->getByToken($post['token']);

if (empty($data)) {
    die(json_encode(array('error' => true, 'message' => 'Permission denied')));
}

$result = array('error' => false, 'message' => '');

if ($post['action'] === 'changeStatus') {
    if (empty($post['status'])) {
        die(json_encode(array('error' => true, 'message' => 'Bad Request')));
    }

    if ($post['status'] === 'accepted') {
        $result = $supplier->setStatusAccepted($data['id']);
    }

    if ($post['status'] === 'assembled') {
        $result = $supplier->setStatusAssembled($data['id']);
    }
}

die(json_encode($result));