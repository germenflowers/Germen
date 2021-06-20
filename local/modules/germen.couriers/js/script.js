$(document).ready(function()
{
  $(document).on('submit', 'form[name=couriersForm]', function(e)
  {
    e.preventDefault();

    let form = $(this);

    $.ajax({
      type: 'POST',
      url: form.attr('action'),
      data: form.serialize(),
      beforeSend: function()
      {
        $('.js-loader').show();
        $('.js-overlay').show();
      },
      success: function(data)
      {
        data = jQuery.parseJSON(data);

        if (data.status === 'success') {
          $('form[name=couriersForm]').remove();
          $('.js-check-order').remove();
        }

        $('.js-loader').hide();
        $('.js-overlay').hide();
      },
      error: function(error)
      {
        $('.js-loader').hide();
        $('.js-overlay').hide();
      },
    });
  });
});