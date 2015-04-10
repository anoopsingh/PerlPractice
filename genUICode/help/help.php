<?php
  header( "Pragma: private");
  header( "Expires: Mon, 20 Dec 1998 01:00:00 GMT" );
  #header( "Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT" );
  header( "Cache-Control: s-maxage=0, no-store, no-cache, must-revalidate, post-check=0, pre-check=0" );
  header( "Pragma: no-cache" );
  header ("Content-type: application/vnd.mozilla.xul+xml; charset=utf-8");
  echo '<' . '?xml version="1.0" encoding="iso-8859-15" ?' . '>';
  echo '<' . '?xml-stylesheet href="chrome://global/skin/" type="text/css"?' . '>' . "";
  echo '<' . '?xml-stylesheet href="/inc/sitedb.css" type="text/css"?' . '>' . "";
?>

<?xul-overlay href="/overlay/globaljs_overlay.xul"?>
<window id="help" orient="horizontal"
        xmlns:html="http://www.w3.org/1999/xhtml"
        xmlns="http://www.mozilla.org/keymaster/gatekeeper/there.is.only.xul"
	onload="UserInterface.init();">

<!-- always include this box -->
<box id="globaljs_overlay"/>
<!-- always include this box -->

<vbox flex="6" style="margin: 0px 5px 0px 5px;">
    <vbox flex="1">
        <tabbox id="atdtabs" flex="1">
            <tabs>
                <tab id="columnHelp" label="Column Help" flex="1"/>
            </tabs>
            <tabpanels flex="1">
                <tabpanel flex="1">
                    <vbox height="100%" flex="1" >
                        <!--<iframe id="mainframe2" class="iframe" name="mainframe2" flex="1" src="/help/columnHelp.php"/> -->
			<span class="helptitle">Video Tutorials</span><br/>
			<label value="Load Test Scheduler" onclick="window.open('http://172.23.54.205/tutorials/GenSMART_Load_Regression_Scheduler_Demo/GenSMART_Load_Regression_Scheduler_Demo.html')" class="text-link" />
                    </vbox>
                </tabpanel>
            </tabpanels>
        </tabbox>
    </vbox>
</vbox>
</window> 
