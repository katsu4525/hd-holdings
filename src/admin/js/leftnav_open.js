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
  let body   = $('body');
  let btn   = $('.toggle_btn');
  let pankuzu = $('.pankuzu').find('.menu');
  let mask  = $('#mask');

  // menu open close
  btn.on( 'click', function() {
    if ( ! body.hasClass('slideOut') ) {
      body.addClass('slideOut');
      body.removeClass('slideIn');
      body.removeClass('open');
    } else {
      body.removeClass('slideOut');
      body.addClass('slideIn');
      body.addClass('open');
    }
  });
  // menu close close
  pankuzu.on( 'click', function() {
    if (body.hasClass('slideOut') ) {
      body.removeClass('slideOut');
      body.addClass('slideIn');
      body.addClass('open');
    }
  });
  // mask close
  mask.on('click', function() {
    body.removeClass('close');
  });
});