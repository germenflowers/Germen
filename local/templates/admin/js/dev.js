(function (Function_prototype) {
  Function_prototype.debounce = function (delay, ctx) {
    var fn = this, timer;
    return function () {
      var args = arguments, that = this;
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
  //==============================================================================

  $(document).on('click', '.js-history-filter-button', function (e) {
    e.preventDefault();

    $('.js-history-filters').toggleClass('is-shown');
  });

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