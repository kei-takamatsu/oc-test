$(function() {
	var controlBtn = $('.p-box-control li');
	var content = $('.p-item');
	var breadcrumb = $('.l-topicPath-list li:last-child span');

	var resetState = function(args) {
		args.each(function() {
			$(this).removeClass('is-active');
		})
	}

	/* GET URL PARAMETER */
	var url = window.location.search;
	var paraPool = ['product', 'real_estate', 'risk'];

	if(url.indexOf('?val=') >= 0){
		var para = url.replace('?val=', '');
		resetState(controlBtn);
		resetState(content);

		for (var i = paraPool.length - 1; i >= 0; i--) {
			if(para == paraPool[i]){
				controlBtn.eq(i).addClass('is-active');
				content.eq(i).addClass('is-active');
			}
		}
	}
	else{
		controlBtn.eq(0).addClass('is-active');
		content.eq(0).addClass('is-active');
	}


	/* SWITCH TAB*/
	controlBtn.click(function() {
		resetState(controlBtn);
		$(this).addClass('is-active');

		resetState(content);
		var position = controlBtn.index(this);
		content.eq(position).addClass('is-active');

		changeBreadcrumb();
	})

	// CHANGE BREADCRUMB
	var changeBreadcrumb = function() {
		controlBtn.each(function() {
			if($(this).hasClass('is-active')){
				breadcrumb.text($(this).text());
			}
		})
	};
	changeBreadcrumb();

	/* DISPLAY IMAGE*/
	var imgList = $('.p-display-list li');
	var displayImg = $('.p-display-image');

	imgList.click(function() {
		resetState(imgList);
		$(this).addClass('is-active');

		var imgSrc = $(this).find('img').attr('src');
		displayImg.find('img').attr('src', imgSrc)
	});
})
