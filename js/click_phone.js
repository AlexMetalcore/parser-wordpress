var page = require('webpage').create();
var system = require('system');
page.injectJs('https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js');
//var url = 'https://www.olx.ua/obyavlenie/eksklyuzivne-dzerkalne-avto-na-vesllya-hyundai-sonata-prokat-orenda-IDAqitv.html#05d220641f';
page.settings.userAgent = 'SpecialAgent';
page.open(params.url, function(status) {
    	var get_telephone = page.evaluate(function() {
			jQuery('.spoiler').click();
  		});
	setTimeout(function () {
		var get_tel = page.evaluate(function() {
			var tel = jQuery('.fnormal').text();
			tel = tel.replace(/\(|\)/g, '').replace(/\D/g, "");
 			if(tel.indexOf('8') == 0) {
				tel = tel.replace('8', '');
                	return tel.substr(0 , 10);
            		}
			if(tel.indexOf('30') == 0) {
                		tel = tel.replace('3', '');
                	return tel.substr(0 , 10);
            		}
			if(tel.indexOf('38') == 0){
				tel = tel.replace('38', "");
                	return tel.substr(0 , 10);
            		}
            		if(tel.indexOf('7') == 0) {
                	return tel.substr(0 , 11);
            		}
			return tel.substr(0 , 10);
		});
		console.log(get_tel);
  		phantom.exit();
	}, 200);
});

