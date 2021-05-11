$(document).ready(function()
{
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
});

function showLoader() {
  $('.js-page-loader').show();
  $('.js-page-overlay').show();
}

function hideLoader() {
  $('.js-page-loader').hide();
  $('.js-page-overlay').hide();
}