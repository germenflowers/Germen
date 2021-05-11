<?php

/**
 * @global CMain $APPLICATION
 */

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}
?>
            </div>
            <div class="modal fade" id="deleteModal" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content modal-delete">
                        <div class="modal__header">
                            <h5 class="modal-delete__header">Отмена заказа: несколько позиций</h5>
                            <button class="modal-delete__close" type="button" data-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <form class="modal-delete__form">
                                <div class="modal-delete__top">
                                    <div class="modal-delete__radio">
                                        <div class="modal-delete__title">Причины</div>
                                        <div class="modal-delete__inputs">
                                            <div class="modal-delete__input">
                                                <input checked type="radio" id="not-free-cancel" name="cancelation" value="not-free">
                                                <label for="not-free-cancel">Отмена со списанием</label>
                                            </div>
                                            <div class="modal-delete__input">
                                                <input type="radio" id="free-cancel" name="cancelation" value="free">
                                                <label for="free-cancel">Отмена без списания</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-delete__textarea">
                                        <div class="modal-delete__title">Комментарий</div>
                                        <textarea class="modal-delete__textarea-input"></textarea>
                                    </div>
                                </div>
                                <div class="modal-delete__buttons">
                                    <button class="btn btn-secondary" type="button">Отправить</button>
                                    <button class="btn btn-primary" type="button" data-dismiss="modal">Отмена</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <div class="page-loader js-page-loader">
            <img src="<?=SITE_TEMPLATE_PATH?>/img/loader.gif" alt="">
        </div>
        <div class="page-overlay js-page-overlay"></div>

        <?php $APPLICATION->IncludeComponent(
            'bitrix:main.include',
            '',
            array(
                'AREA_FILE_SHOW' => 'file',
                'AREA_FILE_SUFFIX' => '',
                'EDIT_TEMPLATE' => '',
                'PATH' => SITE_TEMPLATE_PATH.'/include/footer/counters.php',
            )
        ); ?>
    </body>
</html>