$(document).ready(function()
{
  let formOrder = $('form[name=orderForm]'),
    startDate = new Date(),
    orderData = $('.js-order-datetime'),
    minHours = 8,
    maxHours = 21,
    maxTime = orderData.data('maxtime'),
    minTime = orderData.data('mintime');

  if (formOrder.length) {
    changeFormData(formOrder);

    formOrder.find('input').on('change', function () {
      changeFormData(formOrder);
    });
  }

  startDate.setMinutes(startDate.getMinutes() + parseInt(orderData.data('time')));
  startDate.setHours(startDate.getHours() + 1);
  startDate.setMinutes(0);

  if (startDate.getHours() > maxHours) {
    startDate.setDate(startDate.getDate() + 1);
    startDate.setHours(minHours);
  }

  orderData.datepicker({
    timepicker: true,
    startDate: startDate,
    minDate: startDate,
    minHours: minHours,
    maxHours: maxHours,
    onRenderCell: function (date, cellType) {
      if (cellType === 'day') {
        let timestamp = date.getTime() / 1000,
          isDisabled = false,
          isDisabledMax = false,
          isDisableMin = false;

        if (typeof maxTime !== 'undefined') {
          isDisabledMax = timestamp >= maxTime;
        }

        if (typeof minTime !== 'undefined') {
          minTime += date.getTimezoneOffset() * 60;

          isDisableMin = timestamp <= minTime;
        }

        if (isDisabledMax || isDisableMin) {
          isDisabled = true;
        }

        return {
          disabled: isDisabled
        }
      }
    }
  });

  getMap();

  $('.js-order-offers-slider').each(function () {
    let swiperInstance = new Swiper(this, {
      navigation: {
        nextEl: '.swiper-button-next',
        prevEl: '.swiper-button-prev'
      },
      slideClass: 'order-offers__item',
      slidesPerView: 'auto',
      spaceBetween: 24,
      wrapperClass: 'order-offers__list'
    });

    $(this).on('swiper:update', function () {
      swiperInstance.update();
    });
  });

  $(document).on('click', '.js-order-bookmate', function (e) {
    e.preventDefault();

    let items = $('.js-order-bookmate-items');

    items.removeClass('hidden');

    setTimeout(function () {
      items.trigger('swiper:update');
    }, 0);
  });

  $(document).on('click', '.js-order-item-delete', function (e) {
    e.preventDefault();

    let self = $(this),
      id = self.data('id'),
      productId = self.data('productid');

    $.ajax({
      type: 'POST',
      url: '/ajax/cart.php',
      data: {
        action: 'delete',
        page: 'order',
        id: id,
      },
      beforeSend: function () {
        $('.js-pagenavigation-loader').show();
        $('.js-pagenavigation-overlay').show();
      },
      success: function (data) {
        data = jQuery.parseJSON(data);

        if (data.status === 'success') {
          if (data.data.count > 0) {
            renderOrderSummary(data.data);
            renderBookmate(data.data);
            renderOrderDeliveryTimes(data.data);

            $('.js-order-cart').html($.templates('#orderCartTmpl').render(data.data));
          } else {
            window.location.href = '/cart/';
          }
        }

        $('.js-pagenavigation-loader').hide();
        $('.js-pagenavigation-overlay').hide();
      },
      error: function (error) {
        $('.js-pagenavigation-loader').hide();
        $('.js-pagenavigation-overlay').hide();
      },
    });
  });

  $(document).on('click', '.js-order-item-minus', function (e) {
    e.preventDefault();

    let self = $(this),
      id = self.data('id'),
      productId = self.data('productid');

    $.ajax({
      type: 'POST',
      url: '/ajax/cart.php',
      data: {
        action: 'minus',
        page: 'order',
        id: id,
      },
      beforeSend: function () {
        $('.js-pagenavigation-loader').show();
        $('.js-pagenavigation-overlay').show();
      },
      success: function (data) {
        data = jQuery.parseJSON(data);

        if (data.status === 'success') {
          if (data.data.count > 0) {
            renderOrderSummary(data.data);
            renderBookmate(data.data);
            renderOrderDeliveryTimes(data.data);

            $('.js-order-cart').html($.templates('#orderCartTmpl').render(data.data));
          } else {
            window.location.href = '/cart/';
          }
        }

        $('.js-pagenavigation-loader').hide();
        $('.js-pagenavigation-overlay').hide();
      },
      error: function (error) {
        $('.js-pagenavigation-loader').hide();
        $('.js-pagenavigation-overlay').hide();
      },
    });
  });

  $(document).on('click', '.js-order-item-plus', function (e) {
    e.preventDefault();

    let self = $(this),
      id = self.data('id');

    $.ajax({
      type: 'POST',
      url: '/ajax/cart.php',
      data: {
        action: 'plus',
        page: 'order',
        id: id,
      },
      beforeSend: function () {
        $('.js-pagenavigation-loader').show();
        $('.js-pagenavigation-overlay').show();
      },
      success: function (data) {
        data = jQuery.parseJSON(data);

        if (data.status === 'success') {
          renderOrderSummary(data.data);

          $('.js-order-cart').html($.templates('#orderCartTmpl').render(data.data));
        }

        $('.js-pagenavigation-loader').hide();
        $('.js-pagenavigation-overlay').hide();
      },
      error: function (error) {
        $('.js-pagenavigation-loader').hide();
        $('.js-pagenavigation-overlay').hide();
      },
    });
  });

  $(document).on('click', '.js-order-add-upsale', function (e) {
    e.preventDefault();

    let self = $(this),
      id = self.data('id'),
      productId = self.data('productid');

    ajaxAddToOrder({ action: 'addUpsale', page: 'order', id: id, productId: productId });
  });

  $(document).on('click', '.js-order-add-bookmate', function (e) {
    e.preventDefault();

    let self = $(this),
      id = self.data('id'),
      productId = self.data('productid');

    ajaxAddToOrder({ action: 'addBookmate', page: 'order', id: id, productId: productId });
  });

  $(document).on('click', '.js-order-promocode-apply', function (e) {
    e.preventDefault();

    $(this).hide();

    $('.js-order-promocode-form').html($.templates('#orderPromocodeFormTmpl').render());
  });

  $(document).on('click', '.js-order-promocode-add', function (e) {
    e.preventDefault();

    let button = $(this),
      input = $('input[name=coupon]'),
      coupon = input.val(),
      errorContainer = $('.js-order-promocode-error');

    input.removeClass('error');
    errorContainer.html('').hide();

    if (coupon === '') {
      input.addClass('error');
    } else {
      $.ajax({
        type: 'POST',
        url: '/ajax/cart.php',
        data: {
          action: 'couponAdd',
          coupon: coupon,
        },
        beforeSend: function () {
          button.prop('disabled', true).addClass('btn--loading');
        },
        success: function (data) {
          data = jQuery.parseJSON(data);

          if (data.status === 'success') {
            if (!(data.data.coupon.status === 2 || data.data.coupon.status === 4)) {
              errorContainer.html(data.data.coupon.statusText).show();
            } else {
              renderOrderSummary(data.data);
              $('.js-promocode').html($.templates('#orderPromocodeTmpl').render(data.data));
              $('.js-order-promocode-form').html('');
            }
          }

          button.prop('disabled', false).removeClass('btn--loading');
        },
        error: function (error) {
          button.prop('disabled', false).removeClass('btn--loading');
        },
      });
    }
  });

  $(document).on('click', '.js-order-promocode-cancel', function (e) {
    e.preventDefault();

    $.ajax({
      type: 'POST',
      url: '/ajax/cart.php',
      data: {
        action: 'couponDelete',
      },
      beforeSend: function () {
        $('.js-pagenavigation-loader').show();
        $('.js-pagenavigation-overlay').show();
      },
      success: function (data) {
        data = jQuery.parseJSON(data);

        if (data.status === 'success') {
          renderOrderSummary(data.data);
          $('.js-promocode').html($.templates('#orderPromocodeApplyTmpl').render());
        }

        $('.js-pagenavigation-loader').hide();
        $('.js-pagenavigation-overlay').hide();
      },
      error: function (error) {
        $('.js-pagenavigation-loader').hide();
        $('.js-pagenavigation-overlay').hide();
      }
    });
  });

  $(document).on('submit', formOrder, function (e) {
    e.preventDefault();

    let props = [],
      val = '',
      firstErrorInput = '';

    formOrder.find('input').each(function () {
      $(this).removeClass('error');

      if ($(this).attr('type') === 'checkbox') {
        val = $(this).val();
        if (!$(this).is(':checked')) {
          val = '';
        }
        props[$(this).attr('name')] = val;
      } else {
        props[$(this).attr('name')] = $.trim($(this).val());
      }
    });

    if (props['ORDER_PROP_15'].length === 0 && props['ORDER_PROP_2'].length === 0) {
      $('input[name=ORDER_PROP_2]').addClass('error');

      if (firstErrorInput.length === 0) {
        firstErrorInput = $('input[name=ORDER_PROP_2]');
      }
    }

    if (props['ORDER_PROP_5'].length === 0) {
      $('input[name=ORDER_PROP_5]').addClass('error');

      if (firstErrorInput.length === 0) {
        firstErrorInput = $('input[name=ORDER_PROP_5]');
      }
    }

    for (let i = 0; i < 24; i++) {
      if (
        props['ORDER_PROP_4[' + i + ']'] !== undefined &&
        (
          props['ORDER_PROP_4[' + i + ']'].length === 0 ||
          !moment(props['ORDER_PROP_4[' + i + ']'], 'DD.MM.YYYY HH:mm', true).isValid()
        )
      ) {
        $('input[name="ORDER_PROP_4[' + i + ']"]').addClass('error');

        if (firstErrorInput.length === 0) {
          firstErrorInput = $('input[name="ORDER_PROP_4[' + i + ']"]');
        }
      }
    }

    if (props['ORDER_PROP_19'].length === 0) {
      $('input[name=ORDER_PROP_19]').addClass('error');

      if (firstErrorInput.length === 0) {
        firstErrorInput = $('input[name=ORDER_PROP_19]');
      }
    }

    if (props['ORDER_PROP_8'].length === 0) {
      $('input[name=ORDER_PROP_8]').addClass('error');

      if (firstErrorInput.length === 0) {
        firstErrorInput = $('input[name=ORDER_PROP_8]');
      }
    }

    if (props['ORDER_PROP_9'].length === 0) {
      $('input[name=ORDER_PROP_9]').addClass('error');

      if (firstErrorInput.length === 0) {
        firstErrorInput = $('input[name=ORDER_PROP_9]');
      }
    }

    if (firstErrorInput.length > 0) {
      $("html,body").animate({ scrollTop: firstErrorInput.offset().top - 40 }, 1000);
    } else {
      let data = {};
      $.each(formOrder.serializeArray(), function (index, item) {
        if (item.name.indexOf('ORDER_PROP_4[') + 1) {
          if (typeof data['ORDER_PROP_4'] === 'undefined') {
            data['ORDER_PROP_4'] = [];
          }

          data['ORDER_PROP_4'].push(item.value);
        } else {
          data[item.name] = item.value;
        }
      });

      for (let i = 0; i < 24; i++) {
        if (data['ORDER_PROP_4'][i] !== undefined) {
          $('.js-delivery-date').each(function (index) {
            if (index !== i) {
              return;
            }

            $(this).val(data['ORDER_PROP_4'][i]);
            data[$(this).attr('name')] = data['ORDER_PROP_4'][i];
          });
        }
      }

      if (
        typeof data['ORDER_PROP_19'] === 'undefined' ||
        data['ORDER_PROP_19'] === ''
      ) {
        data['ORDER_PROP_19'] = 'user@germen.me';
      }

      $.ajax({
        type: 'POST',
        url: '/ajax/checkout.php',
        data: data,
        beforeSend: function () {
          $('.js-pagenavigation-loader').show();
          $('.js-pagenavigation-overlay').show();
        },
        success: function (data) {
          data = jQuery.parseJSON(data);

          if (data.order.ID !== '') {
            window.location.replace('/order/?ORDER_ID=' + data.order.ID);
          }

          $('.js-pagenavigation-loader').hide();
          $('.js-pagenavigation-overlay').hide();
        },
        error: function (error) {
          $('.js-pagenavigation-loader').hide();
          $('.js-pagenavigation-overlay').hide();
        }
      });
    }
  });
});

function ajaxAddToOrder(params) {
  $.ajax({
    type: 'POST',
    url: '/ajax/cart.php',
    data: params,
    beforeSend: function () {
      $('.js-pagenavigation-loader').show();
      $('.js-pagenavigation-overlay').show();
    },
    success: function (data) {
      data = jQuery.parseJSON(data);

      if (data.status === 'success') {
        renderOrderSummary(data.data);
        renderBookmate(data.data);

        $('.js-order-cart').html($.templates('#orderCartTmpl').render(data.data));
      }

      $('.js-pagenavigation-loader').hide();
      $('.js-pagenavigation-overlay').hide();
    },
    error: function (error) {
      $('.js-pagenavigation-loader').hide();
      $('.js-pagenavigation-overlay').hide();
    }
  });
}

function renderOrderSummary(data) {
  $('.js-goods-sum').html($.templates('#goodsPriceTmpl').render(data));
  $('.js-upsale-sum').html($.templates('#upsalePriceTmpl').render(data));
  $('.js-order-sum').html($.templates('#orderPriceTmpl').render(data));
  $('.js-order-button').html($.templates('#orderButtonTmpl').render(data));
}

function renderBookmate(data) {
  if (data.hasBookmate) {
    $('.js-order-add-bookmate').hide();
  } else {
    $('.js-order-add-bookmate').show();
  }
}

function renderOrderDeliveryTimes(data) {
  let input = $('input[name=countDateDelivery]'),
    countDateDelivery = parseInt(input.val(), 10);

  if (countDateDelivery > data.countDateDelivery) {
    input.val(data.countDateDelivery);

    $('.js-order-delivery-time').each(function (index) {
      if (index > data.countDateDelivery - 1) {
        $(this).remove();
      }
    });
  }
}

function getMap() {
  let deliveryMap,
    myCollection,
    searchControl,
    addressProp = $('.js-address-property'),
    searchWrap = $('.js-search-street');

  if (addressProp.length === 0) {
    return;
  }

  ymaps.ready(function () {
    let searchAdr = function (options) {
      this.options = $.extend(this.options, options);
    };

    searchAdr.prototype = {
      options: {
        findMap: false,
      },
      mapControl: null,
      geoObjectsArray: [],
      initMap: function () {
        deliveryMap = new ymaps.Map("delivery-map", {
          center: [55.753215, 37.622504],
          zoom: 12
        });

        deliveryMap.behaviors.disable('scrollZoom');

        if (detectTouch()) {
          deliveryMap.behaviors.disable('drag');
        }

        myCollection = new ymaps.GeoObjectCollection();
        deliveryMap.geoObjects.add(myCollection);

        if (addressProp.val().length > 0) {
          addressProp.trigger('keyup');
        }
      },
      addSearchControl: function () {
        searchControl = new ymaps.control.SearchControl({
          options: {
            useMapBounds: true
          }
        });

        if (this.options.findMap) {
          deliveryMap.controls.add(searchControl);
        }

        this.activateSearch();
      },
      activateSearch: function () {
        let self = this;
        addressProp.keyup(function () {
          let search_query = $(this).val(),
            search_result = [];

          if (search_query.length > 0) {
            if (search_query.indexOf('Россия, Москва') < 0) {
              search_query = 'Россия, Москва, ' + search_query;
            }

            searchControl.search(search_query).then(function () {
              self.geoObjectsArray = searchControl.getResultsArray();

              let html;

              if (self.geoObjectsArray.length) {
                searchWrap.show();

                for (let i = 0; i < self.geoObjectsArray.length; i++) {
                  search_result.push({ label: self.geoObjectsArray[i].properties.get('text') });
                }

                html = '';
                for (let i in search_result) {
                  html += '<li onclick="searchAdr.selectAddress(this, ' + i + ')">' + search_result[i].label + '</li>';
                }

                searchWrap.html(html);
              }
            });
          } else {
            searchWrap.hide();
          }
        });
      },
      selectAddress: function (obj, i) {
        let mt = this.geoObjectsArray[i],
          t = mt.properties.get('metaDataProperty').GeocoderMetaData,
          AddressDetails = t.AddressDetails,
          Country = AddressDetails.Country;

        addressProp.val(Country.AddressLine);
        searchWrap.hide();
      }
    };

    window.searchAdr = new searchAdr({ findMap: true });
    window.searchAdr.initMap();
    window.searchAdr.addSearchControl();

    searchWrap.hide();

    deliveryMap.events.add('click', function (e) {
      addressProp.val("Определяем адрес...");
      let coords = e.get('coords');

      myCollection.removeAll();

      ymaps.geocode(coords, { results: 1 }).then(function (res) {
        let MyGeoObj = res.geoObjects.get(0);
        addressProp.val(MyGeoObj.properties.get('text'));

        let pm = new ymaps.Placemark(coords, {
          hintContent: MyGeoObj.properties.get('text')
        });
        myCollection.add(pm);
      });
    });
  });
}

function changeFormData(formOrder) {
  let props = [],
    val;

  formOrder.find('input').each(function () {
    if ($(this).attr('type') === 'checkbox') {
      val = $(this).val();

      if (!$(this).is(':checked')) {
        val = '';
      }

      props[$(this).attr('name')] = val;
    } else {
      props[$(this).attr('name')] = $.trim($(this).val());
    }
  });

  if (props['ORDER_PROP_15'].length === 0) {
    $('.js-address-block').show();
  } else {
    $('.js-address-block').hide();
  }

  if (props['ORDER_PROP_12'].length === 0) {
    $('.js-note-block').hide();
  } else {
    $('.js-note-block').show();
  }
}