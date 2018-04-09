$(function () {
  $('.login-form').submit(function () {
    $.ajax({
      url: '/front-login',
      type: 'post',
      data: $('.login-form').serialize(),
      success: function (res) {
        if (res.result) {
          window.location.href = '/home'
        } else {
          $('.login-tip').html(res.message).removeClass('hide').addClass('show')
        }
      }
    })
    return false
  })
  $('.login-form input').focus(function () {
    $('.login-tip').removeClass('show').addClass('hide')
  })
});
