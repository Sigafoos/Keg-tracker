<?php require('functions.php'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Kegs!</title>
<!--<link href="style.css" rel="stylesheet" type="text/css" media="screen" />-->
<!-- JQTouch! -->
<style type="text/css" media="screen">@import "jqtouch/themes/css/apple.css";</style>
<script src="jqtouch/src/lib/zepto.min.js" type="text/javascript" charset="utf-8"></script>
<script src="jqtouch/src/jqtouch.min.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript">
$.jQTouch({ });

$(document).ready(function(){
		// capture all taps and load them from a named php file
		// this is because as far as I can tell,
		// onclick() isn't recognized in iOS and ontap() in desktop
		$('#home li a').on('click',function() {
			// there will also be the class "active" that we need to strip out
			var section = this.className.split(/\s/)[0];
			$('#'+section+' .content').load(section+'.php');

			});

		$('#submit').on('click',function() {
			alert('form submitted');
			});
		});
</script>
<script type="text/css">
<!-- PUT THIS ELSEWHERE
#progress {
	-webkit-border-radius: 10px;
	background-color: rgba(0,0,0,.7);
color: white;
       font-size: 18px;
       font-weight: bold;
height: 80px;
left: 60px;
      line-height: 80px;
margin: 0 auto;
position: absolute;
	  text-align: center;
top: 120px;
width: 200px;
}
-->
</script>
</head>
<body>
