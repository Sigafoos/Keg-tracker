$(document).bind("pageinit",function(){
		$.mobile.defaultPageTransition = "slidefade";

		$('select#size').change(function(){
			// get the data from 
			$.post('kegsize.php', {size:$('select#size').val()}, function(data) { $('select#id').html(data); });
			});
		});
		
