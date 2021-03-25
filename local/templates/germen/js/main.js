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

  animateMobileCart();

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

  let body = $('body');

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
          animateMobileCart();
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

          if (!(data.data.coupon.status === 2 || data.data.coupon.status === 4)) {
            promocodeStatusContainer.addClass('cart__promo-alert');
          }

          $('.js-promocode-container').html($.templates('#promocodeAddTmpl')
          .render(data.data.coupon));
          promocodeStatusContainer.html($.templates('#promocodeStatusTmpl')
          .render(data.data.coupon));
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

function getUrlParam(name) {
  let s = window.location.search;
  s = s.match(new RegExp(name + '=([^&=]+)'));
  return s ? s[1] : false;
}

function close(e) {
  e.preventDefault();
  slideout.close();
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
        renderMobileCart(data.data);

        if (updateCart) {
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

function renderMobileCart(data) {
  let container = $('.js-mobile-cart');
  let priceContainer = $('.js-mobile-cart-sum');

  priceContainer.html(data.sumFormat);

  if (data.sum > 0) {
    container.addClass("is-shown");
  } else {
    container.removeClass("is-shown");
  }
}

function animateMobileCart() {
  let container = $('.js-mobile-cart');
  let priceContainerString = $('.js-mobile-cart-sum').html();
  let price = parseFloat(priceContainerString);

  $( ".js-detail" ).on( "click", function() {
    if($('.js-mobile-cart.is-shown')) {
      container.removeClass("is-shown");
    }
  });

  $('.modal-content .modal-close').on( "click", function() {
    if (price > 0) {
      setTimeout(function(){
        container.addClass("is-shown");
      }, 400);
    }
  });

  $('.modal-content .product-slider .swiper-wrapper').on( "click", function() {
    if (price > 0) {
      setTimeout(function(){
        container.addClass("is-shown");
      }, 400);
    }
  });

  $('.js-product-order-button').on( "click", function() {
    setTimeout(function(){
      container.addClass("is-shown");
    }, 400);
  });
};


$('.promo-item__add-to-fav-btn').click(function(e){
  var added = $(this).hasClass('red-heart');
  if(added) {
    return;
  }
  var butWrap = $(this).parents('.content');
  butWrap.append('<div class="animtocart"></div>');
  $('.animtocart').css({
    'position' : 'absolute',
    // 'background' : '#FF323D',
    'width' :  '15px',
    'height' : '15px',
    'border-radius' : '100px',
    'z-index' : '9999999999',
    'left' : e.pageX-10,
    'top' : e.pageY-70,
    'opacity' : '0.6',
    'background-image' : 'url(local/templates/germen/img/to-fav.svg)',
    'background-repeat': 'no-repeat',
    'background-size': '100%'

  });
  var fav = $('.header__favorite').offset();
  console.log(fav.top)
  $('.animtocart').animate({ top: fav.top + 'px', left: fav.left + 'px', width: 0, height: 0 }, 800, function(){
    console.log($('.animtocart').offset())
    $(this).remove();
  });
});