(function (Function_prototype) {
  Function_prototype.debounce = function (delay, ctx) {
    let fn = this, timer;
    return function () {
      let args = arguments, that = this;
      clearTimeout(timer);
      timer = setTimeout(function () {
        fn.apply(ctx || that, args);
      }, delay);
    };
  };
})(Function.prototype);

$(document).ready(function () {
  pagenavigation();

  //============================ Авторизация =====================================
  let authorizationForm = $('.js-authorization-form');

  if (authorizationForm.length > 0) {
    let loginInput = authorizationForm.find('input[name=USER_LOGIN]'),
      passwordInput = authorizationForm.find('input[name=USER_PASSWORD]'),
      login = loginInput.val();

    if (typeof login !== 'undefined' && login !== null && login !== '') {
      try {
        passwordInput.focus();
      }
      catch (e) {
      }
    } else {
      try {
        loginInput.focus();
      }
      catch (e) {
      }
    }

    authorizationForm.validate({
      rules: {
        USER_PASSWORD: {
          minlength: 6
        },
      },
      messages: {
        USER_LOGIN: {
          required: 'Обязательное поле',
          email: 'Пожалуйста, введите корректный адрес электронной почты.'
        },
        USER_PASSWORD: {
          required: 'Обязательное поле',
          minlength: $.validator.format('Пожалуйста, введите не меньше {0} символов.')
        },
      },
      submitHandler: function (form) {
        form.submit();
      },
    });
  }
  //==============================================================================

  // $(document).on('click', '.js-history-filter-button', function (e) {
  //   e.preventDefault();
  //
  //   $('.js-history-filters').toggleClass('is-shown');
  // });

  let historyFilterDateEl = document.querySelector('.js-history-filter-date'),
    historyFilterPriceMinEl = document.querySelector('.js-history-filter-price-min'),
    historyFilterPriceMaxEl = document.querySelector('.js-history-filter-price-max');

  if (typeof historyFilterDateEl !== 'undefined' && historyFilterDateEl !== null) {
    historyFilterDateEl.addEventListener('input', historyFilterDate.debounce(1000));
  }

  if (typeof historyFilterPriceMinEl !== 'undefined' && historyFilterPriceMinEl !== null) {
    historyFilterPriceMinEl.addEventListener('input', historyFilterPriceMin.debounce(1000));
  }

  if (typeof historyFilterPriceMaxEl !== 'undefined' && historyFilterPriceMaxEl !== null) {
    historyFilterPriceMaxEl.addEventListener('input', historyFilterPriceMax.debounce(1000));
  }

  $(document).on('change', '.js-history-filter-status', function (e) {
    let filter = getHistoryFilter();

    filter.status = $(this).val();

    historyFilter(filter);
  });

  $(document).on('selectmenuchange', '.js-orders-type-select', function (e) {
    showLoader();

    let list = $('.aside__orders-list[data-type=' + $(this).val() + ']'),
      orderId = list.find('.js-aside-order:first').data('id');

    $('.aside__orders-list').removeClass('aside__orders-list_active');
    list.addClass('aside__orders-list_active');

    $('.js-order-steps').removeClass('steps_active');
    $('.js-order-steps[data-id=' + orderId + ']').addClass('steps_active');

    $('.js-order').removeClass('order-cover_active');
    $('.js-order[data-id=' + orderId + ']').addClass('order-cover_active');

    hideLoader();
  });

  $(document).on('click', '.js-aside-order', function (e) {
    showLoader();

    $('.js-order-steps').removeClass('steps_active');
    $('.js-order-steps[data-id=' + $(this).data('id') + ']').addClass('steps_active');

    $('.js-order').removeClass('order-cover_active');
    $('.js-order[data-id=' + $(this).data('id') + ']').addClass('order-cover_active');

    hideLoader();
  });

  $(document).on('click', '.js-order-take', function (e) {
    let self = $(this),
      container = self.parents('.js-order-steps');

    if (self.hasClass('is-active')) {
      $.ajax({
        type: 'POST',
        url: '/admin/ajax/orders.php',
        data: {
          action: 'take',
          id: self.data('id'),
        },
        beforeSend: function () {
          showLoader();
        },
        success: function (data) {
          if (data.status === 'success') {
            self.removeClass('is-active');
            container.find('.js-order-ready').addClass('is-active');

            let buildDateElement = $('.js-order-build-date[data-id=' + self.data('id') + ']'),
              buildTimerElement = $('.js-order-build-timer[data-id=' + self.data('id') + ']'),
              date = new Date(),
              buildDate = new Date(date.getTime() + buildDateElement.data('time') * 1000);

            buildDateElement.html(buildDate.getHours() + ':' + (buildDate.getMinutes() < 10 ? '0' : '') + buildDate.getMinutes());
            startTimer(buildTimerElement.data('time'), buildTimerElement);
          }

          hideLoader();
        },
        error: function (error) {
          hideLoader();
        }
      });
    }
  });

  $(document).on('click', '.js-order-ready', function (e) {
    let self = $(this),
      container = self.parents('.js-order-steps');

    if (self.hasClass('is-active')) {
      $.ajax({
        type: 'POST',
        url: '/admin/ajax/orders.php',
        data: {
          action: 'ready',
          id: self.data('id'),
        },
        beforeSend: function () {
          showLoader();
        },
        success: function (data) {
          if (data.status === 'success') {
            self.removeClass('is-active');
            container.find('.js-order-courier').addClass('is-active');
            renderBuildOrder(self.data('id'));
          }

          hideLoader();
        },
        error: function (error) {
          hideLoader();
        }
      });
    }
  });

  $(document).on('click', '.js-order-courier', function (e) {
    let self = $(this);

    if (self.hasClass('is-active')) {
      $.ajax({
        type: 'POST',
        url: '/admin/ajax/orders.php',
        data: {
          action: 'courier',
          id: self.data('id'),
        },
        beforeSend: function () {
          showLoader();
        },
        success: function (data) {
          if (data.status === 'success') {
            self.removeClass('is-active');

            reduceAsideOrdersCount('new');
            increaseAsideOrdersCount('collected');
            moveAsideElement(self.data('id'), 'collected');
            renderCollectedOrder(self.data('id'));
          }

          hideLoader();
        },
        error: function (error) {
          hideLoader();
        }
      });
    }
  });

  $(document).on('click', '.js-order-delete-button', function (e) {
    let self = $(this);

    $('form[name=deleteOrder]').find('input[name=id]').val(self.data('id'));

    $("#deleteModal").modal();
  });

  let deleteOrderForm = $('form[name=deleteOrder]');

  if (deleteOrderForm.length > 0) {
    deleteOrderForm.validate({
      rules: {},
      messages: {
        comment: {
          required: 'Обязательное поле',
        },
      },
      submitHandler: function (form) {
        $.ajax({
          type: 'POST',
          url: '/admin/ajax/orders.php',
          data: $(form).serialize(),
          beforeSend: function () {
            showLoader();
          },
          success: function (data) {
            if (data.status === 'success') {
              reduceAsideOrdersCount('new');
              increaseAsideOrdersCount('canceled');
              moveAsideElement($(form).find('input[name=id]').val(), 'canceled');
              renderCanceledOrder($(form).find('input[name=id]').val());
            }

            $("#deleteModal").modal('hide');

            hideLoader();
          },
          error: function (error) {
            hideLoader();
          }
        });
      },
    });
  }

  $('.js-order-build-timer').each(function () {
    if ($(this).data('init') === 'Y') {
      startTimer($(this).data('timestamp'), $(this));
    }
  });

  if ($('.js-orders-container').length > 0) {
    let now = new Date(),
      delay = 2 * 60 * 1000,
      checkNewOrdersTimerId = setTimeout(function tick() {
        getNewOrders(now);
        now = new Date();
        checkNewOrdersTimerId = setTimeout(tick, delay);
      }, delay);
  }
});

function showLoader() {
  $('.js-page-loader').show();
  $('.js-page-overlay').show();
}

function hideLoader() {
  $('.js-page-loader').hide();
  $('.js-page-overlay').hide();
}

function pagenavigation() {
  let flag = true,
    container = $('.js-items');

  if (container.length !== 0) {
    $(document).scroll(function () {
      let scroll = $(window).scrollTop() + $(window).height(),
        offset = container.offset().top + container.height();

      if (scroll > offset && flag) {
        flag = false;

        let pagenavigation = $('.js-pagenavigation'),
          pagenavigationParams = $('.js-pagenavigation-params'),
          id = pagenavigationParams.data('id'),
          page = pagenavigationParams.data('page'),
          size = pagenavigationParams.data('size'),
          entity = pagenavigation.data('entity'),
          url = '/admin/ajax/' + entity + '.php',
          filter = {};

        if (typeof entity != 'undefined' && pagenavigationParams.length > 0) {
          if (typeof page == 'undefined') {
            page = 1;
          }
          if (typeof size == 'undefined') {
            size = 20;
          }

          if (entity === 'history') {
            filter = getHistoryFilter();
          }

          let data = {
            action: 'pagenavigation',
            elements: size,
            filter: filter,
          };

          data[id] = 'page-' + ++page;

          $.ajax({
            type: 'GET',
            url: url,
            data: data,
            beforeSend: function () {
              showLoader();
            },
            success: function (data) {
              data = jQuery.parseJSON(data);

              container.append($('#' + entity + 'Tmpl').render(data.items));
              pagenavigation.html(data.pagenavigation);

              if (entity === 'history') {
                let accordionEl = $("#history-accordion");

                if (typeof accordionEl.accordion('instance') != 'undefined') {
                  accordionEl.accordion('destroy');
                }

                accordionEl.accordion({
                  collapsible: true,
                  active: false
                });
              }

              hideLoader();

              flag = true;
            },
            error: function () {
              hideLoader();

              flag = true;
            },
          });
        }
      }
    });
  }
}

function historyFilterDate() {
  let filter = getHistoryFilter();

  filter.date = this.value;

  historyFilter(filter);
}

function historyFilterPriceMin() {
  let filter = getHistoryFilter();

  filter.priceMin = this.value;

  historyFilter(filter);
}

function historyFilterPriceMax() {
  let filter = getHistoryFilter();

  filter.priceMax = this.value;

  historyFilter(filter);
}

function historyFilter(filter) {
  let container = $('.js-items'),
    pagenavigation = $('.js-pagenavigation'),
    data = {
      action: 'filter',
      filter: filter,
    };

  $.ajax({
    type: 'GET',
    url: '/admin/ajax/history.php',
    data: data,
    beforeSend: function () {
      showLoader();
    },
    success: function (data) {
      data = jQuery.parseJSON(data);

      container.html($('#historyTmpl').render(data.items));
      pagenavigation.html(data.pagenavigation);

      let accordionEl = $("#history-accordion");

      if (typeof accordionEl.accordion('instance') != 'undefined') {
        accordionEl.accordion('destroy');
      }

      accordionEl.accordion({
        collapsible: true,
        active: false
      });

      hideLoader();
    },
    error: function (error) {
      hideLoader();
    }
  });
}

function getHistoryFilter() {
  let filter = {},
    dateVal = $('.js-history-filter-date').val(),
    statusVal = $('.js-history-filter-status').val(),
    priceMinVal = $('.js-history-filter-price-min').val(),
    priceMaxVal = $('.js-history-filter-price-max').val();

  if (typeof dateVal !== 'undefined' && dateVal !== '') {
    filter.date = dateVal;
  }

  if (typeof statusVal !== 'undefined' && statusVal !== '') {
    filter.status = statusVal;
  }

  if (typeof priceMinVal !== 'undefined' && priceMinVal !== '') {
    filter.priceMin = priceMinVal;
  }

  if (typeof priceMaxVal !== 'undefined' && priceMaxVal !== '') {
    filter.priceMax = priceMaxVal;
  }

  return filter;
}

function startTimer(goal, timer) {
  if (goal <= 0) {
    return;
  }

  let i = a => (a - a % 1 + "").padStart(2, "0"),
    m = 60,
    h = m * m;

  goal += new Date / 1000;

  setInterval(() => {
    let t = goal - new Date / 1000;

    if (t <= 0) {
      timer.html('00:00');
      return;
    }

    timer.html([t / h, t % h / m].map(i).join(":"));
  }, 999);
}

function reduceAsideOrdersCount(type) {
  if (typeof type === 'undefined' || type === '') {
    return;
  }
}

function increaseAsideOrdersCount(type) {
  if (typeof type === 'undefined' || type === '') {
    return;
  }
}

function moveAsideElement(id, type) {
  if (typeof id === 'undefined' || id === '') {
    return;
  }

  if (typeof type === 'undefined' || type === '') {
    return;
  }

  let element = $('.js-aside-order[data-id=' + id + ']'),
    container = $('.js-aside-orders-list[data-type=' + type + ']');

  if (typeof element === 'undefined' || element === '') {
    return;
  }

  if (typeof container === 'undefined' || container === '') {
    return;
  }

  element.find('.js-order-note-container').remove();
  element.find('.js-order-delete-container').remove();

  if (type === 'collected') {
    element.find('.js-order-state-container').html($('#orderStateCollectedTmpl').render());
  }

  if (type === 'canceled') {
    element.find('.js-order-state-container').html($('#orderStateCanceledTmpl').render());
  }

  element.detach().appendTo(container);
}

function renderBuildOrder(id) {
  if (typeof id === 'undefined' || id === '') {
    return;
  }

  let container = $('.js-order[data-id=' + id + ']');

  if (typeof container === 'undefined' || container === '') {
    return;
  }

  container.html($('#orderBuildTmpl').render({ id: id }));
}

function renderCollectedOrder(id) {
  if (typeof id === 'undefined' || id === '') {
    return;
  }

  let container = $('.js-order[data-id=' + id + ']'),
    list = $('.aside__orders-list[data-type=collected]');

  if (typeof container === 'undefined' || container === '') {
    return;
  }

  $('.aside__orders-list').removeClass('aside__orders-list_active');
  list.addClass('aside__orders-list_active');

  $('.js-order-steps[data-id=' + id + ']').remove();

  container.html($('#orderCollectedTmpl').render({ id: id }));
}

function renderCanceledOrder(id) {
  if (typeof id === 'undefined' || id === '') {
    return;
  }

  let container = $('.js-order[data-id=' + id + ']'),
    list = $('.aside__orders-list[data-type=canceled]');

  if (typeof container === 'undefined' || container === '') {
    return;
  }

  $('.aside__orders-list').removeClass('aside__orders-list_active');
  list.addClass('aside__orders-list_active');

  $('.js-order-steps[data-id=' + id + ']').remove();

  container.html($('#orderCanceledTmpl').render({ id: id }));
}

function getNewOrders(date) {
  $.ajax({
    type: 'POST',
    url: '/admin/ajax/orders.php',
    data: {
      'action': 'getNewOrders',
      'timestamp': date.getTime() / 1000,
    },
    beforeSend: function () {
    },
    success: function (data) {
      if (data.status === 'success' && !$.isEmptyObject(data.orders)) {
        renderNewOrders(data.orders);
        showModalNewOrders(data.orders);
      }
    },
    error: function (error) {
    }
  });
}

function renderNewOrders(orders) {
  let asideEl = $('.js-aside');

  asideEl.show();
  $('.js-empty-orders').remove();
  $('.js-aside-orders-list[data-type=new]').append($('#asideOrderTmpl').render(orders));

  $.each(orders, function (index, order) {
    document.querySelectorAll('.js-aside-order[data-id="' + order.id + '"] .circle-timer[data-time-limit]')
    .forEach(element => new CircleTimer(element));
  });

  asideEl.after($('#orderTmpl').render(orders));
  asideEl.after($('#orderStepsTmpl').render(orders));
}

function showModalNewOrders(orders) {
  let ordersId = [],
    ordersStr = '',
    delay = 2 * 1000,
    modalEl = $("#newOrdersModal");

  $.each(orders, function (index, order) {
    ordersId.push(order.id);
  });

  ordersStr = ordersId.join(', ');

  $('.js-modal-new-orders-id').html(ordersStr);

  modalEl.modal({ backdrop: false });
  setTimeout(() => modalEl.modal('hide'), delay);

  let audio = new Audio('/local/templates/admin/audio/notification.mp3');
  audio.play().then(r => {
  }, e => {
  });
}