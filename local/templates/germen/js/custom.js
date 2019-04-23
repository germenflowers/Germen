function detectTouch() {
    if (typeof window !== 'undefined') {
        return Boolean(
            'ontouchstart' in window ||
            window.navigator.maxTouchPoints > 0 ||
            window.navigator.msMaxTouchPoints > 0 ||
            window.DocumentTouch && document instanceof DocumentTouch
        );
    }
}

function getMap() {
    var deliveryMap,
        myCollection,
        searchControl,
        addressProp = $("#ORDER_PROP_2"),
        searchWrap = $('#search-street');

    if ( addressProp.length ) {
        (function (window, document, undefined) {
            searchAdr = function (options) {
                this.options = $.extend(this.options, options);
            };
            searchAdr.prototype = {
                options: {
                    findMap: false //Нужно ли привязывать наш поиск к карте, по умолчанию не нужно. Если нужно тогда нужно и инициализацию карты делать
                },
                mapControl: null,//объект карты yandex
                geoObjectsArray: [],//Сюда складываем все найденные объекты

                initMap: function () {
                    deliveryMap = new ymaps.Map("delivery-map", {
                        center: [ 55.753215, 37.622504 ],
                        zoom: 12
                    });
                    deliveryMap.behaviors.disable('scrollZoom');
                    if ( detectTouch() )
                        deliveryMap.behaviors.disable('drag');
                    myCollection = new ymaps.GeoObjectCollection();
                    deliveryMap.geoObjects.add(myCollection);

                    if ( addressProp.val().length > 0 )
                        addressProp.trigger('keyup');
                },
                //Создаем объект поисковых подсказок
                addSearchControl: function () {
                    searchControl = new ymaps.control.SearchControl({
                        options: {
                            useMapBounds: true
                        }
                    });
                    if ( this.options.findMap )
                        deliveryMap.controls.add(searchControl);
                    //Активируем наш поиск
                    this.activateSearch();
                },
                //Активируем поиск. Привяжемся к форме (input) в котором будем искать
                activateSearch: function () {
                    var self = this;
                    addressProp.keyup(function () {
                        var search_query = $(this).val(),
                            search_result = [];
                        if ( search_query.length > 0 ) {
                            if ( search_query.indexOf('Россия, Москва') < 0 )
                                search_query = 'Россия, Москва, ' + search_query;
                            searchControl.search( search_query ).then(function () {
                                self.geoObjectsArray = searchControl.getResultsArray();
                                if ( self.geoObjectsArray.length ) {
                                    searchWrap.show();
                                    for ( var i = 0; i < self.geoObjectsArray.length; i++ ) {
                                        search_result.push({label: self.geoObjectsArray[i].properties.get('text')});
                                    }
                                    html = '';
                                    for ( var i in search_result ) {
                                        html += '<li onclick="searchAdr.selectAddress(this, ' + i + ')">' + search_result[i].label + '</li>';
                                    }
                                    searchWrap.html(html);
                                }
                            });
                        }
                        else
                            searchWrap.hide();
                    });
                },
                selectAddress: function (obj, i) {
                    var full_adress = '';
                    var mt = this.geoObjectsArray[i];
                    var t = mt.properties.get('metaDataProperty').GeocoderMetaData;
                    AddressDetails = t.AddressDetails;
                    Country = AddressDetails.Country;
                    Region = Country.AdministrativeArea.AdministrativeAreaName;

                    addressProp.val( Country.AddressLine );
                    searchWrap.hide();
                }
            };
        })(window, document);
        window.searchAdr = new searchAdr({findMap: true});
        ymaps.ready(function () {
            searchAdr.initMap();
            searchAdr.addSearchControl();

            searchWrap.hide();
            deliveryMap.events.add('click', function (e) {
                addressProp.val("Определяем адрес...");
                var coords = e.get('coords');

                myCollection.removeAll();

                ymaps.geocode(coords,{ results:1 }).then(function(res) {
                    var MyGeoObj = res.geoObjects.get(0);
                    addressProp.val( MyGeoObj.properties.get('text') );

                    pm  = new ymaps.Placemark(coords, {
                        hintContent: MyGeoObj.properties.get('text')
                    });
                    myCollection.add(pm);
                });
            });
        });
    }
}

function changeFormData( formOrder ) {
    var error = true,
        props = [],
        val,
        vaseWrap = $('#vase-wrap');

    formOrder.find('input').each(function() {
        if ( $(this).attr('type') == 'checkbox' ) {
            val = $(this).val();
            if ( !$(this).is(':checked') )
                val = '';
            props[ $(this).attr('name') ] = val;
        }
        else
            props[ $(this).attr('name') ] = $.trim( $(this).val()) ;
    });

    if ( props['ORDER_PROP_15'].length == 0 )
        $('.js-address-block').show();
    else
        $('.js-address-block').hide();

    if ( props['ORDER_PROP_7'].length == 0 )
        $('#social-wrap').hide();
    else
        $('#social-wrap').show();

    if ( props['ORDER_PROP_11'].length == 0 )
        vaseWrap.hide();
    else
        vaseWrap.show();

    if ( props['ORDER_PROP_12'].length == 0 )
        $('#note-wrap').hide();
    else
        $('#note-wrap').show();

    var price = 0,
        totalOldPrice = $('.js-orderprice').prev('.promo-order__submit__old-price');
    price += parseInt( $('.promo-order__item').data('price') );
    if ( props['ORDER_PROP_11'].length > 0 ) {
        price += parseInt( vaseWrap.data('price') );

        if ( totalOldPrice.length > 0 )
            totalOldPrice.html($.number( (parseInt(totalOldPrice.data('price')) + parseInt(vaseWrap.data('price'))), 0, '', ' ' ) + ' <span class="rouble"></span>');
    }
    else if ( totalOldPrice.length > 0 )
        totalOldPrice.html($.number( parseInt(totalOldPrice.data('price')), 0, '', ' ' ) + ' <span class="rouble"></span>');


    $('.js-orderprice').text( $.number( price, 0, '', ' ' ) );
}

function afterEnterCoupon( result ) {
    var couponWrap = $('#coupon_wrap');
    couponWrap.find('.promo-order__error').hide();
    if ( result.STATUS_ENTER == 'BAD' )
        couponWrap.find('.promo-order__error').show();
    else {
        couponWrap.find('.input__wrapper input').val('');
        couponWrap.find('.input__wrapper').show();
        couponWrap.find('.promo-order__coupon__discount').hide();
        couponWrap.find('button').show();
        couponWrap.find('.promo-order__coupon__sum').hide();

        if ( result.COUPON ) {
            couponWrap.find('.input__wrapper').hide();

            couponWrap.find('.promo-order__coupon__discount').html(result.DISCOUNT_NAME + ' (<strong>' + result.COUPON + '</strong>)<span class="promo-order__coupon__discount__cancel">Отменить</span>' );
            couponWrap.find('.promo-order__coupon__discount').show();

            couponWrap.find('button').hide();
            if ( result.OLD_PRICE > 0 ) {
                couponWrap.find('.promo-order__coupon__sum').html('-' + (result.OLD_PRICE-result.PRICE) + ' <span class="rouble"></span>');
                couponWrap.find('.promo-order__coupon__sum').show();
            }
        }
    }

    var priceProd = '';
    if ( result.OLD_PRICE > 0 )
        priceProd += '<span class="promo-order__item__price__old">' + $.number( result.OLD_PRICE, 0, '', ' ' ) + ' <span class="rouble"></span></span> ';
    priceProd += $.number( result.PRICE, 0, '', ' ' ) + ' <span class="rouble"></span>';
    $('.js-prod_price').html( priceProd );

    var vaseInput = $('#ORDER_PROP_11');

    var priceTotal = '',
        priceVase = 0;
    if ( vaseInput.is(':checked') )
        priceVase = parseInt( vaseInput.data('price') );
    if ( result.OLD_PRICE > 0 )
        priceTotal += '<span class="promo-order__submit__old-price" data-price="' + result.OLD_PRICE + '">' + $.number( (result.OLD_PRICE+priceVase), 0, '', ' ' ) + ' <span class="rouble"></span></span>';
    priceTotal += '<span class="js-orderprice">' + $.number( (result.PRICE+priceVase), 0, '', ' ' ) + '</span> <span class="rouble"></span>';
    $('.js-order_total').html( priceTotal );
}

$(document).ready(function(){
    var body = $('body');

    var startDate = new Date(),
        orderData = $('.order-datetime'),
        minHours = 8,
        maxHours = 21;

    startDate.setMinutes(startDate.getMinutes() + parseInt(orderData.data('time')) );
    startDate.setHours(startDate.getHours() + 1 );
    startDate.setMinutes(0);

    if ( startDate.getHours() > maxHours ) {
        startDate.setDate( startDate.getDate() + 1);
        startDate.setHours( minHours );
    }

    orderData.datepicker({
        timepicker: true,
        startDate: startDate,
        minDate: startDate,
        minHours: minHours,
        maxHours: maxHours
    });

    $('.phomemask').mask("+7 (999) 999-99-99");

    if ( $('#popup-login').length )
        $('#popup-login').modal('show');

    $('.js-set_delivery').click(function () {
        $.post(
            "/_ajax/actions.php",
            {
                id: $(this).data('id'),
                action: "setDelivery"
            },
            function (result) {
                if (!result.error) {
                    $('#popup-login').modal('hide');
                    $('.js-prod_time').text(result.data.TIME);
                }
            },
            'json'
        );

        return false;
    });

    body.on('click', '.js-pagin', function(){
        var link = $(this),
            href = link.data('href');

        var nextBlock = href.split('=');
        nextBlock = '[data-pagen="' + nextBlock[0].replace('/?PAGEN_', '') + '"]';

        $.get( href, function ( data ) {
            link.parent().prev().append( $(data).find(nextBlock).prev().html() );
            link.parent().after( $(data).find(nextBlock) );
            link.parent().remove();
        });

        return false;
    });

    var $popupProduct = $('#popup-product');
    var $popupProductBody = $popupProduct.find('.js-body');

    function getUrlParam(name) {
        var s = window.location.search;
        s = s.match(new RegExp(name + '=([^&=]+)'));
        return s ? s[1] : false;
    }

    body.on('click', '.js-detail', function () {
        var id = parseInt($(this).data('id')),
            order = '';
        if (window.location.pathname === '/order/')
            order = 'Y';


        if (id > 0) {
            $.ajax({
                url: "/_ajax/actions.php",
                method: "POST",
                dataType: "json",
                data: {
                    id: id,
                    order: order,
                    action: 'getDetail',
                    clear_cache: getUrlParam("clear_cache")
                },
                success: function (result) {
                    if (!result.error) {
                        $popupProductBody.html(result.data);
                        var $productSlider = $popupProduct.find('[data-product-slider]');

                        if ($productSlider.length > 0) {
                            $productSlider.slick({
                                lazyLoad: 'ondemand',
                                dots: true,
                                arrows: false
                            });
                        }
                        $popupProduct.modal('show');
                    }
                }
            });
        }

        return false;
    });

    $popupProduct.on('shown.bs.modal', function(){
        var $productSlider = $popupProduct.find('[data-product-slider]');

        if ($productSlider.length > 0) {
            $productSlider.slick('refresh');
        }
    });

    $popupProduct.on('hidden.bs.modal', function(){
        var $productSlider = $popupProduct.find('[data-product-slider]');

        if ($productSlider.length > 0) {
            $productSlider.slick('unslick');
        }
    });

    getMap();

    var formOrder = $('#form-order');
    if ( formOrder.length ) {
        changeFormData( formOrder );

        formOrder.find('input').on('change', function(){
            changeFormData( formOrder );
        });
    }

    formOrder.submit(function () {
        var props = [],
            val = '',
            firstErrorInput = '';

        formOrder.find('input').each(function() {
            $(this).removeClass('error');
            if ( $(this).attr('type') == 'checkbox' ) {
                val = $(this).val();
                if ( !$(this).is(':checked') )
                    val = '';
                props[ $(this).attr('name') ] = val;
            }
            else
                props[ $(this).attr('name') ] = $.trim( $(this).val()) ;
        });

        if ( props['ORDER_PROP_15'].length == 0 && props['ORDER_PROP_2'].length == 0 ) {
            $('#ORDER_PROP_2').addClass('error');
            if ( firstErrorInput.length == 0 )
                firstErrorInput = $('#ORDER_PROP_2');
        }

        if ( props['ORDER_PROP_5'].length == 0) {
            $('#ORDER_PROP_5').addClass('error');
            if ( firstErrorInput.length == 0 )
                firstErrorInput = $('#ORDER_PROP_5');
        }

        for ( i = 0; i < 12; i++ ) {
            if ( props['ORDER_PROP_4[' + i + ']'] !== undefined ) {
                if ( props['ORDER_PROP_4[' + i + ']'].length == 0 ) {
                    $('#ORDER_PROP_4_' + i).addClass('error');
                    if ( firstErrorInput.length == 0 )
                        firstErrorInput = $('#ORDER_PROP_4_' + i);
                }
            }
        }

        if ( props['ORDER_PROP_8'].length == 0) {
            $('#ORDER_PROP_8').addClass('error');
            if ( firstErrorInput.length == 0 )
                firstErrorInput = $('#ORDER_PROP_8');
        }

        if ( props['ORDER_PROP_9'].length == 0) {
            $('#ORDER_PROP_9').addClass('error');
            if ( firstErrorInput.length == 0 )
                firstErrorInput = $('#ORDER_PROP_9');
        }

        if ( firstErrorInput.length > 0 )
            $("html,body").animate({scrollTop: firstErrorInput.offset().top - 40 }, 1000);
        else {
            $('.promo-order').addClass('loader');
            formOrder.find('[type="submit"]').prop('disabled', true);
            BX.showWait();
            $.post( window.location.href, formOrder.serialize(), function ( orderId ) {
                if ( orderId > 0 )
                    window.location.href = '/order/?ORDER_ID=' + orderId;
                BX.closeWait();
            });
        }

        return false;
    });

    if ( $('#popup-flowers').length )
        $('#popup-flowers').modal('show');

    $('#popup-flowers').on('hide.bs.modal', function () {
        window.location.href = '/';
    });

    if ( $('#popup-flowers-success').length )
        $('#popup-flowers-success').modal('show');

    body.on('click', '.js-coupon_link', function() {
        $('#coupon_wrap').slideToggle();
        return false;
    });

    body.on('click', '#coupon_wrap button', function() {
        var btn = $(this),
            input = $(this).closest('.promo-order__coupon').find('input'),
            props = [],
            val = '';

        btn.prop('disabled', true);
        input.removeClass('error');
        if ( input.val().length == 0 )
            input.addClass('error');
        else {
            BX.showWait();
            $.post( window.location.href, { coupon:input.val() }, function ( result ) {
                afterEnterCoupon( result );
                btn.prop('disabled', false);
                BX.closeWait();
            }, 'json');
        }

        return false;
    });

    body.on('click', '#coupon_wrap .promo-order__coupon__discount__cancel', function() {
        $(this).hide();
        BX.showWait();
        $.post( window.location.href, { coupon:'' }, function ( result ) {
            afterEnterCoupon( result );
            BX.closeWait();
        }, 'json');

        return false;
    });
});

// Фикс скролла после закрытия попапа скидки
(function(){
    var $doc = $(document);

    $doc.ready(function(){
        $doc.on('click', '.mc-closeModal', function(){
            $('body').css('overflow', '');
        });
    });
})();