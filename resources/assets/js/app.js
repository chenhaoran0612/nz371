window._ = require('lodash');
window.$ = window.jQuery = require('jquery');
require('bootstrap-sass');

require('jquery-slimscroll');
require('icheck');
require('jquery-ui/ui/widgets/autocomplete');
window.toastr = require('toastr');
window.echarts = require('echarts/dist/echarts.min');

window.Highcharts = require('highcharts');
require('highcharts/modules/exporting')(window.Highcharts);
require('./waves');
require('./navbar');
require('./select');
require('./sweetalert');
require('./upload');
window.moment = require('moment');
moment.locale('zh-cn');

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});




$(function () {
  var search = window.location.search + '';
  if (search) {
      search = search.replace('?', '').split('&');
      for (var i = 0; i < search.length; i++) {
          $('#'+search[i].split('=')[0]).val(decodeURI(search[i].split('=')[1]));
      }
  }
  $(".searchs").submit(function(t){t.preventDefault(),window.location.href=window.location.pathname+"?"+$(this).serialize()});


  // MetsiMenu
  $('#side-menu').metisMenu();

  $('#side-menu a').each(function(i, e){
    var path = window.location.pathname;
    var $ele = $(e);
    if (path.indexOf($ele.attr('href')) == 0) {
      $ele.parent('li').parent('ul').addClass('in');
      $ele.parent('li').parent('ul').prev().addClass('active');
      $ele.parent('li').parent('ul').prev().parent('li').addClass('active');
      $ele.addClass('active');
    }
  });
  if ($(this).width() < 769) {
    $('body').addClass('body-small')
  } else {
    $('body').removeClass('body-small')
  }

  //searchButton
  $('.float-btn-group').mouseover(function(){
      $(this).addClass('open');
  });

  $('.float-btn-group').mouseleave(function(){
      $(this).removeClass('open');
  });

  

// check if browser support HTML5 local storage
function localStorageSupport() {
  return (('localStorage' in window) && window['localStorage'] !== null)
}

// For demo purpose - animation css script
function animationHover(element, animation) {
  element = $(element);
  element.hover(
    function () {
      element.addClass('animated ' + animation);
    },
    function () {
            //wait for animation to finish before removing classes
            window.setTimeout(function () {
              element.removeClass('animated ' + animation);
            }, 2000);
          });
}



// Dragable panels
function WinMove() {
  var element = "[class*=col]";
  var handle = ".ibox-title";
  var connect = "[class*=col]";
  $(element).sortable(
  {
    handle: handle,
    connectWith: connect,
    tolerance: 'pointer',
    forcePlaceholderSize: true,
    opacity: 0.8
  })
  .disableSelection();
}
});


$('[data-toggle="tooltip"]').tooltip();


