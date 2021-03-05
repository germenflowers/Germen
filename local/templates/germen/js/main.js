if (isTouch()) {
  $('html').addClass('touch');
} else {
  $('html').addClass('no-touch');
}

$(document).ready(function () {
  $('.selectpicker').selectpicker();

  $('.btn__loading').click(function () {
    $(this).toggleClass('btn__loading--active');
  });

  $('.slider-review').slick({});

  let $promoProduct = $('.promo-product');
  if ($promoProduct.length > 0) {
    $promoProduct.slick({
      dots: true
    });

    $('#popup-product').on('shown.bs.modal', function () {
      $promoProduct.slick('refresh');
    });
  }

  let $productSlider = $('[data-product-slider]');
  if ($productSlider.length > 0) {
    $productSlider.slick({
      dots: true,
      arrows: false
    });

    $('#popup-product').on('shown.bs.modal', function () {
      $productSlider.slick('refresh');
    });
  }

  $('#right-menu').sidr({
    name: 'sidr-right',
    side: 'right',
    source: '#header-nav',
    onOpen: function () {
      $('.mobile-menu').addClass('active');
    },
    onClose: function () {
      $('.mobile-menu').removeClass('active');
    }
  });

  $(document).mouseup(function (e) {
    let container = $("#sidr-right");

    if (!container.is(e.target)
      && container.has(e.target).length === 0) {
      $.sidr('close', 'sidr-right');
    }
  });

  let panelElement = document.getElementById('panel-body');
  let menuElement = document.getElementById('menu-body');

  if (panelElement && menuElement) {
    let slideout = new Slideout({
      'panel': document.getElementById('panel-body'),
      'menu': document.getElementById('menu-body'),
      'padding': 294,
      'tolerance': 0,
      'easing': false,
      'side': 'left'
    });

    $('.toggle-menu').click(function () {
      slideout.open();
    });

    $('.toggle-menu--inside').click(function () {
      $('.slideout-open').removeClass('slideout-open');
      slideout.close();
    });

    slideout
    .on('beforeopen', function () {
      this.panel.classList.add('panel-open');
    })
    .on('open', function () {
      this.panel.addEventListener('click', close);
    })
    .on('beforeclose', function () {
      this.panel.classList.remove('panel-open');
      this.panel.removeEventListener('click', close);
    });
  }

  $('.promo-tooltip').tooltip();

  $('#revify-phone').change(function () {
    if ($(this).is(":checked")) {
      $('.promo-order__social').removeClass("hidden");
    } else {
      $('.promo-order__social').addClass("hidden");
    }
  });

  $('#add_vase').change(function () {
    if ($(this).is(":checked")) {
      $('.promo-order__optional').addClass("promo-order__optional--active");
    } else {
      $('.promo-order__optional').removeClass("promo-order__optional--active");
    }
  });

  $('#add_comment').change(function () {
    if ($(this).is(":checked")) {
      $('#write_comment').removeClass("hidden");
    } else {
      $('#write_comment').addClass("hidden");
    }
  });

  $('#check_comment').change(function () {
    if ($(this).is(":checked")) {
      $('#write_note').removeClass("hidden");
    } else {
      $('#write_note').addClass("hidden");
    }
  });

  let hiddenClass = 'info-bar--hidden';

  $(document).on('click', '.info-bar__close', function (e) {
    $(this).closest('.info-bar').addClass(hiddenClass);
    e.preventDefault();
  });

  $('.js-promo-features-slider').each(function () {
    new Swiper(this, {
      centeredSlides: false,
      initialSlide: 0,
      slideClass: 'promo-features__item',
      slidesPerView: 3,
      wrapperClass: 'promo-features__list',
      breakpoints: {
        1029: {
          centeredSlides: true,
          initialSlide: 1,
          slidesPerView: 'auto'
        }
      }
    });
  });

  $(document).on('click', '.js-scroll-to', function (e) {
    let offset = $(this).data('offset') || 0;
    let anchorId = $(this).attr('href');
    let scrollTop = $('[data-anchor="' + anchorId.replace('#', '') + '"]').offset().top - offset;

    $('html, body').stop().animate({ scrollTop: scrollTop }, 800, 'easeOutCubic');
    e.preventDefault();
  });

  initPhoneMask('1');
  initPhoneMask('2');

  let body = $('body');

  let startDate = new Date(),
    orderData = $('.order-datetime'),
    minHours = 8,
    maxHours = 21,
    maxTime = orderData.data('maxtime'),
    minTime = orderData.data('mintime');

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

  $('.phomemask').mask("+7 (999) 999-99-99");

  let popupLogin = $('#popup-login');
  if (popupLogin.length) {
    popupLogin.modal('show');
  }

  $('.js-set_delivery').click(function () {
    $.post(
      "/ajax/actions.php",
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

  body.on('click', '.js-pagin', function () {
    let link = $(this),
      href = link.data('href');

    let nextBlock = href.split('=');
    nextBlock = '[data-pagen="' + nextBlock[0].replace('/?PAGEN_', '') + '"]';

    $.get(href, function (data) {
      link.parent().prev().append($(data).find(nextBlock).prev().html());
      link.parent().after($(data).find(nextBlock));
      link.parent().remove();
    });

    return false;
  });

  let $popupProduct = $('#popup-product');
  let $popupProductBody = $popupProduct.find('.js-body');

  body.on('click', '.js-detail', function (event) {
    if ($(event.target).closest(".js-quantity-control").length
      || $(event.target).hasClass("js-remove-basket-item")
      || $(event.target).closest(".js-order_link").length) {
      return;
    }

    let id = parseInt($(this).data('id')),
      order = '';
    if (window.location.pathname === '/order/')
      order = 'Y';

    if (id > 0) {
      $.ajax({
        url: "/ajax/actions.php",
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
            $popupProduct.modal('show');
          }
        }
      });
    }

    return false;
  });

  $popupProduct.on('shown.bs.modal', function () {
    changeNumber();
    showDiscription();
    showFeatures();
    productMainSlider();
    productAddSlider();
  });

  $popupProduct.on('hidden.bs.modal', function () {
  });

  getMap();

  let formOrder = $('#form-order');
  if (formOrder.length) {
    changeFormData(formOrder);

    formOrder.find('input').on('change', function () {
      changeFormData(formOrder);
    });
  }

  formOrder.submit(function () {
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
      $('#ORDER_PROP_2').addClass('error');
      if (firstErrorInput.length === 0)
        firstErrorInput = $('#ORDER_PROP_2');
    }

    if (props['ORDER_PROP_5'].length === 0) {
      $('#ORDER_PROP_5').addClass('error');
      if (firstErrorInput.length === 0)
        firstErrorInput = $('#ORDER_PROP_5');
    }

    for (let i = 0; i < 24; i++) {
      if (props['ORDER_PROP_4[' + i + ']'] !== undefined) {
        if (
          props['ORDER_PROP_4[' + i + ']'].length === 0 ||
          !moment(props['ORDER_PROP_4[' + i + ']'], 'DD.MM.YYYY HH:mm', true).isValid()
        ) {
          $('#ORDER_PROP_4_' + i).addClass('error');

          if (firstErrorInput.length === 0) {
            firstErrorInput = $('#ORDER_PROP_4_' + i);
          }
        }
      }
    }

    if (props['ORDER_PROP_8'].length === 0) {
      $('#ORDER_PROP_8').addClass('error');
      if (firstErrorInput.length === 0)
        firstErrorInput = $('#ORDER_PROP_8');
    }

    if (props['ORDER_PROP_9'].length === 0) {
      $('#ORDER_PROP_9').addClass('error');
      if (firstErrorInput.length === 0)
        firstErrorInput = $('#ORDER_PROP_9');
    }

    if (firstErrorInput.length > 0)
      $("html,body").animate({ scrollTop: firstErrorInput.offset().top - 40 }, 1000);
    else {
      $('.promo-order').addClass('loader');
      formOrder.find('[type="submit"]').prop('disabled', true);
      $.post(window.location.href, formOrder.serialize(), function (orderId) {
        if (orderId > 0)
          window.location.href = '/order/?ORDER_ID=' + orderId;
      });
    }

    return false;
  });

  let popupFlowers = $('#popup-flowers');
  if (popupFlowers.length) {
    popupFlowers.modal('show');
  }

  popupFlowers.on('hide.bs.modal', function () {
    window.location.href = '/';
  });

  let popupFlowersSuccess = $('#popup-flowers-success');
  if (popupFlowersSuccess.length) {
    popupFlowersSuccess.modal('show');
  }

  $(document).on('click', 'input[name=size]', function () {
    let size = $(this).val();

    $('.js-subscribe').hide();
    $('.js-subscribe[data-size=' + size + ']')
    .show()
    .filter(':first')
    .find('input[type=radio]')
    .prop('checked', true);
  });

  $(document).on('click', '.js-bouquet-type', function () {
    $('.js-bouquet-title').html($(this).data('title'));
    $('.js-bouquet-text').html($(this).data('text'));
  });

  $(document).on('click', '.promo-item__add-to-fav-btn', function (e) {
    e.preventDefault();

    processWishlist($(this), 'red-heart', false);
  });

  $(document).on('click', '.product-info__favorite-desktop', function (e) {
    e.preventDefault();

    processWishlist($(this), 'red-heart', true);
  });

  $(document).on('click', '.product-info__favorite-mob', function (e) {
    e.preventDefault();

    processWishlist($(this), 'white-heart', true);
  });

  $(document).on('change', 'input[name=cover]', function () {
    $('.product-info__cover-text').html($(this).data('text'));
  });

  $(document).on('click', '.product-add-slider__item', function (e) {
    e.preventDefault();

    let button = $(this).find('.product-add-slider__btn');

    if (!button.hasClass('js-upsale-add-to-cart')) {
      button.toggleClass('product-add-slider__btn--is-chosen');
    }
  });

  /**
   * pdv:order
   */
  $(document).on("reCalcTotal", function () {
    let discountPercent = Number($(".js-discount-percent").val());

    let $selectedUpSaleProducts = $(".js-upsale-products")
    if (!isJson($selectedUpSaleProducts.val())) {
      return false;
    }

    let products = JSON.parse($selectedUpSaleProducts.val())

    let upSaleSum = 0;

    for (let id in products) {
      let quantity = products[id]
      let price = Number($("[data-id=" + id + "]").data("price"))
      upSaleSum += (price * quantity);
    }

    upSaleSum = upSaleSum || 0;

    $(".js-upsale-sum").text(formatPrice(upSaleSum))

    let $selectedMainProducts = $(".js-main-products")
    if (!isJson($selectedMainProducts.val())) {
      return false;
    }

    let mainProducts = JSON.parse($selectedMainProducts.val())
    let mainBlock = $(".js-main-basket-item.js-basket-item-info");

    let mainPrice = mainBlock.data("price")
    let mainId = mainBlock.data("id")

    if (!mainProducts.hasOwnProperty(mainId)) {
      return false;
    }

    let mainSum = mainPrice * mainProducts[mainId];
    let mainSumElement = $(".js-main-sum");
    mainSumElement.text(formatPrice(discountPrice(mainSum, discountPercent)));
    showOldPrice(mainSumElement, mainSum)
    let totalSum = Number(mainSum) + Number(upSaleSum)
    let discountTotalSum = discountPrice(Number(mainSum), discountPercent) + Number(upSaleSum)

    let totalSumElement = $(".js-total-sum");
    totalSumElement.text(formatPrice(discountTotalSum))

    showOldPrice(totalSumElement, totalSum)

    $(".js-discount-diff").text(formatPrice(totalSum - discountTotalSum))

    $(".js-basket-item").not(".js-basket-item-base").each(function (idx, elem) {
      if (!$(elem).find(".js-main-basket-item").length) {
        return true
      }

      let price = $(elem).find(".js-basket-item-info").data("price")
      $(elem).find(".js-price").text(formatPrice(discountPrice(price, discountPercent)))
      if (discountPercent > 0) {
        showOldPrice($(elem).find(".js-price"), price)
      }
    })

    if (discountPercent <= 0) {
      $(".js-old-price").remove()
    }

  })

  $(document).on("click", ".js-add-basket", function (event) {
    event.preventDefault();

    let productType, $container, $block
    if (($block = $(this).closest(".js-upsale-block")).length) {
      productType = "upsale"
      $container = $block
    }

    if (($block = $(this).closest(".js-bookmate-block")).length) {
      productType = "bookmate"
      $container = $block
    }

    if (!productType) {
      return;
    }

    let newBasketItem = {}

    newBasketItem.name = $container.find(".js-name").text()
    newBasketItem.id = $container.data("id")

    if ($container.find(".js-image-block img").length) {
      newBasketItem.imageUrl = $container.find(".js-image-block img").attr("src")
    }


    newBasketItem.price = Number($container.find(".js-price").text())


    if (Number(newBasketItem.id) <= 0) {
      return;
    }

    let $selectedUpSaleProducts = $(".js-upsale-products")
    if (!isJson($selectedUpSaleProducts.val())) {
      $selectedUpSaleProducts.val(JSON.stringify({}))
    }

    let products = JSON.parse($selectedUpSaleProducts.val())

    if (products.hasOwnProperty(newBasketItem.id)) {
      if (newBasketItem.price <= 0) {
        return;
      }
      products[newBasketItem.id]++;
      $selectedUpSaleProducts.val(JSON.stringify(products))

      let $basketItems = $(".js-basket-item [data-id]")
      $basketItems.each(function (idx, elem) {
        if (Number($(elem).data("id")) === Number(newBasketItem.id)) {
          $(elem).find(".js-quantity-field").val(products[newBasketItem.id])
          return false;
        }
      });

      $(document).trigger("reCalcTotal");

      return;
    }

    products[newBasketItem.id] = 1;
    if (newBasketItem.price <= 0) {
      $(".js-upsale-products-block").find("[data-id=" + newBasketItem.id + "]").find(
        ".js-add-basket").hide();
    }

    if (productType === "bookmate") {
      $(".js-bookmate-block").find(".js-add-basket").hide();
    }

    $selectedUpSaleProducts.val(JSON.stringify(products))
    $(document).trigger("reCalcTotal");

    let $basketItemBase = $(".js-basket-item-base");

    let $newBasketItem = $basketItemBase.clone()

    $newBasketItem.removeClass("js-basket-item-base")
    $newBasketItem.show()

    $newBasketItem.find(".js-name").text(newBasketItem.name)
    if (productType === "bookmate" || Number(newBasketItem.price) <= 0) {
      $newBasketItem.find(".js-price-block").remove()
      $newBasketItem.find(".js-quantity-control").remove()
    } else {
      $newBasketItem.find(".js-price").text(formatPrice(newBasketItem.price))
      $newBasketItem.find(".js-detail").data("price", newBasketItem.price)
    }

    if (!newBasketItem.imageUrl || newBasketItem.imageUrl.length <= 0) {
      $newBasketItem.find(".js-image-block").remove()
    } else {
      $newBasketItem.find(".js-image-block img").attr("src", newBasketItem.imageUrl)
      $newBasketItem.find(".js-image-block img").attr("alt", newBasketItem.name)
    }

    $newBasketItem.find(".js-basket-item-info").data("id", newBasketItem.id)
    $newBasketItem.find(".js-basket-item-info").data("price", Number(newBasketItem.price))

    $newBasketItem.find(".js-basket-item-info").removeClass("js-detail")
    $(".js-basket-item").last().after($newBasketItem)
    $(document).trigger("reCalcTotal");

  })

  $(document).on("click", ".js-remove-basket-item", function (event) {
    event.preventDefault();

    if ($(this).closest(".js-main-basket-item").length) {
      window.location.href = "/"
    } else {
      let $upSaleProductBlock = $(".js-upsale-products-block")
      let $container = $(this).closest(".js-basket-item")
      let $info = $container.find(".js-basket-item-info")
      let id = Number($info.data("id"))
      if (Number($info.data("price")) <= 0 && id > 0) {
        $upSaleProductBlock.find("[data-id=" + id + "]").find(".js-add-basket").show();
      }

      if ($upSaleProductBlock.find("[data-id=" + id + "]").hasClass("js-bookmate-block")) {
        $(".js-bookmate-block").find(".js-add-basket").show();
      }

      $(this).closest(".js-basket-item").remove()

      let $selectedUpSaleProducts = $(".js-upsale-products")
      if (!isJson($selectedUpSaleProducts.val())) {
        $selectedUpSaleProducts.val(JSON.stringify({}))
      }

      let products = JSON.parse($selectedUpSaleProducts.val())

      if (products.hasOwnProperty(id)) {
        delete products[id];
        $selectedUpSaleProducts.val(JSON.stringify(products))
        $(document).trigger("reCalcTotal");
      }
    }
  })

  $(document).on("click", ".js-show-coupon-form", function (event) {
    event.preventDefault();
    $(".js-coupon-form").slideToggle();
  })

  $(document).on('click', '.js-coupon-apply', function (event) {
    event.preventDefault();
    let btn = $(this);
    let field = $(".js-coupon-field");
    let loadingClass = 'btn--loading';

    btn.removeClass('error');

    if (field.val().length <= 0) {
      field.addClass('error');
      return;
    }

    field.removeClass('error');

    btn.prop('disabled', true);
    btn.addClass(loadingClass);

    $.ajax(
      window.location.href,
      {
        dataType: "json",
        data: {
          coupon: field.val()
        },
        success: function (result) {
          afterEnterCouponOrder(result);
          btn.prop('disabled', false);
          btn.removeClass(loadingClass);

        },
        type: "POST",
      }
    )
  });

  $(document).on('click', '.js-coupon-cancel', function (event) {
    event.preventDefault()
    $.post(window.location.href, { coupon: '' }, function (result) {
      afterEnterCouponOrder(result);
    }, 'json');

    return false;
  });

  $(document).on("click", ".js-minus", function (event) {
    event.preventDefault();
    let $container = $(this).closest(".js-basket-item-info")
    let id = $container.data("id")
    let currentQuantity = $container.find(".js-quantity-field").val();

    let $currentSelectedProducts
    if ($(this).closest(".js-main-basket-item").length) {
      $currentSelectedProducts = $(".js-main-products")
    } else {
      $currentSelectedProducts = $(".js-upsale-products")
    }

    if (!isJson($currentSelectedProducts.val())) {
      $currentSelectedProducts.val(JSON.stringify({}))
    }

    let products = JSON.parse($currentSelectedProducts.val())

    if (!products.hasOwnProperty(id)) {
      return false;
    }


    if (currentQuantity <= 1) {
      if ($(this).closest(".js-main-basket-item").length) {
        window.location.href = "/"
      } else {
        delete products[id];
        $(this).closest(".js-basket-item").remove()
      }
    } else {
      products[id]--;
      $container.find(".js-quantity-field").val(products[id])
    }

    $currentSelectedProducts.val(JSON.stringify(products))
    $(document).trigger("reCalcTotal");
  })

  $(document).on('click', '.js-plus', function (event) {
    event.preventDefault();

    let $container = $(this).closest('.js-basket-item-info'),
      id = $container.data('id'),
      $currentSelectedProducts;

    if ($(this).closest('.js-main-basket-item').length) {
      $currentSelectedProducts = $('.js-main-products');
    } else {
      $currentSelectedProducts = $('.js-upsale-products');
    }

    if (!isJson($currentSelectedProducts.val())) {
      $currentSelectedProducts.val(JSON.stringify({}));
    }

    let products = JSON.parse($currentSelectedProducts.val())

    if (!products.hasOwnProperty(id)) {
      return false;
    }

    products[id]++;
    $container.find('.js-quantity-field').val(products[id]);
    $currentSelectedProducts.val(JSON.stringify(products));

    $(document).trigger('reCalcTotal');
  });

  $(document).on("change", ".js-quantity-field", function (event) {
    event.preventDefault();
    let $container = $(this).closest(".js-basket-item-info")
    let id = $container.data("id")
    let $currentSelectedProducts
    if ($(this).closest(".js-main-basket-item").length) {
      $currentSelectedProducts = $(".js-main-products")
    } else {
      $currentSelectedProducts = $(".js-upsale-products")
    }

    if (!isJson($currentSelectedProducts.val())) {
      $currentSelectedProducts.val(JSON.stringify({}))
    }

    let products = JSON.parse($currentSelectedProducts.val())

    if (!products.hasOwnProperty(id)) {
      return false;
    }

    let newQuantity = $(this).val()

    if (isNaN(Number(newQuantity)) || Number(newQuantity) <= 0) {
      newQuantity = 1
      $(this).val(newQuantity)
    }

    products[id] = newQuantity

    $currentSelectedProducts.val(JSON.stringify(products))
    $(document).trigger("reCalcTotal");
  })

  $(document).trigger("reCalcTotal");
  /**
   * End pdv:order
   */

  /** cart **/
  $(document).on('click', '.js-add-to-cart', function (e) {
    e.preventDefault();

    let self = $(this),
      id = self.data('id');

    ajaxAddToCart({ action: 'add', id: id });
  });

  $(document).on('click', '.js-upsale-add-to-cart', function (e) {
    e.preventDefault();

    let self = $(this),
      id = self.data('id'),
      productId = self.data('productid');

    ajaxAddToCart({ action: 'addUpsale', id: id, productId: productId }, true, true);
  });

  $(document).on('click', '.js-product-order-button', function (e) {
    e.preventDefault();

    let params = {
      id: $(this).data('id'),
      quantity: $('input[name=product-quantity]').val(),
      cover: $('input[name=cover]:checked').val(),
      upsale: [],
    };

    $('.js-upsale-button').each(function () {
      if ($(this).hasClass('product-add-slider__btn--is-chosen')) {
        params.upsale.push($(this).data('id'));
      }
    });

    ajaxAddToCart({ action: 'add', id: params.id, params: params });
  });

  $(document).on('submit', 'form[name=subscribeForm]', function (e) {
    e.preventDefault();

    let self = $(this),
      params = self.serializeObject();

    params.subscribe = 'Y';

    ajaxAddToCart({ action: 'add', id: params.id, params: params });
  });

  $(document).on('click', '.js-cart-item-delete', function (e) {
    e.preventDefault();

    let self = $(this),
      id = self.data('id'),
      productId = self.data('productid');

    $.ajax({
      type: 'POST',
      url: '/ajax/cart.php',
      data: {
        action: 'delete',
        id: id,
      },
      beforeSend: function () {
        $('.js-pagenavigation-loader').show();
        $('.js-pagenavigation-overlay').show();
      },
      success: function (data) {
        data = jQuery.parseJSON(data);

        if (data.status === 'success') {
          renderHeaderCartItemsCount(data.data.count);

          $('.js-upsale-add-to-cart').each(function (index) {
            if ($(this).data('id') === productId) {
              $(this).removeClass('product-add-slider__btn--is-chosen');
            }
          });

          if (data.data.count > 0) {
            $('.js-cart-list').html($.templates('#cartTmpl').render(data.data));
            $('.js-cart-sum').html($.templates('#cartSumTmpl').render(data.data));
          } else {
            $('.js-cart').html($.templates('#cartEmptyTmpl').render());
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

  $(document).on('click', '.js-cart-item-minus', function (e) {
    e.preventDefault();

    let self = $(this),
      id = self.data('id'),
      productId = self.data('productid');

    $.ajax({
      type: 'POST',
      url: '/ajax/cart.php',
      data: {
        action: 'minus',
        id: id,
      },
      beforeSend: function () {
        $('.js-pagenavigation-loader').show();
        $('.js-pagenavigation-overlay').show();
      },
      success: function (data) {
        data = jQuery.parseJSON(data);

        if (data.status === 'success') {
          renderHeaderCartItemsCount(data.data.count);

          if (data.delete) {
            $('.js-upsale-add-to-cart').each(function (index) {
              if ($(this).data('id') === productId) {
                $(this).removeClass('product-add-slider__btn--is-chosen');
              }
            });
          }

          if (data.data.count > 0) {
            $('.js-cart-list').html($.templates('#cartTmpl').render(data.data));
            $('.js-cart-sum').html($.templates('#cartSumTmpl').render(data.data));
          } else {
            $('.js-cart').html($.templates('#cartEmptyTmpl').render());
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

  $(document).on('click', '.js-cart-item-plus', function (e) {
    e.preventDefault();

    let self = $(this),
      id = self.data('id');

    $.ajax({
      type: 'POST',
      url: '/ajax/cart.php',
      data: {
        action: 'plus',
        id: id,
      },
      beforeSend: function () {
        $('.js-pagenavigation-loader').show();
        $('.js-pagenavigation-overlay').show();
      },
      success: function (data) {
        data = jQuery.parseJSON(data);

        if (data.status === 'success') {
          renderHeaderCartItemsCount(data.data.count);
          $('.js-cart-list').html($.templates('#cartTmpl').render(data.data));
          $('.js-cart-sum').html($.templates('#cartSumTmpl').render(data.data));
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

  $(document).on('submit', 'form[name=promocodeForm]', function (e) {
    e.preventDefault();

    let self = $(this),
      params = self.serializeObject(),
      promocodeStatusContainer = $('.cart__promo-status'),
      buttonContainer = $('.js-promocode-button-container');

    promocodeStatusContainer.removeClass('cart__promo-alert').html('');

    $.ajax({
      type: 'POST',
      url: '/ajax/cart.php',
      data: {
        action: 'couponAdd',
        coupon: params.coupon
      },
      beforeSend: function () {
        buttonContainer.html($.templates('#promocodeButtonLoadingTmpl').render());
      },
      success: function (data) {
        data = jQuery.parseJSON(data);

        if (data.status === 'success') {
          $('.js-cart-list').html($.templates('#cartTmpl').render(data.data));
          $('.js-cart-sum').html($.templates('#cartSumTmpl').render(data.data));

          if(!(data.data.coupon.status === 2 || data.data.coupon.status === 4)) {
            promocodeStatusContainer.addClass('cart__promo-alert');
          }

          $('.js-promocode-container').html($.templates('#promocodeAddTmpl').render(data.data.coupon));
          promocodeStatusContainer.html($.templates('#promocodeStatusTmpl').render(data.data.coupon));
          buttonContainer.html($.templates('#promocodeButtonDeleteTmpl').render());
        } else {
          buttonContainer.html($.templates('#promocodeButtonAddTmpl').render());
        }
      },
      error: function (error) {
        buttonContainer.html($.templates('#promocodeButtonAddTmpl').render());
      }
    });
  });

  $(document).on('click', '.js-promocode-delete', function (e) {
    e.preventDefault();

    let buttonContainer = $('.js-promocode-button-container');

    $.ajax({
      type: 'POST',
      url: '/ajax/cart.php',
      data: {
        action: 'couponDelete',

      },
      beforeSend: function () {
        buttonContainer.html($.templates('#promocodeButtonLoadingTmpl').render());
      },
      success: function (data) {
        data = jQuery.parseJSON(data);

        if (data.status === 'success') {
          $('.js-cart-list').html($.templates('#cartTmpl').render(data.data));
          $('.js-cart-sum').html($.templates('#cartSumTmpl').render(data.data));

          $('.js-promocode-container').html($.templates('#promocodeDeleteTmpl').render());
          $('.cart__promo-status').removeClass('cart__promo-alert').html('');
          buttonContainer.html($.templates('#promocodeButtonAddTmpl').render());
        } else {
          buttonContainer.html($.templates('#promocodeButtonDeleteTmpl').render());
        }
      },
      error: function (error) {
        buttonContainer.html($.templates('#promocodeButtonDeleteTmpl').render());
      }
    });
  });
  /** end cart **/

  /** checkout **/
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
          renderOrderSummary();

          // renderHeaderCartItemsCount(data.data.count);
          //
          // $('.js-upsale-add-to-cart').each(function (index) {
          //   if ($(this).data('id') === productId) {
          //     $(this).removeClass('product-add-slider__btn--is-chosen');
          //   }
          // });
          //
          // if (data.data.count > 0) {
          //   $('.js-cart-list').html($.templates('#cartTmpl').render(data.data));
          //   $('.js-cart-sum').html($.templates('#cartSumTmpl').render(data.data));
          // } else {
          //   $('.js-cart').html($.templates('#cartEmptyTmpl').render());
          // }
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

            $('.js-order-cart').html($.templates('#orderCartTmpl').render(data.data));
          } else {

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
  /** end checkout **/
});

(function () {
  $(document).ready(function () {
    $(document).on('click', '.mc-closeModal', function () {
      $('body').css('overflow', '');
    });
  });
})();

function isTouch() {
  return !!(('ontouchstart' in window) || window.DocumentTouch && document instanceof DocumentTouch);
}

function initPhoneMask(num) {
  let element = $('#phone-mask-' + num);

  if (element.length > 0) {
    let maskList = $.masksSort($.masksLoad('/local/templates/germen/js/phone-codes.json'),
      ['#'],
      /[0-9]|#/,
      'mask'),
      maskOpts = {
        inputmask: {
          definitions: {
            '#': {
              validator: '[0-9]',
              cardinality: 1,
            },
          },
          //clearIncomplete: true,
          showMaskOnHover: false,
          autoUnmask: true,
        },
        match: /[0-9]/,
        replace: '#',
        list: maskList,
        listKey: 'mask',
        onMaskChange: function () {
          $(this).attr('placeholder', $(this).inputmask('getemptymask').join(''));
        },
      },
      checkbox = element,
      input = $('.js-phone-' + num);

    checkbox.change(function () {
      if (checkbox.is(':checked')) {
        input.inputmasks(maskOpts);
      } else {
        input.inputmask('+[####################]', maskOpts.inputmask).attr('placeholder',
          input.inputmask('getemptymask'));
      }
    });

    checkbox.change();
  }
}

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

  if (props['ORDER_PROP_15'].length === 0)
    $('.js-address-block').show();
  else
    $('.js-address-block').hide();

  if (props['ORDER_PROP_7'].length === 0)
    $('#social-wrap').hide();
  else
    $('#social-wrap').show();

  if (props['ORDER_PROP_12'].length === 0)
    $('#note-wrap').hide();
  else
    $('#note-wrap').show();

  let price = 0;
  price += parseInt($('.promo-order__item').data('price'));

  $('.js-orderprice').text($.number(price, 0, '', ' '));
}

function afterEnterCoupon(result) {
  let couponWrap = $('#coupon_wrap');

  couponWrap.find('.promo-order__error').hide();
  if (result.STATUS_ENTER === 'BAD')
    couponWrap.find('.promo-order__error').show();
  else {
    couponWrap.find('.input__wrapper input').val('');
    couponWrap.find('.input__wrapper').show();
    couponWrap.find('.promo-order__coupon__discount').hide();
    couponWrap.find('button').show();
    couponWrap.find('.promo-order__coupon__sum').hide();

    if (result.COUPON) {
      couponWrap.find('.input__wrapper').hide();

      couponWrap.find('.promo-order__coupon__discount')
      .html(result.DISCOUNT_NAME + ' (<strong>' + result.COUPON + '</strong>)<span class="promo-order__coupon__discount__cancel">Отменить</span>');
      couponWrap.find('.promo-order__coupon__discount').show();

      couponWrap.find('button').hide();
      if (result.OLD_PRICE > 0) {
        couponWrap.find('.promo-order__coupon__sum')
        .html('-' + (result.OLD_PRICE - result.PRICE) + ' <span class="rouble"></span>');
        couponWrap.find('.promo-order__coupon__sum').show();
      }
    }
  }

  let priceProd = '';
  if (result.OLD_PRICE > 0)
    priceProd += '<span class="promo-order__item__price__old">' + $.number(result.OLD_PRICE,
      0,
      '',
      ' ') + ' <span class="rouble"></span></span> ';
  priceProd += $.number(result.PRICE, 0, '', ' ') + ' <span class="rouble"></span>';
  $('.js-prod_price').html(priceProd);

  let vaseInput = $('#ORDER_PROP_11');

  let priceTotal = '',
    priceVase = 0;
  if (vaseInput.is(':checked'))
    priceVase = parseInt(vaseInput.data('price'));
  if (result.OLD_PRICE > 0)
    priceTotal += '<span class="promo-order__submit__old-price" data-price="' + result.OLD_PRICE + '">' + $.number(
      (result.OLD_PRICE + priceVase),
      0,
      '',
      ' ') + ' <span class="rouble"></span></span>';
  priceTotal += '<span class="js-orderprice">' + $.number((result.PRICE + priceVase),
    0,
    '',
    ' ') + '</span> <span class="rouble"></span>';
  $('.js-order_total').html(priceTotal);
}

function getUrlParam(name) {
  let s = window.location.search;
  s = s.match(new RegExp(name + '=([^&=]+)'));
  return s ? s[1] : false;
}

function close(e) {
  e.preventDefault();
  slideout.close();
}

function getMap() {
  let deliveryMap,
    myCollection,
    searchControl,
    addressProp = $("#ORDER_PROP_2"),
    searchWrap = $('#search-street');

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

function getCookie(name) {
  let matches = document.cookie.match(new RegExp(
    '(?:^|; )' + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + '=([^;]*)',
  ));

  return matches ? decodeURIComponent(matches[1]) : undefined;
}

function setCookie(name, value, options) {
  options = options || {};

  let expires = options.expires;

  if (typeof expires == 'number' && expires) {
    let d = new Date();
    d.setTime(d.getTime() + expires * 1000);
    expires = options.expires = d;
  }
  if (expires && expires.toUTCString) {
    options.expires = expires.toUTCString();
  }

  value = encodeURIComponent(value);

  let updatedCookie = name + '=' + value;

  for (let propName in options) {
    updatedCookie += '; ' + propName;
    let propValue = options[propName];
    if (propValue !== true) {
      updatedCookie += '=' + propValue;
    }
  }

  document.cookie = updatedCookie;
}

function processWishlist(element, cssClass, editListItem) {
  let self = element,
    id = self.data('id').toString(),
    deleteElement = self.data('delete') === 'Y',
    wishlist = getCookie('wishlist');

  self.toggleClass(cssClass);

  if (editListItem) {
    $('.promo-item__add-to-fav-btn[data-id=' + id + ']').toggleClass('red-heart');
  }

  if (deleteElement) {
    $(self).parents('.promo-catalog__block').remove();

    let container = $('.js-favorite-block');
    if (container.find('.promo-catalog__block').length === 0) {
      container.hide();
      $('.js-favorite-empty-block').show();
    }
  }

  if (typeof wishlist != 'undefined') {
    wishlist = wishlist.split('|');
  } else {
    wishlist = [];
  }

  wishlist = wishlist.filter(function (el) {
    return el !== '';
  });

  if (jQuery.inArray(id, wishlist) !== -1) {
    wishlist = jQuery.grep(wishlist, function (value) {
      return value !== id;
    });

    if (wishlist.length === 0) {

    }
  } else {
    wishlist.push(id);
  }

  $('.js-favorites-counter').html(wishlist.length);

  wishlist = wishlist.join('|');

  setCookie('wishlist', wishlist, { path: '/' });
}

function ajaxAddToCart(params, updateCart, isCartUpsale) {
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
        renderHeaderCartItemsCount(data.data.count);

        if(updateCart) {
          $('.js-cart-list').html($.templates('#cartTmpl').render(data.data));
          $('.js-cart-sum').html($.templates('#cartSumTmpl').render(data.data));
        }

        if (isCartUpsale) {
          $('.js-upsale-add-to-cart').each(function (index) {
            if ($(this).data('id') === params.productId) {
              $(this).addClass('product-add-slider__btn--is-chosen');
            }
          });
        }
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

function renderHeaderCartItemsCount(count) {
  $('.js-cart-counter').html(count);
}

function renderOrderSummary(data) {
  $('.js-order-sum').html(data.sumFormat);
  $('.js-goods-sum').html(data.goodsPriceFormat);
  $('.js-upsale-sum').html(data.upsalePriceFormat);
}

/**
 * pdv:order
 */

function afterEnterCouponOrder(result) {
  if (result.STATUS_ENTER === 'BAD') {
    $('.js-coupon-error').show();
    $(".js-discount-percent").val(0)
  } else {
    if (result.COUPON) {
      $(".js-coupon-name").text(result.DISCOUNT_NAME)
      $(".js-coupon-value").text(result.COUPON)
      $(".js-coupon-form").hide();
      $(".js-show-coupon-form").hide();
      $(".js-coupon-applied").show();
      $(".js-coupon-field").val("")
      $(".js-coupon-error").hide()
      $(".js-discount-percent").val(result.PERCENT)
    } else {
      $(".js-show-coupon-form").show();
      $(".js-coupon-applied").hide();
      $(".js-discount-percent").val(0)
    }
  }

  $(document).trigger("reCalcTotal");
}

function isJson(string) {
  try {
    JSON.parse(string);
    return true;
  }
  catch (e) {
    return false;
  }
}

function formatPrice(price) {
  price = String(Math.round(price));
  return price.toString().replace(/(\d)(?=(\d{3})+$)/g, '$1 ');
}

function discountPrice(price, percent) {
  if (price <= 0 || percent <= 0) {
    return price;
  }
  return price - ((price * percent) / 100)
}

function showOldPrice($priceElem, price) {
  if ($priceElem.siblings(".js-old-price").length > 0) {
    $priceElem.siblings(".js-old-price").find(".js-old-price-value").text(formatPrice(price))
    return;
  }
  $priceElem.before(
    '<span class="js-old-price promo-order__submit__old-price"><span class="js-old-price-value">' + formatPrice(
    price) + '</span><span class="rouble"></span></span>')
}

/**
 * End pdv:order
 */
