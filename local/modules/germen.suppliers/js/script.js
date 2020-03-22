$(document).ready(function()
{
  $(document).on('click', '.js-accepted-button', function(e)
  {
    e.preventDefault();

    let form = $('form[name=suppliersForm]');

    $.ajax({
      type: 'POST',
      url: form.attr('action'),
      data: {
        'token': form.find('input[name=token]').val(),
        'action': 'changeStatus',
        'status': 'accepted',
      },
      beforeSend: function()
      {
        $('.js-success-message').hide();
        $('.js-error-message').hide();
        $('.js-loader').show();
        $('.js-overlay').show();
      },
      success: function(data)
      {
        data = jQuery.parseJSON(data);

        if (data.error) {
          $('.js-error-message').html('Ошибка: ' + data.message).show();
        } else {
          $('.js-accepted-button').hide();
          $('.js-assembled-button').show();
          $('.js-success-message').show();
          $('.js-status').html('Принят');
        }

        $('.js-loader').hide();
        $('.js-overlay').hide();
      },
      error: function(error)
      {
        $('.js-error-message').html('Ошибка: ' + error).show();
        $('.js-loader').hide();
        $('.js-overlay').hide();
      },
    });
  });

  $(document).on('click', '.js-assembled-button', function(e)
  {
    e.preventDefault();

    let form = $('form[name=suppliersForm]');

    $.ajax({
      type: 'POST',
      url: form.attr('action'),
      data: {
        'token': form.find('input[name=token]').val(),
        'action': 'changeStatus',
        'status': 'assembled',
      },
      beforeSend: function()
      {
        $('.js-success-message').hide();
        $('.js-error-message').hide();
        $('.js-loader').show();
        $('.js-overlay').show();
      },
      success: function(data)
      {
        data = jQuery.parseJSON(data);

        if (data.error) {
          $('.js-error-message').html('Ошибка: ' + data.message).show();
        } else {
          $('.js-assembled-button').hide();
          $('.js-success-message').show();
          $('.js-status').html('Собран');
        }

        $('.js-loader').hide();
        $('.js-overlay').hide();
      },
      error: function(error)
      {
        $('.js-error-message').html('Ошибка: ' + error).show();
        $('.js-loader').hide();
        $('.js-overlay').hide();
      },
    });
  });
});