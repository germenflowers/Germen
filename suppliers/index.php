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

if (empty($data)) {
    header('HTTP/1.0 403 Forbidden');
    exit;
}

$modulePath = '/local/modules/germen.suppliers';

switch ($data['status']) {
    case 'create':
        $status = 1;
        break;
    case 'send':
        $status = 2;
        break;
    case 'accepted':
        $status = 3;
        break;
    case 'assembled':
        $status = 4;
        break;
    default:
        $status = 0;
}
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
            <p>Статус:
                <span class="js-status">
                    <?php if ($status === 1 || $status === 2): ?>
                        Создан
                    <?php elseif ($status === 3): ?>
                        Принят
                    <?php elseif ($status === 4): ?>
                        Собран
                    <?php endif; ?>
                </span>
            </p>
            <p>Номер заказа: <?=$data['orderId']?></p>
            <p>Собрать до: <?=$data['courierTimeFormat']?></p>
            <p>Текст записки: <?=$data['note']?></p>
            <p>Комментарий клиента: <?=$data['comment']?></p>

            <p>Товары:</p>
            <?php foreach ($data['products'] as $item): ?>
                <p>Название: <?=$item['name']?></p>
                <p>Состав букета: <?=$item['composition']?></p>
                <img src="<?=$item['image']?>">
            <?php endforeach; ?>

            <?php if (!empty($data['compliments'])): ?>
                <p>Комплименты:</p>
                <?php foreach ($data['compliments'] as $item): ?>
                    <p>Название: <?=$item['name']?></p>
                    <p>Текст: <?=$item['text']?></p>
                    <img src="<?=$item['image']?>">
                <?php endforeach; ?>
            <?php endif; ?>

            <?php if ($status === 2 || $status === 3): ?>
                <form name="suppliersForm" action="<?=$modulePath?>/ajax/handler.php" method="post">
                    <input type="hidden" name="token" value="<?=$_REQUEST['token']?>">

                    <button class="js-accepted-button" <?=$status === 3 ? 'style="display: none;"' : ''?>>
                        Установить статус "Принят"
                    </button>
                    <button class="js-assembled-button" <?=$status === 2 ? 'style="display: none;"' : ''?>>
                        Установить статус "Собран"
                    </button>

                    <span class="success-message js-success-message">Статус обновлён</span>
                    <span class="error-message js-error-message"></span>
                </form>
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
