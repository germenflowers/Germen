<?php

use Bitrix\Main\Loader;
use Germen\Suppliers\Supplier;

if (empty($_REQUEST['token'])) {
    header('HTTP/1.0 403 Forbidden');
    exit;
}

require_once $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php';

if (!Loader::includeModule('germen.suppliers')) {
    header('HTTP/1.0 403 Forbidden');
    exit;
}

$supplier = new Supplier;
$data = $supplier->getByToken($_REQUEST['token']);
$statuses = $supplier->getStatusList($data['status']);

if (empty($data)) {
    header('HTTP/1.0 403 Forbidden');
    exit;
}

$modulePath = '/local/modules/germen.suppliers';
?>
<!doctype html>
<html lang="ru">
    <head>
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">

        <title>Поставщикам</title>

        <link rel="stylesheet" type="text/css" href="<?=$modulePath?>/css/style.css">

        <script type="text/javascript" src="<?=$modulePath?>/js/jquery-3-4-1.min.js"></script>
        <script type="text/javascript" src="<?=$modulePath?>/js/script.js"></script>
    </head>
    <body>
        <header>

        </header>
        <main>
            <p>Заказ №<?=$data['orderId']?></p>
            <p>Собрать до:</p>
            <p><?=$data['courierTimeFormat']?> <?=$data['courierDateFormat']?></p>
            <form name="suppliersForm" action="<?=$modulePath?>/ajax/handler.php" method="post">
                <input type="hidden" name="token" value="<?=$_REQUEST['token']?>">
                <select name="status">
                    <?php foreach ($statuses as $status): ?>
                        <option value="<?=$status['id']?>" <?=$status['selected'] ? 'selected' : ''?>>
                            <?=$status['name']?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <span class="success-message js-success-message">Статус обновлён</span>
                <span class="error-message js-error-message"></span>
            </form>

            <?php foreach ($data['products'] as $item): ?>
                <img src="<?=$item['image']?>">
                <p>Состав:</p>
                <p><?=$item['composition']?></p>
            <?php endforeach; ?>

            <p>Комментарий клиента: <?=$data['comment']?></p>

            <p>Текст Открытки:</p>
            <p><?=$data['note']?></p>

            <?php if (!empty($data['compliments'])): ?>
                <p>Комплименты:</p>
                <?php foreach ($data['compliments'] as $item): ?>
                    <img src="<?=$item['image']?>">
                    <p>Текст: <?=$item['text']?></p>
                <?php endforeach; ?>
            <?php endif; ?>
        </main>
        <footer>
            <div class="loader js-loader">
                <img src="<?=$modulePath?>/img/loader.gif">
            </div>
            <div class="overlay js-overlay"></div>
        </footer>
    </body>
</html>
