<?php
  header( "Pragma: private");
  header( "Expires: Mon, 20 Dec 1998 01:00:00 GMT" );
  ##header( "Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT" );
  header( "Cache-Control: s-maxage=0, no-store, no-cache, must-revalidate, post-check=0, pre-check=0" );
  header( "Pragma: no-cache" );
  include_once("session.php");
  if(isset($_SESSION['USERINFO'])){
    $tmp = $_SESSION['USERINFO'];
    $userInfo = unserialize(base64_decode($tmp));
    if($userInfo['userId'] == ""){
      header("Cache-control: private");
      session_destroy();
      header("Location: /");      
    }
  }else{
    header("Cache-control: private");
    session_destroy();
    header("Location: /");
  }

//  $userInfo['administrator'] = "N";  
  header ("Content-type: application/vnd.mozilla.xul+xml; charset=utf-8");
  echo '<' . '?xml version="1.0" encoding="iso-8859-15" ?' . '>';
  echo '<' . '?xml-stylesheet href="chrome://global/skin/" type="text/css"?' . '>' . "";
  echo '<' . '?xml-stylesheet href="/inc/sitedb.css" type="text/css"?' . '>' . "";
?>
<?xml-stylesheet href="spinnerUI.css" type="text/css"?>
<?xul-overlay href="/overlay/globaljs_overlay.xul"?>

<window id="objectadmin" orient="horizontal"
        xmlns:html="http://www.w3.org/1999/xhtml"
        xmlns="http://www.mozilla.org/keymaster/gatekeeper/there.is.only.xul"
        onload="UserInterface.init();">
        
<!-- always include this box -->
<box id="globaljs_overlay"/>
<!-- always include this box -->

<!--  Broadcasters here -->
<broadcasterset>
  <broadcaster id="currentUser" value="<?php echo $userInfo['userName']; ?>"/>  
  <?php
  if( isset($userInfo['roleName']) && ($userInfo['roleName'] === 'admin' || $userInfo['roleName'] === 'manager')) {
  ?>
	<broadcaster id="adminprivileges" hidden="false" />
	<broadcaster id="admincollapsed" collapsed="false" />
	<broadcaster id="admindisabled" disabled="false" />
  <?php
  }else{
  ?>  
        <broadcaster id="adminprivileges" hidden="true"/>
        <broadcaster id="admincollapsed" collapsed="true" />
        <broadcaster id="admindisabled" disabled="true" />    
  <?php  
  }
  ?>
</broadcasterset>

<vbox flex="1" style="overflow:auto">
  <hbox>
  <label value="Run Load" class="header" style="margin:2px 5px 2px 5px;"/>
  </hbox>
    <vbox flex="1">
        <hbox flex="0">
        <groupbox id="groupbox1" flex="1">
          <grid>
            <columns>
              <column flex="1"/>
              <column flex="2"/>
            </columns>
            <rows>
              <row>
                <label control="jobname">Job Name</label>
                <textbox id="jobname" value=""/>
              </row>
              <row>
                <label value="DUT"/>
                <menulist id="dut" flex="1">
                    <menupopup>
                      <menuitem label="--SELECT DUT --" value=""/>
                      <menuitem label="SBC"/>
                    </menupopup>
                </menulist>
              </row>

              <row>
                <label value="RELEASE"/>
                <menulist id="release" flex="1">
                    <menupopup>
                      <menuitem label="--SELECT RELEASE --" value=""/>
                      <menuitem label="V8.1.1"/>
                      <menuitem label="V8.2.1"/>
                      <menuitem label="V8.3.1"/>
                      <menuitem label="V8.3.2"/>
                    </menupopup>
                </menulist>
              </row>
              <row>
                <label value="Platform"/>
                <menulist id="platform" flex="1">
                    <menupopup>
                      <menuitem label="--SELECT PLATFORM --" value=""/>
                      <menuitem label="Annapolis"/>
                      <menuitem label="Jarell"/>
                      <menuitem label="Sandybridge-1U"/>
                      <menuitem label="Sandybridge-2U"/>
                      <menuitem label="SandyBridge -DSP"/>
                    </menupopup>
                </menulist>
              </row>
              <row>
                <label value="Test Case"/>
                <menulist id="testbed_templates" flex="1"> 
                    <menupopup>
                      <menuitem label="--SELECT TEST CASE --" value=""/>
                    </menupopup>
                </menulist>
              </row>
              <row>
                <label control="build">Build</label>
                <textbox id="build" value=""/>
              </row>

              <row>
                <label control="supportingdevices">Other Testbed Elements</label>
                <textbox id="supportingdevices" value=""/>
              </row>
              <row>
                <label control="duration">Test Duration (seconds)</label>
                <textbox id="duration" value=""/>
              </row>
              <row>
                <spacer/>
                <hbox flex="0" pack="left">
                <button label=" Clear"/>
                <button label="Run"/>
                </hbox>
              </row>
            </rows>
          </grid>
        </groupbox>
        </hbox>
    </vbox>
</vbox>
<splitter/>
<vbox flex="6" style="margin: 0px 0px 0px 0px;">
	<hbox>
  <label value="Job Status" class="header" style="margin:2px 5px 2px 5px;"/>
  </hbox>
    <vbox flex="1">
        <tabbox id="tabs1" flex="1">
  	<tabs>
  	  <tab id="auto_log" label="Automation Log"/>
  	  <tab id="session_log" label="SBC Session Log"/>
  	</tabs>
            <tabpanels flex="1">
                <tabpanel flex="1">
                    <vbox flex="1">
		    <splitter/>
                    <vbox flex="1">
                    </vbox>
                    </vbox>
                </tabpanel>
                
            </tabpanels>
        </tabbox>
    </vbox>
</vbox>
</window>

