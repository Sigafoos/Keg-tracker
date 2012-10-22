$(document).bind("pageinit",function(){
		$.mobile.defaultPageTransition = "slidefade";

		$('select#size').change(function(){
			// get the data from keginfo, display the list in #id
			$.post('keg.php', {size:$('select#size').val(), list:"yes"}, function(data) { $('select#id').html(data); });
			});

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

});

