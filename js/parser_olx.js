jQuery(document).ready(function () {
		jQuery("#current").keydown(function (){
			if(jQuery("#current").val().indexOf(0) !== -1){
				alert("Нельзя вводить " + jQuery("#current").val());
				jQuery("#current").val('');
			}
			else {
				return true;
			}
		});	
		jQuery('.btn-success').click(function (event){
		var current = jQuery("#current").val();
		var next = jQuery('#next').val();
		if (current.length == 0 || next.length == 0 || current == 0){
			event.preventDefault();
			jQuery('.form-control').each(function() {
				jQuery('#url , #current , #next').css('border' , '1px solid red');
			});
			jQuery("<span class='empty_field'>Заповніть поля</span>").css("color" , "red").appendTo(".container-fluid");
			setTimeout(function() {
				jQuery("span.empty_field").fadeOut(500);
				jQuery('#url , #current , #next').css('border' , '');
			},1000);
		}
			else {
				jQuery('.btn-success').fadeOut(300);
				jQuery('.content_count , .loading').fadeIn(300, function() {
					    jQuery('.content_count , .loading').css('display' , 'inline');
				});
				var updateTimer = function() {
  				var cell = document.getElementById('count');
  				var count = Number(cell.innerHTML);
  				cell.innerHTML = count += 1;
				};
				setInterval(updateTimer, 1000);
			}
	});
	});