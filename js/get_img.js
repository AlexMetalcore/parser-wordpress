var page = require('webpage').create();
var system = require('system');
page.injectJs('/var/www/html/renthub/wp-includes/js/jquery/jquery.js');
page.settings.userAgent = 'SpecialAgent';
page.open(img.url, function(status) {
  		var get_img = page.evaluate(function() {
  			var img_arr = [];
			jQuery('img.vtop').each(function () {
				img_arr.push(jQuery(this).attr('src'));
			});
			return img_arr;
  		});
  		console.log(get_img);
  		phantom.exit();
});
