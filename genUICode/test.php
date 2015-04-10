<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>


    <meta http-equiv="content-type" content="text/html; charset=utf-8">
<title>Basic Local Data</title>


<link rel="stylesheet" type="text/css" href="yui/current/build/autocomplete/assets/skins/sam/autocomplete.css" />
<script type="text/javascript" src="yui/current/build/yahoo-dom-event/yahoo-dom-event.js"></script>

<script type="text/javascript" src="yui/current/build/datasource/datasource-min.js"></script>
<script type="text/javascript" src="yui/current/build/autocomplete/autocomplete-min.js"></script>



<!--end custom header content for this example-->

</head>

<body class="yui-skin-sam">


<h1>Basic Local Data</h1>

<div class="exampleIntro">
	<p>This AutoComplete implementation points to a JavaScript array that is available in-memory, allowing for a zippy user interaction without the need for a server-side component. Enabling the <code>prehighlightClassName</code> and <code>useShadow</code> features, as well as pulling in the Animation utility, provides an ehanced visual user experience.</p>
			
</div>

<!--BEGIN SOURCE CODE FOR EXAMPLE =============================== -->

<label for="myInput">Enter a state:</label>
<div id="myAutoComplete">
	<input id="myInput" type="text">
	<div id="myContainer"></div>
</div>


<script type="text/javascript">
YAHOO.example.BasicLocal = function() {
var a = ['ramesh','pateel','kumar'];
    var states = new YAHOO.util.LocalDataSource(a);
    // Optional to define fields for single-dimensional array
    states.responseSchema = {fields : ["state"]};
alert(states);
    var oAC = new YAHOO.widget.AutoComplete("myInput", "myContainer", states);
    oAC.prehighlightClassName = "yui-ac-prehighlight";
    oAC.useShadow = true;
    
    return {
        states: states,
        oAC: oAC
    };
}();
</script>

<!--END SOURCE CODE FOR EXAMPLE =============================== -->

</body>
</html>
