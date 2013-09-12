/** WHAT THE HELL THIS ISN'T UPDATING!!!
  (well, we're not minifying now, so it is, but still)
  Don't forget you're using minified code:
  uglifyjs custom.js -o custom-min.js */
$(document).bind("pageinit",function(){
		$.mobile.defaultPageTransition = "slidefade";

		// let me access $_GET
		function getQueryParams() {
		var qs = window.location.href.split('?')[1]
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

		// take an array of ids, update the kegs (used by the #keglist csv and #kegs old school checkbox forms)
		function updateKegs(ids) {
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

			$.mobile.loading("show");
			$.post('postactions.php', vars, function(data){
					$.mobile.loading("hide");
					$('#message').html(data);
					$('#success').popup('open', {transition: 'pop'});
					});
		}

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

		// hijack the submit of a keg edit
		$('#keginfo').submit(function() {
			// grab the GET variables
			// from http://stackoverflow.com/questions/439463/how-to-get-get-and-post-variables-with-jquery

			var $_GET = getQueryParams();
			$.post('postactions.php', {edit:"keg", id:$_GET['id'], size:$_GET['size'], status:$('#status').val(), beer:$('#beer').val(), location:$('#location').val()});
			$('#success').popup("open", {transition: "pop"});
			return false; // stop the submit
		});

		$('#keginfo a.action').on('click',function(){
				var $_GET = getQueryParams();
				$.post('postactions.php',{update:"keg", status:$(this).attr('id').substr(1,1), ids:$_GET['id']+'_'+$_GET['size']});
				$('#success').popup('open', {transition: 'pop'});
				});

		$('#kegs').submit(function() {
				var ids = Array();
				$('#kegs input:checkbox:checked').each(function(){
					ids[ids.length] = $(this).attr('id').substr(3);
					});

				updateKegs(ids);
				return false; // stop the submit
				});

		// the csv list
		$('#csv').submit(function(){
				var list = $('#keglist').val();
				// if there are no kegs entered
				if (list == '') {
				$('#success h1').html('Error!');
				$('#message').html('No kegs specified');
				$('#success').popup('open', {transition: 'pop'});
				return false;
				}
				var ids = list.split(".");
				//if (ids.length == 0) alert("oi");
				for (i = 0; i < ids.length; i++) {
				ids[i] = $.trim(ids[i]) + '_1'; // assume it's a sixtel, trim whitespace
				}
				updateKegs(ids);
				return false;
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

		$('#newkeg').submit(function(){
				var vars = {new:"keg", size:$('#size').val(), id:$('#id').val(), end:$('#end').val(), status:$('#status').val() };
				var count = 1;
				if ($('#end').val()) count = $('#end').val()-$('#id').val() + 1;

				$.post('postactions.php',vars);
				var message = count + ' keg';
				if (count != 1) message = message + 's';
				message = message + ' added';
				$('#message').html(message);
				$('#success').popup('open', {transition: 'pop'});
				return false;
				});

		$('#done').on('click',function(){
				var ids = Array();
				$('#kegs input:checkbox').each(function(){
					ids[ids.length] = $(this).attr('id').substr(3);
					});
				// perhaps there's a better way to do this (ie localStorage)
				// but I sort of like the backwards compatability with php
				// that or I'm dumb
				var vars = {clean:'true', ids:ids.join('+')};
				$.post('postactions.php',vars);
				$('#message').html('All remaining kegs marked as unknown');
				$('#success').popup('open', {transition: 'pop'});
				});

		$('#warn button').on('click',function(){
				alert("hi");
				});
});

