<?php

/**
 * @var array $arParams
 * @var array $arResult
 */

use \Bitrix\Main\Context;

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}

$this->setFrameMode(true);

$lastLogin = '';
if (!empty($arResult['LAST_LOGIN'])) {
    $lastLogin = $arResult['LAST_LOGIN'];
} elseif (!empty($_POST['USER_EMAIL'])) {
    $lastLogin = $_POST['USER_EMAIL'];
}

$request = Context::getCurrent()->getRequest();
$post = $request->getPostList();
?>
<div class="login">
    <div class="login__logo">
        <svg width="102" height="24" aria-hidden="true">
            <use xlink:href="<?=SITE_TEMPLATE_PATH?>/img/sprite.svg#logo"></use>
        </svg>
    </div>
    <div class="login__title">Войти</div>
    <div class="login__form">
        <form
                name="system_auth_form<?=$arResult['RND']?>"
                method="post"
                action="<?=POST_FORM_ACTION_URI?>"
                class="js-authorization-form"
        >
            <input type="hidden" name="AUTH_FORM" value="Y">
            <input type="hidden" name="TYPE" value="AUTH">
            <input type="hidden" name="USER_REMEMBER" value="Y">

            <input
                    type="email"
                    name="USER_LOGIN"
                    value="<?=$lastLogin?>"
                    placeholder="e-mail"
                    required="required"
                    class="login__form-input"
            >
            <input
                    type="password"
                    name="USER_PASSWORD"
                    value=""
                    placeholder="password"
                    required="required"
                    autocomplete="off"
                    class="login__form-input"
            >
            <input type="submit" value="Войти" class="login__form-button button-black">
        </form>

        <?php if (
                !empty($post['USER_LOGIN']) &&
                !empty($post['USER_PASSWORD']) &&
                !empty($arResult['ERROR_MESSAGE']['MESSAGE'])
        ): ?>
            <div class="login__form-errors">
                <?=$arResult['ERROR_MESSAGE']['MESSAGE']?>
            </div>
        <?php endif; ?>

        <a class="login__form-remind" href="<?=$arParams['FORGOT_PASSWORD_URL']?>">Забыли пароль?</a>
    </div>
</div>