$(function () {
  // $('#sideBtn').on('click', function(){
  //   $('#sideBtn').toggleClass('close')
  //   $('#side').toggleClass('slideIn')
  //   // $('#mask').addClass('open')
  // });
  //   // mask close
  // $('main').on('click', function() {
  //   $('#sideBtn').removeClass('close')
  //   $('#side').removeClass('slideIn')
  // });
  var body   = $('body');
  var btn   = $('.toggle_btn');
  var pankuzu = $('.pankuzu').find('.menu');
  var mask  = $('#mask');

  // menu open close
  btn.on( 'click', function() {
    if ( ! body.hasClass('open') ) {
      body.addClass('open');
    } else {
      body.removeClass('open');
    }
  });
  // menu open close
  pankuzu.on( 'click', function() {
    if ( ! body.hasClass('open') ) {
      body.addClass('open');
    } else {
      body.removeClass('open');
    }
  });
  // mask close
  mask.on('click', function() {
    body.removeClass('open');
  });
});