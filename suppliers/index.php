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
$templatePath = '/local/templates/germen';
?>
<!doctype html>
<html lang="ru">
    <head>
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <title>Поставщикам</title>

        <link rel="preload" href="<?=$templatePath?>/fonts/ProximaNova-Regular.woff2" as="font" type="font/woff2" crossorigin>
        <link rel="preload" href="<?=$templatePath?>/fonts/ProximaNovaBold.woff2" as="font" type="font/woff2" crossorigin>
        <link rel="stylesheet" type="text/css" href="<?=$templatePath?>/css/main.css">
        <link rel="stylesheet" type="text/css" href="<?=$modulePath?>/css/style.css">

        <script type="text/javascript" src="<?=$modulePath?>/js/jquery-3-4-1.min.js"></script>
        <script type="text/javascript" src="<?=$modulePath?>/js/script.js"></script>
    </head>
    <body>
        <header class="header"></header>
        <main>
            <form class="innerform" name="suppliersForm" action="<?=$modulePath?>/ajax/handler.php" method="post">
                <input type="hidden" name="token" value="<?=$_REQUEST['token']?>">

                <h1>
                    Заказ №
                    <span><?=$data['orderId']?></span>
                </h1>
                <div class="innerform__text-block">
                    <p>Cобрать до:</p>
                    <div class="innerform__deadline">
                        <span><?=$data['courierTimeFormat']?></span>
                        <span><?=$data['courierDateFormat']?></span>
                    </div>
                </div>
                <div class="inner__sticky">
                    <select name="status">
                        <?php foreach ($statuses as $status): ?>
                            <option value="<?=$status['id']?>" <?=$status['selected'] ? 'selected' : ''?>>
                                <?=$status['name']?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <span class="success-message js-success-message">Статус обновлён</span>
                    <span class="error-message js-error-message"></span>

                    <?php foreach ($data['products'] as $item): ?>
                        <div class="innerform__order-img">
                            <img src="<?=$item['image']?>" alt="">
                        </div>
                        <div class="innerform__text-block">
                            <p>Состав:</p>
                            <p>
                                <?=$item['composition']?>
                            </p>
                        </div>
                    <?php endforeach; ?>

                    <div class="innerform__comment">
                        <p><?=$data['comment']?></p>
                    </div>
                    <div class="innerform__text-block innerform__text-block--card">
                        <p>Текст открытки:</p>
                        <p><?=$data['note']?></p>
                    </div>

                    <?php if (!empty($data['compliments'])): ?>
                        <div class="innerform__text-block innerform__text-block--extra">
                            <p>Комплименты:</p>
                            <?php foreach ($data['compliments'] as $item): ?>
                                <img src="<?=$item['image']?>" alt="">
                                <p>Текст: <?=$item['text']?></p>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </form>
        </main>
        <footer class="footer">
            <div class="loader js-loader">
                <img src="<?=$modulePath?>/img/loader.gif">
            </div>
            <div class="overlay js-overlay"></div>
        </footer>
    </body>
</html>
