<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<style type="text/css">
body {
	margin:0;
	padding:0;
	width: 100%;
}
.gradient {
	background-image: -webkit-gradient(  linear,  left bottom,  left top,  color-stop(0.28, rgb(255,201,116)),  color-stop(0.64, rgb(228,143,10)) );
	background-image: -moz-linear-gradient(  center bottom,  rgb(255,201,116) 28%,  rgb(228,143,10) 64% );
	width:300px;
	height:150px;
	padding:5px;
}

h1 {
	font:bold 14px/1.5em "Trebuchet MS", Trebuchet, Arial, Verdana, Sans-serif;
	text-transform:uppercase;
}
div.background {
	position:absolute;
	left:0px;
	top:0px;
	z-index:-1;
}
div.background img {
	position:fixed;
	list-style: none;
	left:0px;
	top:0px;
}
div.background ul li.show {
	z-index:500
}
</style>
<script type="text/javascript" src="js/jquery-1.10.1.min.js"></script>
<script type="text/javascript">

function thebackground() {
$('div.background img').css({opacity: 0.0});
$('div.background img:first').css({opacity: 1.0});
setInterval('change()',5000);
}

function change() {
var current = ($('div.background img.show')? $('div.background img.show') : $('div.background img:first'));
if ( current.length == 0 ) current = $('div.background img:first');
var next = ((current.next().length) ? ((current.next().hasClass('show')) ? $('div.background img:first') :current.next()) : $('div.background img:first'));
next.css({opacity: 0.0})
.addClass('show')
.animate({opacity: 1.0}, 1000);
current.animate({opacity: 0.0}, 1000)
.removeClass('show');
};

$(document).ready(function() {
thebackground();	
$('div.background').fadeIn(1000); // works for all the browsers other than IE
$('div.background img').fadeIn(1000); // IE tweak
});

</script>
</head>
<body>
<div class="background"> <img src="images/bgImage/img1.jpg" style="width: 100%;" alt="pic1" /> <img src="images/bgImage/img2.jpg" style="width: 100%;" alt="pic2" /> <img src="images/bgImage/img3.jpg" style="width: 100%;" alt="pic3" /></div>
</body>
</html>
