<?php

require_once $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php';

global $APPLICATION;
global $USER;

if ($USER->IsAdmin()) {
    if (!CModule::IncludeModule('iblock')) {
        echo 'Не удалось подключить модуль iblock<br>';
        die;
    }

    if (!CModule::IncludeModule('catalog')) {
        echo 'Не удалось подключить модуль catalog<br>';
        die;
    }

    if (!CModule::IncludeModule('sale')) {
        echo 'Не удалось подключить модуль sale<br>';
        die;
    }

    $CIBlockType = new CIBlockType;
    $iblockTypes = array(
        array(
            'ID' => 'flower_subscription',
            'SORT' => 100,
            'SECTIONS' => 'Y',
            'LANG' => array(
                'ru' => array(
                    'NAME' => 'Подписка на цветы',
                ),
                'en' => array(
                    'NAME' => 'Подписка на цветы',
                ),
            ),
        ),
    );
    foreach ($iblockTypes as $fields) {
        $filter = array('ID' => $fields['ID']);
        $result = CIBlockType::GetList(array(), $filter);
        if ($row = $result->fetch()) {
            echo '"'.$row['ID'].'" уже существует <br>';
        } elseif ($CIBlockType->Add($fields)) {
            echo 'Успешно создан тип инфоблоков "'.$fields['ID'].'" <br>';
        } else {
            echo 'Не удалось создать тип инфоблоков "'.$fields['ID'].'" <br>';
            echo 'Error: '.$CIBlockType->LAST_ERROR.'<br>';
        }
    }

    $CIBlock = new CIBlock;
    $iblocks = array(
        array(
            'IBLOCK_TYPE_ID' => 'flower_subscription',
            'LID' => 's1',
            'CODE' => 'flower_subscription_slider',
            'NAME' => 'Слайдер',
            'LIST_PAGE_URL' => '#SITE_DIR#/',
            'SECTION_PAGE_URL' => '#SITE_DIR#/',
            'DETAIL_PAGE_URL' => '#SITE_DIR#/',
            'INDEX_ELEMENT' => 'N',
            'INDEX_SECTION' => 'N',
            'GROUP_ID' => array(2 => 'R'),
        ),
        array(
            'IBLOCK_TYPE_ID' => 'flower_subscription',
            'LID' => 's1',
            'CODE' => 'mono_bouquets_slider',
            'NAME' => 'Слайдер (Монобукеты)',
            'LIST_PAGE_URL' => '#SITE_DIR#/',
            'SECTION_PAGE_URL' => '#SITE_DIR#/',
            'DETAIL_PAGE_URL' => '#SITE_DIR#/',
            'INDEX_ELEMENT' => 'N',
            'INDEX_SECTION' => 'N',
            'GROUP_ID' => array(2 => 'R'),
        ),
        array(
            'IBLOCK_TYPE_ID' => 'flower_subscription',
            'LID' => 's1',
            'CODE' => 'composite_bouquets_slider',
            'NAME' => 'Слайдер (Составные букеты)',
            'LIST_PAGE_URL' => '#SITE_DIR#/',
            'SECTION_PAGE_URL' => '#SITE_DIR#/',
            'DETAIL_PAGE_URL' => '#SITE_DIR#/',
            'INDEX_ELEMENT' => 'N',
            'INDEX_SECTION' => 'N',
            'GROUP_ID' => array(2 => 'R'),
        ),
        array(
            'IBLOCK_TYPE_ID' => 'flower_subscription',
            'LID' => 's1',
            'CODE' => 'flower_subscription_faq',
            'NAME' => 'Частые вопросы',
            'LIST_PAGE_URL' => '#SITE_DIR#/',
            'SECTION_PAGE_URL' => '#SITE_DIR#/',
            'DETAIL_PAGE_URL' => '#SITE_DIR#/',
            'INDEX_ELEMENT' => 'N',
            'INDEX_SECTION' => 'N',
            'GROUP_ID' => array(2 => 'R'),
        ),
        array(
            'IBLOCK_TYPE_ID' => 'flower_subscription',
            'LID' => 's1',
            'CODE' => 'flower_subscription_promo',
            'NAME' => 'Промо-блок',
            'LIST_PAGE_URL' => '#SITE_DIR#/',
            'SECTION_PAGE_URL' => '#SITE_DIR#/',
            'DETAIL_PAGE_URL' => '#SITE_DIR#/',
            'INDEX_ELEMENT' => 'N',
            'INDEX_SECTION' => 'N',
            'GROUP_ID' => array(2 => 'R'),
        ),
        array(
            'IBLOCK_TYPE_ID' => 'flower_subscription',
            'LID' => 's1',
            'CODE' => 'flower_subscription_advantages',
            'NAME' => 'Преимущества',
            'LIST_PAGE_URL' => '#SITE_DIR#/',
            'SECTION_PAGE_URL' => '#SITE_DIR#/',
            'DETAIL_PAGE_URL' => '#SITE_DIR#/',
            'INDEX_ELEMENT' => 'N',
            'INDEX_SECTION' => 'N',
            'GROUP_ID' => array(2 => 'R'),
        ),
        array(
            'IBLOCK_TYPE_ID' => 'flower_subscription',
            'LID' => 's1',
            'CODE' => 'flower_subscription',
            'NAME' => 'Подписка на цветы',
            'LIST_PAGE_URL' => '#SITE_DIR#/subscribe/choice/',
            'SECTION_PAGE_URL' => '#SITE_DIR#/subscribe/choice/',
            'DETAIL_PAGE_URL' => '#SITE_DIR#/subscribe/choice/',
            'INDEX_ELEMENT' => 'N',
            'INDEX_SECTION' => 'N',
            'GROUP_ID' => array(2 => 'R'),
        ),
        array(
            'IBLOCK_TYPE_ID' => 'flower_subscription',
            'LID' => 's1',
            'CODE' => 'flower_subscription_offers',
            'NAME' => 'Торговые предложения',
            'LIST_PAGE_URL' => '#SITE_DIR#/subscribe/choice/',
            'SECTION_PAGE_URL' => '#SITE_DIR#/subscribe/choice/',
            'DETAIL_PAGE_URL' => '#SITE_DIR#/subscribe/choice/',
            'INDEX_ELEMENT' => 'N',
            'INDEX_SECTION' => 'N',
            'GROUP_ID' => array(2 => 'R'),
        ),
    );
    foreach ($iblocks as $fields) {
        $filter = array('TYPE' => $fields['IBLOCK_TYPE_ID'], 'CODE' => $fields['CODE']);
        $result = CIBlock::GetList(array(), $filter);
        if ($row = $result->Fetch()) {
            echo 'Инфоблок "'.$fields['NAME'].'" уже существует <br>';
        } elseif ($CIBlock->Add($fields)) {
            echo 'Успешно создан инфоблок "'.$fields['NAME'].'" <br>';
        } else {
            echo 'Не удалось создать инфоблок "'.$fields['NAME'].'" <br>';
            echo 'Error: '.$CIBlock->LAST_ERROR.'<br>';
        }
    }

    $faqIblockId = 0;
    $filter = array('TYPE' => 'flower_subscription', 'CODE' => 'flower_subscription_faq');
    $result = CIBlock::GetList(array(), $filter);
    if ($row = $result->Fetch()) {
        $faqIblockId = $row['ID'];
    }

    $promoIblockId = 0;
    $filter = array('TYPE' => 'flower_subscription', 'CODE' => 'flower_subscription_promo');
    $result = CIBlock::GetList(array(), $filter);
    if ($row = $result->Fetch()) {
        $promoIblockId = $row['ID'];
    }

    $advantagesIblockId = 0;
    $filter = array('TYPE' => 'flower_subscription', 'CODE' => 'flower_subscription_advantages');
    $result = CIBlock::GetList(array(), $filter);
    if ($row = $result->Fetch()) {
        $advantagesIblockId = $row['ID'];
    }

    $subscriptionIblockId = 0;
    $filter = array('TYPE' => 'flower_subscription', 'CODE' => 'flower_subscription');
    $result = CIBlock::GetList(array(), $filter);
    if ($row = $result->Fetch()) {
        $subscriptionIblockId = $row['ID'];
    }

    $offersIblockId = 0;
    $filter = array('TYPE' => 'flower_subscription', 'CODE' => 'flower_subscription_offers');
    $result = CIBlock::GetList(array(), $filter);
    if ($row = $result->Fetch()) {
        $offersIblockId = $row['ID'];
    }

    $CIBlockProperty = new CIBlockProperty;
    $catalogs = array(
        array(
            'IBLOCK_ID' => $offersIblockId,
            'PRODUCT_IBLOCK_ID' => $subscriptionIblockId,
            'SKU_PROPERTY' => array(
                'NAME' => 'Элемент каталога',
                'CODE' => 'CML2_LINK',
                'PROPERTY_TYPE' => 'E',
                'USER_TYPE' => 'SKU',
            ),
        ),
    );
    foreach ($catalogs as $fields) {
        $filter = array('IBLOCK_ID' => $fields['IBLOCK_ID']);
        $select = array('IBLOCK_ID', 'ID', 'NAME');
        $result = CCatalog::GetList(array(), $filter, false, false, $select);
        if ($row = $result->fetch()) {
            echo 'Инфоблок "'.$row['NAME'].'" уже является торговым каталогом <br>';
        } else {
            if (isset($fields['SKU_PROPERTY']) && !empty($fields['SKU_PROPERTY'])) {
                $propertyFields = $fields['SKU_PROPERTY'];
                $propertyFields['IBLOCK_ID'] = $fields['IBLOCK_ID'];
                $propertyFields['LINK_IBLOCK_ID'] = $fields['PRODUCT_IBLOCK_ID'];
                unset($fields['SKU_PROPERTY']);

                $filter = array('IBLOCK_ID' => $propertyFields['IBLOCK_ID'], 'CODE' => $propertyFields['CODE']);
                $result = CIBlockProperty::GetList(array(), $filter);
                if ($row = $result->fetch()) {
                    $fields['SKU_PROPERTY_ID'] = $row['ID'];

                    echo 'Свойство "'.$propertyFields['NAME'].'" уже существует <br>';
                } elseif ($fields['SKU_PROPERTY_ID'] = $CIBlockProperty->Add($propertyFields)) {
                    echo 'Успешно создано свойство "'.$propertyFields['NAME'].'" <br>';
                } else {
                    echo 'Не удалось создать свойство "'.$propertyFields['NAME'].'" <br>';
                    echo 'Error: '.$CIBlockProperty->LAST_ERROR.'<br>';
                }
            }

            if (CCatalog::Add($fields)) {
                echo 'Инфоблок "'.$fields['IBLOCK_ID'].'" успешно привязан к торговому каталогу <br>';
            } else {
                echo 'Не удалось привязать инфоблок "'.$fields['IBLOCK_ID'].'" к торговому каталогу <br>';
                if ($exception = $APPLICATION->GetException()) {
                    echo 'Error: '.$exception->GetString().'<br>';
                }
            }
        }
    }

    $CIBlockElement = new CIBlockElement;
    $iblockElements = array(
        array(
            'IBLOCK_ID' => $faqIblockId,
            'NAME' => 'Как это работает?',
            'PREVIEW_TEXT' => 'Выбираете стилистику букетов, привязываете карту и получаете свежий букет каждую неделю!',
            'SORT' => 1,
        ),
        array(
            'IBLOCK_ID' => $faqIblockId,
            'NAME' => 'Сколько это стоит?',
            'PREVIEW_TEXT' => 'Все очень просто. 12 000 рублей при оплате за месяц или 33 000 рублей при оплате за 3 месяца (экономия 10%!).',
            'SORT' => 2,
        ),
        array(
            'IBLOCK_ID' => $faqIblockId,
            'NAME' => 'А можно подарить подписку?',
            'PREVIEW_TEXT' => 'Да, конечно. Подарить один букет – это скучно и банально. Другое дело – подписка. Каждую неделю…',
            'SORT' => 3,
        ),
        array(
            'IBLOCK_ID' => $promoIblockId,
            'NAME' => 'Вы экономите',
            'PREVIEW_TEXT' => 'Это дешевле на 40% чем покупать по одному букету',
            'CODE' => 'discount',
            'SORT' => 1,
        ),
        array(
            'IBLOCK_ID' => $promoIblockId,
            'NAME' => 'Свежие цветы',
            'PREVIEW_TEXT' => 'Доставка от местных поставщиков, цветы собираем прямо перед доставкой',
            'CODE' => 'bunch-green',
            'SORT' => 2,
        ),
        array(
            'IBLOCK_ID' => $promoIblockId,
            'NAME' => 'Красота всегда',
            'PREVIEW_TEXT' => 'Вам не надо помнить о цветах. Самый красивый букет у вас дома или в офисе',
            'CODE' => 'bouquet',
            'SORT' => 3,
        ),
        array(
            'IBLOCK_ID' => $advantagesIblockId,
            'NAME' => 'Быстрая, бесконтактная доставка',
            'CODE' => 'parcel',
            'SORT' => 1,
        ),
        array(
            'IBLOCK_ID' => $advantagesIblockId,
            'NAME' => 'Бесплатная доставка в пределах МКАД',
            'CODE' => 'delivery',
            'SORT' => 2,
        ),
        array(
            'IBLOCK_ID' => $advantagesIblockId,
            'NAME' => 'Дешевле на 40% чем покупать по одному букету',
            'CODE' => 'discount-black',
            'SORT' => 3,
        ),
        array(
            'IBLOCK_ID' => $subscriptionIblockId,
            'NAME' => '1 месяц',
            'SORT' => 1,
        ),
        array(
            'IBLOCK_ID' => $subscriptionIblockId,
            'NAME' => '3 месяца',
            'SORT' => 2,
        ),
        array(
            'IBLOCK_ID' => $subscriptionIblockId,
            'NAME' => '6 месяцев',
            'SORT' => 3,
        ),
    );
    foreach ($iblockElements as $fields) {
        $filter = array('IBLOCK_ID' => $fields['IBLOCK_ID'], 'NAME' => $fields['NAME']);
        $select = array('IBLOCK_ID', 'ID');
        $result = CIBlockElement::GetList(array(), $filter, false, false, $select);
        if ($row = $result->fetch()) {
            echo 'Элемент "'.$fields['NAME'].'" уже существует <br>';
        } elseif ($CIBlockElement->Add($fields)) {
            echo 'Успешно создан элемент "'.$fields['NAME'].'" <br>';
        } else {
            echo 'Не удалось создать элемент "'.$fields['NAME'].'" <br>';
            echo 'Error: '.$CIBlockElement->LAST_ERROR.'<br>';
        }
    }

    $CIBlock = new CIBlock;
    if ($CIBlock->Update(3, array('NAME' => 'Подписка на цветы (Старое)'))) {
        echo 'Успешно обновлён инфоблок "Подписка на цветы" <br>';
    } else {
        echo 'Не удалось обновить инфоблок "Подписка на цветы" <br>';
        echo 'Error: '.$CIBlock->LAST_ERROR.'<br>';
    }

    $CIBlock = new CIBlock;
    $iblockIds = array(6, 7);
    foreach ($iblockIds as $iblockId) {
        if (CIBlock::Delete($iblockId)) {
            echo 'Успешно удалён инфоблок "'.$iblockId.'" <br>';
        } else {
            echo 'Не удалось удалить инфоблок "'.$iblockId.'" <br>';
            echo 'Error: '.$CIBlock->LAST_ERROR.'<br>';
        }
    }

    $CIBlockProperty = new CIBlockProperty;
    $CIBlockPropertyEnum = new CIBlockPropertyEnum;
    $iblockProperties = array(
        array(
            'IBLOCK_ID' => $offersIblockId,
            'NAME' => 'Размер букета',
            'CODE' => 'SIZE',
            'PROPERTY_TYPE' => 'L',
            'ITEMS' => array(
                array(
                    'VALUE' => 'Стандартный',
                    'XML_ID' => 'standart',
                    'SORT' => 1,
                ),
                array(
                    'VALUE' => 'Увеличенный',
                    'XML_ID' => 'big',
                    'SORT' => 2,
                ),
            ),
        ),
        array(
            'IBLOCK_ID' => $offersIblockId,
            'NAME' => 'Хит',
            'CODE' => 'HIT',
            'PROPERTY_TYPE' => 'L',
            'LIST_TYPE' => 'C',
            'ITEMS' => array(
                array(
                    'VALUE' => 'Да',
                    'XML_ID' => 'yes',
                ),
            ),
        ),
        array(
            'IBLOCK_ID' => $offersIblockId,
            'NAME' => 'Подпись',
            'CODE' => 'CAPTION',
        ),
        array(
            'IBLOCK_ID' => $offersIblockId,
            'NAME' => 'Количество доставок',
            'CODE' => 'COUNT_DELIVERIES',
            'PROPERTY_TYPE' => 'N',
        ),
    );
    foreach ($iblockProperties as $fields) {
        $filter = array('IBLOCK_ID' => $fields['IBLOCK_ID'], 'CODE' => $fields['CODE']);
        $result = CIBlockProperty::GetList(array(), $filter);
        if ($row = $result->fetch()) {
            echo 'Свойство "'.$fields['NAME'].'" уже существует <br>';
        } elseif ($propertyId = $CIBlockProperty->Add($fields)) {
            echo 'Успешно создано свойство "'.$fields['NAME'].'" <br>';

            if (isset($fields['ITEMS']) && !empty($fields['ITEMS'])) {
                foreach ($fields['ITEMS'] as $enumFields) {
                    $enumFields['PROPERTY_ID'] = $propertyId;
                    if (CIBlockPropertyEnum::Add($enumFields)) {
                        echo 'Успешно создано значение "'.$enumFields['VALUE'].'" свойства "'.$fields['NAME'].'" <br>';
                    } else {
                        echo 'Не удалось создать значение "'.$enumFields['VALUE'].'" свойства "'.$fields['NAME'].'" <br>';
                    }
                }
            }
        } else {
            echo 'Не удалось создать свойство "'.$fields['NAME'].'" <br>';
            echo 'Error: '.$CIBlockProperty->LAST_ERROR.'<br>';
        }
    }

    $id1 = 0;
    $id3 = 0;
    $id6 = 0;
    $filter = array('IBLOCK_ID' => $subscriptionIblockId);
    $select = array('IBLOCK_ID', 'ID', 'NAME');
    $result = CIBlockElement::GetList(array(), $filter, false, false, $select);
    while ($row = $result->Fetch()) {
        if ($row['NAME'] === '1 месяц') {
            $id1 = $row['ID'];
        }

        if ($row['NAME'] === '3 месяца') {
            $id3 = $row['ID'];
        }

        if ($row['NAME'] === '6 месяцев') {
            $id6 = $row['ID'];
        }
    }

    $standart = 0;
    $big = 0;
    $filter = array('IBLOCK_ID' => $offersIblockId, 'CODE' => 'SIZE');
    $result = CIBlockPropertyEnum::GetList(array(), $filter);
    while ($row = $result->Fetch()) {
        if ($row['XML_ID'] === 'standart') {
            $standart = $row['ID'];
        }
        if ($row['XML_ID'] === 'big') {
            $big = $row['ID'];
        }
    }

    $hit = 0;
    $filter = array('IBLOCK_ID' => $offersIblockId, 'CODE' => 'HIT');
    $result = CIBlockPropertyEnum::GetList(array(), $filter);
    if ($row = $result->Fetch()) {
        $hit = $row['ID'];
    }

    $priceId = 1;
    $iblockElements = array(
        array(
            'IBLOCK_ID' => $offersIblockId,
            'NAME' => '1 месяц (Стандартный)',
            'SORT' => 1,
            'PROPERTY_VALUES' => array(
                'CML2_LINK' => $id1,
                'SIZE' => $standart,
                'CAPTION' => '4 букета',
            ),
            'PRICE' => 12000,
            'QUANTITY' => 1000,
        ),
        array(
            'IBLOCK_ID' => $offersIblockId,
            'NAME' => '1 месяц (Увеличенный)',
            'SORT' => 2,
            'PROPERTY_VALUES' => array(
                'CML2_LINK' => $id1,
                'SIZE' => $big,
                'CAPTION' => '4 букета',
            ),
            'PRICE' => 18000,
            'QUANTITY' => 1000,
        ),
        array(
            'IBLOCK_ID' => $offersIblockId,
            'NAME' => '3 месяца (Стандартный)',
            'SORT' => 3,
            'PROPERTY_VALUES' => array(
                'CML2_LINK' => $id3,
                'SIZE' => $standart,
                'HIT' => $hit,
                'CAPTION' => '12 букетов',
            ),
            'PRICE' => 33000,
            'QUANTITY' => 1000,
        ),
        array(
            'IBLOCK_ID' => $offersIblockId,
            'NAME' => '3 месяца (Увеличенный)',
            'SORT' => 4,
            'PROPERTY_VALUES' => array(
                'CML2_LINK' => $id3,
                'SIZE' => $big,
                'CAPTION' => '12 букетов',
            ),
            'PRICE' => 49500,
            'QUANTITY' => 1000,
        ),
        array(
            'IBLOCK_ID' => $offersIblockId,
            'NAME' => '6 месяцев (Стандартный)',
            'SORT' => 5,
            'PROPERTY_VALUES' => array(
                'CML2_LINK' => $id6,
                'SIZE' => $standart,
                'CAPTION' => '24 букета',
            ),
            'PRICE' => 60000,
            'QUANTITY' => 1000,
        ),
        array(
            'IBLOCK_ID' => $offersIblockId,
            'NAME' => '6 месяцев (Увеличенный)',
            'SORT' => 6,
            'PROPERTY_VALUES' => array(
                'CML2_LINK' => $id6,
                'SIZE' => $big,
                'CAPTION' => '24 букета',
            ),
            'PRICE' => 90000,
            'QUANTITY' => 1000,
        ),
    );
    foreach ($iblockElements as $fields) {
        $filter = array('IBLOCK_ID' => $fields['IBLOCK_ID'], 'NAME' => $fields['NAME']);
        $select = array('IBLOCK_ID', 'ID');
        $result = CIBlockElement::GetList(array(), $filter, false, false, $select);
        if ($row = $result->fetch()) {
            echo 'Элемент "'.$fields['NAME'].'" уже существует <br>';
        } elseif ($id = $CIBlockElement->Add($fields)) {
            echo 'Успешно создан элемент "'.$fields['NAME'].'" <br>';

            \Bitrix\Catalog\Model\Product::add(
                array(
                    'PRODUCT_ID' => $id,
                    'QUANTITY' => $fields['QUANTITY'],
                )
            );

            \Bitrix\Catalog\Model\Price::add(
                array(
                    'PRODUCT_ID' => $id,
                    'CATALOG_GROUP_ID' => $priceId,
                    'PRICE' => $fields['PRICE'],
                    'CURRENCY' => 'RUB',
                )
            );
        } else {
            echo 'Не удалось создать элемент "'.$fields['NAME'].'" <br>';
            echo 'Error: '.$CIBlockElement->LAST_ERROR.'<br>';
        }
    }

    $personTypeId = 1;

    /**
     * Создаём группу свойств заказа
     */
    $groupId = 0;

    $CSaleOrderPropsGroup = new CSaleOrderPropsGroup();

    $orderPropertiesGroups = array(
        array(
            'NAME' => 'Дата доставки',
            'PERSON_TYPE_ID' => $personTypeId,
        ),
    );
    foreach ($orderPropertiesGroups as $fields) {
        $filter = array('NAME' => $fields['NAME'], 'PERSON_TYPE_ID' => $fields['PERSON_TYPE_ID']);
        $select = array('ID');
        $result = $CSaleOrderPropsGroup->GetList(array(), $filter, false, false, $select);
        if ($row = $result->fetch()) {
            $groupId = $row['ID'];

            echo 'Группа свойств заказа '.$fields['NAME'].' уже существует'."<br>";
        } elseif ($groupId = $CSaleOrderPropsGroup->Add($fields)) {
            echo 'Успешно добавлена группа свойств заказа '.$fields['NAME']."<br>";
        } else {
            echo 'Не удалось добавить группу свойств заказа '.$fields['NAME']."<br>";
            if ($exception = $APPLICATION->GetException()) {
                echo 'Error: '.$exception->GetString()."<br>";
            }
        }
    }

    $baseGroupId = 1;

    /**
     * Создаём свойство заказа
     */
    $orderProperties = array(
        'DELIVERY_DATE_13' => array(
            'NAME' => 'Дата доставки 13',
            'CODE' => 'DELIVERY_DATE_13',
            'PERSON_TYPE_ID' => $personTypeId,
            'PROPS_GROUP_ID' => $groupId,
            'TYPE' => 'DATE',
            'SORT' => 250,
            'SETTINGS' => array(
                'TIME' => 'Y',
            ),
        ),
        'DELIVERY_DATE_14' => array(
            'NAME' => 'Дата доставки 14',
            'CODE' => 'DELIVERY_DATE_14',
            'PERSON_TYPE_ID' => $personTypeId,
            'PROPS_GROUP_ID' => $groupId,
            'TYPE' => 'DATE',
            'SORT' => 270,
            'SETTINGS' => array(
                'TIME' => 'Y',
            ),
        ),
        'DELIVERY_DATE_15' => array(
            'NAME' => 'Дата доставки 15',
            'CODE' => 'DELIVERY_DATE_15',
            'PERSON_TYPE_ID' => $personTypeId,
            'PROPS_GROUP_ID' => $groupId,
            'TYPE' => 'DATE',
            'SORT' => 290,
            'SETTINGS' => array(
                'TIME' => 'Y',
            ),
        ),
        'DELIVERY_DATE_16' => array(
            'NAME' => 'Дата доставки 16',
            'CODE' => 'DELIVERY_DATE_16',
            'PERSON_TYPE_ID' => $personTypeId,
            'PROPS_GROUP_ID' => $groupId,
            'TYPE' => 'DATE',
            'SORT' => 310,
            'SETTINGS' => array(
                'TIME' => 'Y',
            ),
        ),
        'DELIVERY_DATE_17' => array(
            'NAME' => 'Дата доставки 17',
            'CODE' => 'DELIVERY_DATE_17',
            'PERSON_TYPE_ID' => $personTypeId,
            'PROPS_GROUP_ID' => $groupId,
            'TYPE' => 'DATE',
            'SORT' => 330,
            'SETTINGS' => array(
                'TIME' => 'Y',
            ),
        ),
        'DELIVERY_DATE_18' => array(
            'NAME' => 'Дата доставки 18',
            'CODE' => 'DELIVERY_DATE_18',
            'PERSON_TYPE_ID' => $personTypeId,
            'PROPS_GROUP_ID' => $groupId,
            'TYPE' => 'DATE',
            'SORT' => 350,
            'SETTINGS' => array(
                'TIME' => 'Y',
            ),
        ),
        'DELIVERY_DATE_19' => array(
            'NAME' => 'Дата доставки 19',
            'CODE' => 'DELIVERY_DATE_19',
            'PERSON_TYPE_ID' => $personTypeId,
            'PROPS_GROUP_ID' => $groupId,
            'TYPE' => 'DATE',
            'SORT' => 370,
            'SETTINGS' => array(
                'TIME' => 'Y',
            ),
        ),
        'DELIVERY_DATE_20' => array(
            'NAME' => 'Дата доставки 20',
            'CODE' => 'DELIVERY_DATE_20',
            'PERSON_TYPE_ID' => $personTypeId,
            'PROPS_GROUP_ID' => $groupId,
            'TYPE' => 'DATE',
            'SORT' => 390,
            'SETTINGS' => array(
                'TIME' => 'Y',
            ),
        ),
        'DELIVERY_DATE_21' => array(
            'NAME' => 'Дата доставки 21',
            'CODE' => 'DELIVERY_DATE_21',
            'PERSON_TYPE_ID' => $personTypeId,
            'PROPS_GROUP_ID' => $groupId,
            'TYPE' => 'DATE',
            'SORT' => 410,
            'SETTINGS' => array(
                'TIME' => 'Y',
            ),
        ),
        'DELIVERY_DATE_22' => array(
            'NAME' => 'Дата доставки 22',
            'CODE' => 'DELIVERY_DATE_22',
            'PERSON_TYPE_ID' => $personTypeId,
            'PROPS_GROUP_ID' => $groupId,
            'TYPE' => 'DATE',
            'SORT' => 430,
            'SETTINGS' => array(
                'TIME' => 'Y',
            ),
        ),
        'DELIVERY_DATE_23' => array(
            'NAME' => 'Дата доставки 23',
            'CODE' => 'DELIVERY_DATE_23',
            'PERSON_TYPE_ID' => $personTypeId,
            'PROPS_GROUP_ID' => $groupId,
            'TYPE' => 'DATE',
            'SORT' => 450,
            'SETTINGS' => array(
                'TIME' => 'Y',
            ),
        ),
        'TYPE_BOUQUET' => array(
            'NAME' => 'Тип букета (Подписка на цветы)',
            'CODE' => 'TYPE_BOUQUET',
            'PERSON_TYPE_ID' => $personTypeId,
            'PROPS_GROUP_ID' => $baseGroupId,
            'TYPE' => 'ENUM',
            'SORT' => 2000,
            'ITEMS' => array(
                array(
                    'NAME' => 'Монобукеты',
                    'VALUE' => 'mono',
                ),
                array(
                    'NAME' => 'Составные букеты',
                    'VALUE' => 'compose',
                ),
            ),
        ),
        'TYPE_BOUQUET_2' => array(
            'NAME' => 'Вид букета (Подписка на цветы)',
            'CODE' => 'TYPE_BOUQUET_2',
            'PERSON_TYPE_ID' => $personTypeId,
            'PROPS_GROUP_ID' => $baseGroupId,
            'TYPE' => 'ENUM',
            'SORT' => 2100,
            'ITEMS' => array(
                array(
                    'NAME' => 'Собранный букет',
                    'VALUE' => 'assembled',
                ),
                array(
                    'NAME' => 'Набор',
                    'VALUE' => 'set',
                ),
            ),
        ),
    );
    foreach ($orderProperties as $fields) {
        $filter = array(
            'CODE' => $fields['CODE'],
            'PERSON_TYPE_ID' => $fields['PERSON_TYPE_ID'],
            'PROPS_GROUP_ID' => $fields['PROPS_GROUP_ID'],
        );
        $select = array('ID');
        $result = CSaleOrderProps::GetList(array(), $filter, false, false, $select);
        if ($row = $result->fetch()) {
            $orderProperties[$fields['CODE']]['ID'] = $row['ID'];

            echo 'Свойство заказа '.$fields['NAME'].' уже существует'."<br>";
        } elseif ($orderProperties[$fields['CODE']]['ID'] = CSaleOrderProps::Add($fields)) {
            echo 'Успешно добавлено свойство заказа '.$fields['NAME']."<br>";

            if (isset($fields['ITEMS']) && !empty($fields['ITEMS'])) {
                foreach ($fields['ITEMS'] as $enumFields) {
                    $enumFields['ORDER_PROPS_ID'] = $orderProperties[$fields['CODE']]['ID'];
                    if (CSaleOrderPropsVariant::Add($enumFields)) {
                        echo 'Успешно создано значение "'.$enumFields['NAME'].'" свойства "'.$fields['NAME'].'" <br>';
                    } else {
                        echo 'Не удалось создать значение "'.$enumFields['NAME'].'" свойства "'.$fields['NAME'].'" <br>';
                    }
                }
            }
        } else {
            echo 'Не удалось добавить свойство заказа '.$fields['NAME']."<br>";
            if ($exception = $APPLICATION->GetException()) {
                echo 'Error: '.$exception->GetString()."<br>";
            }
        }
    }
}