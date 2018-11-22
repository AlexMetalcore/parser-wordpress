jQuery(document).ready(function () {
		jQuery("#current").change(function (){
			if(jQuery("#current").val().indexOf(0) !== -1){
				alert("Нельзя вводить " + jQuery("#current").val());
				jQuery("#current").val('');
			}
			else {
				return true;
			}
		});	
		jQuery('.action').click(function (event){
			var current = jQuery("#current").val();
			var next = jQuery('#next').val();
			if (current.length == 0 || next.length == 0 || current == 0){
				event.preventDefault();
				jQuery('#url , #current , #next').css('border' , '1px solid red');
				jQuery('.empty_field').fadeOut(500);
				setTimeout(function() {
					jQuery(".empty_field").fadeOut(500);
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

		var field_option = function (e) {
			jQuery('.form-control').each(function() {
				object_input = jQuery(this);
				var input_value = object_input.val();
				if(input_value.length == 0) {
					e.preventDefault();
					object_input.css('border' , '1px solid red');
					jQuery(".empty_field").first().fadeIn(500);
				}
			});
			setTimeout(function() {
				jQuery('.empty_field').fadeOut(500);
				jQuery('.form-control').css('border' , '');
			},1000);
		}
		jQuery('#save-option-parser').click(field_option);
		jQuery('.delete-value').click(function(){
			jQuery(this).prev().val('');
		})
	});