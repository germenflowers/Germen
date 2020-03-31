$(document).ready(function()
{
  $(document).on('change', 'select[name=status]', function(e)
  {
    e.preventDefault();

    $('form[name=suppliersForm]').submit();
  });

  $(document).on('submit', 'form[name=suppliersForm]', function(e)
  {
    e.preventDefault();

    let form = $(this);

    $.ajax({
      type: 'POST',
      url: form.attr('action'),
      data: {
        'token': form.find('input[name=token]').val(),
        'action': 'changeStatus',
        'status': form.find('select[name=status]').val(),
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
          $('.js-success-message').show();
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