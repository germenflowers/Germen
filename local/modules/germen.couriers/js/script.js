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
          $('.js-order-status').html(data.orderStatus);
          $('.courier-tabs__link--first').addClass("js-tabs__title-active");
          $('.courier-tabs__link--second').removeClass("js-tabs__title-active");
          $(".courier-tabs__content--first").css("opacity", "1").css("display", "block");
          $(".courier-tabs__content--second").css("opacity", "0").css("display", "none");
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