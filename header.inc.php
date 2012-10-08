<!DOCTYPE html>
<html lang="en">
<head>
<title>Kegs!</title>
<!--<link href="style.css" rel="stylesheet" type="text/css" media="screen" />-->
<!-- JQTouch! -->
<style type="text/css" media="screen">@import "jqtouch/themes/css/jqtouch.css";</style>
<script src="jqtouch/src/lib/zepto.min.js" type="text/javascript" charset="utf-8"></script>
<script src="jqtouch/src/jqtouch.min.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">
$.jQTouch({ });

$(document).ready(function(){
		// capture all taps and load them from a named php file
		// this is because as far as I can tell,
		// onclick() isn't recognized in iOS and ontap() in desktop
		$(document).on('click','li a',function() {
			var section = this.hash.substr(1);
			// this.search is null if there are no GET variables, otherwise it adds them back in
			$('#'+section).load(section+'.php'+this.search+' #'+section);

			});
		});
</script>
</head>
<body>
