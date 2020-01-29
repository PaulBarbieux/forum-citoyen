jQuery(document).ready(function(){
	var delay = (function(){
		var timer = 0;
		return function(callback, ms){
			clearTimeout (timer);
			timer = setTimeout(callback, ms);
		};
	})();
	$(window).resize(function() {
		delay(function() {
			$('.equal-heights .same-height').removeAttr("style");
			$('.equal-heights').each(function(){
				if ($(this).children('DIV:first-child').css("float") == "left") {
					rowHeight = ($(this).height());
					$(this).find(".same-height").css("min-height",rowHeight);
				}
			});
		}, 10);
	});
	$(window).resize();
});