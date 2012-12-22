$(document).bind("pageinit",function(){
		$.mobile.defaultPageTransition = "slidefade";

		// archive a beer/location
		$('a.confirm').on('click',function(){
			var theid = $(this).attr('id');

			if (theid.substr(0,1) == "b") { // beer
			$('#confirm a.archive').on('click',function(){
				$.post('archive.php', {beer:theid.substr(1)});
				$('#confirm').popup('close');
				$('#'+theid).parent().fadeOut('slow');
				});
			} else if (theid.substr(0,1) == "l") { // location
			$('#confirm a.archive').on('click',function(){
				$.post('archive.php', {location:theid.substr(1)});
				$('#confirm').popup('close');
				$('#'+theid).parent().fadeOut('slow');
				});
			}
			$('#confirm').popup('open');
			});

		// update the select with the available kegs
		$('select#size').change(function(){
			// get the data from keginfo, display the list in #id
				var url = window.location.pathname.substr(1);
			//$.post(url, {size:$('select#size').val(), update:"yes"}, function(data) { alert(data); });
				//alert($('#id')[0].outerHTML);
			$.post(url, {size:$('select#size').val(), update:"yes"}, function(data) { $('#id')[0].outerHTML = data; });
			});

		// hijack the submit of a keg edit
		$('#keginfo').submit(function() {
			// grab the GET variables
			// from http://stackoverflow.com/questions/439463/how-to-get-get-and-post-variables-with-jquery
			function getQueryParams(qs) {
			qs = qs.split("+").join(" ");
			var params = {},
			tokens,
			re = /[?&]?([^=]+)=([^&]*)/g;

			while (tokens = re.exec(qs)) {
			params[decodeURIComponent(tokens[1])]
			= decodeURIComponent(tokens[2]);
			}

			return params;
			}

			var $_GET = getQueryParams(document.location.search);

			$.post('keg.php', {edit:"yes", id:$_GET['id'], size:$_GET['size'], status:$('#status').val(), beer:$('#beer').val(), location:$('#location').val()});
			$('#success').popup("open", {transition: "pop"});
			return false; // stop the submit
		});

		$('#kegs').submit(function() {
				var ids = Array();
				$('#kegs input:checkbox:checked').each(function(){
					ids[ids.length] = $(this).attr('id').substr(3);
					});
				// perhaps there's a better way to do this (ie localStorage)
				// but I sort of like the backwards compatability with php
				// that or I'm dumb
				var vars = {update:'stuff', ids:ids.join('+')};
				var action = $('#kegs form').attr('action');
				var qs = action.substr(action.indexOf('?')+1).split('&');
				for (var i = 0; i < qs.length; i++) {
				var delimiter = qs[i].indexOf('=');
				vars[qs[i].substr(0,delimiter)] = qs[i].substr(delimiter+1);
				}

				$.post('postactions.php', vars, function(data){$('#message').html(data);});
				$('#success').popup('open', {transition: 'pop'});
				return false; // stop the submit
				});

		// new beer submit
		$('#new').submit(function(){
				if ($('#beer').val() != undefined) $.post('postactions.php',{new:"beer",beer:$('#beer').val()},function(data){$.mobile.changePage('fill.php?beer='+data);});
				else if ($('#location').val() != undefined) $.post('postactions.php',{new:"location",location:$('#location').val()},function(data){$.mobile.changePage('deliver.php?location='+data);});
				return false; // don't submit
				});

		// re-activate a deactivated beer
		$('.activate').on('click',function(){
				$.post('postactions.php',{activate:$(this).attr('id')});
				$(this).fadeOut('fast');
				});

});

