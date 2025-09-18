$(function () {
	// メニューの開閉
	$('.header_toggle').click(function () {
		$('.header_nav_list').toggleClass('active');

		if ($('.header_nav_list').hasClass('active')) {
			$('.header_nav_list').addClass('active');
		} else {
			$('.header_nav_list').removeClass('active');
		}
	});

	// ×ボタンをクリックするとナビが閉じる
	$('.header_nav_close').click(function () {
		$('.header_nav_list').toggleClass('active');
	
		if ($('.header_nav_list').hasClass('active')) {
			$('.header_nav_list').addClass('active');
		} else {
			$('.header_nav_list').removeClass('active');
		}
	});
	
	// アイコン差し込み
	$('.header_logo').find('a').prepend('<img src="./img/book.svg" alt="本のアイコン" width="24" heght="24">');
});

