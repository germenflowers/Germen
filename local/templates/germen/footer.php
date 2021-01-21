<?php

/**
 * @var bool $isOrderPage
 * @var bool $isArticlePage
 * @var bool $isTextPage
 * @var bool $isFavoritePage
 * @global CMain $APPLICATION
 */

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    die();
}
?>
            <?php if (!$isOrderPage): ?>
                    <?php if ($isArticlePage || $isTextPage) : ?>
                            </div>
                        </div>
                    <?php endif; ?>

                    <?php if ($isFavoritePage) : ?>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="footer">
                    <div class="footer__container">
                        <div class="promo-about">
                            <?php $APPLICATION->IncludeComponent(
                                'bitrix:main.include',
                                '',
                                array(
                                    'AREA_FILE_SHOW' => 'file',
                                    'AREA_FILE_SUFFIX' => '',
                                    'EDIT_TEMPLATE' => '',
                                    'PATH' => SITE_TEMPLATE_PATH.'/include/footer/about.php',
                                )
                            ); ?>
                        </div>
                    </div>
                    <div class="footer__main">
                        <div class="footer__container">
                            <div class="footer__content">
                                <ul class="footer__nav">
                                    <?php $APPLICATION->IncludeComponent(
                                        'bitrix:menu',
                                        'simple',
                                        array(
                                            'ALLOW_MULTI_SELECT' => 'N',
                                            'CHILD_MENU_TYPE' => '',
                                            'DELAY' => 'N',
                                            'MAX_LEVEL' => 1,
                                            'MENU_CACHE_GET_VARS' => array(),
                                            'MENU_CACHE_TIME' => 3600,
                                            'MENU_CACHE_TYPE' => 'A',
                                            'MENU_CACHE_USE_GROUPS' => 'Y',
                                            'ROOT_MENU_TYPE' => 'footer',
                                            'USE_EXT' => 'N',
                                        )
                                    ); ?>
                                </ul>
                                <p>
                                    <?php $APPLICATION->IncludeComponent(
                                        'bitrix:main.include',
                                        '',
                                        array(
                                            'AREA_FILE_SHOW' => 'file',
                                            'AREA_FILE_SUFFIX' => '',
                                            'EDIT_TEMPLATE' => '',
                                            'PATH' => SITE_TEMPLATE_PATH.'/include/footer/copyright.php',
                                        )
                                    ); ?>
                                </p>
                            </div>
                            <div class="footer__info">
                                <ul class="footer__social">
                                    <?php $APPLICATION->IncludeComponent(
                                        'bitrix:main.include',
                                        '',
                                        array(
                                            'AREA_FILE_SHOW' => 'file',
                                            'AREA_FILE_SUFFIX' => '',
                                            'EDIT_TEMPLATE' => '',
                                            'PATH' => SITE_TEMPLATE_PATH.'/include/footer/social.php',
                                        )
                                    ); ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </main>

        <?php
        include_once 'include/modals.php';
        ?>

        <script src="<?=SITE_TEMPLATE_PATH?>/js/scripts.min.js"></script>

        <script src="//mssg.me/widget/germen_flowers" async></script>
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-120818642-1"></script>
        <script>
          window.dataLayer = window.dataLayer || [];
          function gtag() {
            dataLayer.push(arguments);
          }
          gtag('js', new Date());
          gtag('config', 'UA-120818642-1');
        </script>
        <script type="text/javascript">
          (function (d, w, c) {
            (w[c] = w[c] || []).push(function () {
              try {
                w.yaCounter49237897 = new Ya.Metrika2({
                  id: 49237897,
                  clickmap: true,
                  trackLinks: true,
                  accurateTrackBounce: true,
                  webvisor: true
                });
              }
              catch (e) {
              }
            });

            var n = d.getElementsByTagName("script")[0],
              s = d.createElement("script"),
              f = function () {
                n.parentNode.insertBefore(s, n);
              };
            s.type = "text/javascript";
            s.async = true;
            s.src = "https://mc.yandex.ru/metrika/tag.js";

            if (w.opera == "[object Opera]") {
              d.addEventListener("DOMContentLoaded", f, false);
            } else {
              f();
            }
          })(document, window, "yandex_metrika_callbacks2");
        </script>
        <noscript>
            <div>
                <img src="https://mc.yandex.ru/watch/49237897" style="position:absolute; left:-9999px;" alt=""/>
            </div>
        </noscript>
        <script>
          (function (w, d, s, h, id) {
            w.roistatProjectId = id;
            w.roistatHost = h;
            var p = d.location.protocol == "https:" ? "https://" : "http://";
            var u = /^.roistat_visit=[^;]+(.)?$/.test(d.cookie) ? "/dist/module.js" : "/api/site/1.0/" + id + "/init";
            var js = d.createElement(s);
            js.charset = "UTF-8";
            js.async = 1;
            js.src = p + h + u;
            var js2 = d.getElementsByTagName(s)[0];
            js2.parentNode.insertBefore(js, js2);
          })(window, document, 'script', 'cloud.roistat.com', '9516d7eec0af9d2753f41506039006d7');
        </script>
        <script>
          !function (f, b, e, v, n, t, s) {
            if (f.fbq) return;
            n = f.fbq = function () {
              n.callMethod ?
                n.callMethod.apply(n, arguments) : n.queue.push(arguments)
            };
            if (!f._fbq) f._fbq = n;
            n.push = n;
            n.loaded = !0;
            n.version = '2.0';
            n.queue = [];
            t = b.createElement(e);
            t.async = !0;
            t.src = v;
            s = b.getElementsByTagName(e)[0];
            s.parentNode.insertBefore(t, s)
          }(window, document, 'script',
            'https://connect.facebook.net/en_US/fbevents.js');
          fbq('init', '1536440706460007');
          fbq('track', 'PageView');
        </script>
        <noscript>
            <img height="1" width="1" style="display:none" src="https://www.facebook.com/tr?id=1536440706460007&ev=PageView&noscript=1"/>
        </noscript>
        <script type="text/javascript">
          !function () {
            var t = document.createElement("script");
            t.type = "text/javascript", t.async = !0, t.src = "https://vk.com/js/api/openapi.js?157", t.onload = function () {
              VK.Retargeting.Init("VK-RTRG-265739-1DOMm"), VK.Retargeting.Hit()
            }, document.head.appendChild(t)
          }();
        </script>
        <noscript>
            <img src="https://vk.com/rtrg?p=VK-RTRG-265739-1DOMm" style="position:fixed; left:-999px;" alt=""/>
        </noscript>
    </body>
</html>