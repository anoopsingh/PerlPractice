<?php
    header('Pragma: private');
    header( "Expires: Mon, 20 Dec 1998 01:00:00 GMT" );
    header( "Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT" );
    header( "Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0" );
    header( "Pragma: no-cache" );
    
    header ("Content-type: application/vnd.mozilla.xul+xml; charset=iso-8859-15");
    echo '<' . '?xml version="1.0" encoding="iso-8859-15" ?' . '>';
    echo '<' . '?xml-stylesheet href="chrome://global/skin/" type="text/css"?' . '>' . "";
    echo '<' . '?xml-stylesheet href="/inc/sitedb.css" type="text/css"?' . '>' . "";
    echo '<?xul-overlay href="overlay/main_menubar.xul"?>';

?>
<?xul-overlay href="/overlay/globaljs_overlay.xul"?>
<window id="amazWindow"
        title="genSmart"
        xmlns="http://www.mozilla.org/keymaster/gatekeeper/there.is.only.xul"
        width="device-width"
        height="600"
        type="gensmart::main"
        persist="screenX screenY width height sizemode">
        
<link rel="stylesheet" type="text/css" href="yui/current/build/autocomplete/assets/skins/sam/autocomplete.css" />
<script type="text/javascript" src="yui/current/build/yahoo-dom-event/yahoo-dom-event.js"></script>

<script type="text/javascript" src="yui/current/build/datasource/datasource-min.js"></script>
<script type="text/javascript" src="yui/current/build/autocomplete/autocomplete-min.js"></script>
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

<box id="globaljs_overlay"/>
<hbox flex="1" id="main-box">
  <vbox flex="4">
<label for="myInput">Enter a state:</label>
<div id="myAutoComplete">
	<textbox id="myInput" type="text"/>
	<div id="myContainer"></div>
</div>
</vbox>
</hbox>
</window>