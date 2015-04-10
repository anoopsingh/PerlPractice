<?php 
    header('Pragma: private');
    header( "Expires: Mon, 20 Dec 1998 01:00:00 GMT" );
    header( "Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT" );
    header( "Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0" );
    header( "Pragma: no-cache" );
    if (!defined('APP_DIR')) { define('APP_DIR', dirname(__FILE__));   }
    require_once("session.php");
    $userInfo = array();
    if(array_key_exists('USERINFO',$_SESSION)){ #$_SESSION['USERINFO']){
        $tmp = $_SESSION['USERINFO'];
        $userInfo = unserialize(base64_decode($tmp));
    }else{
        header("Cache-control: private");
        session_destroy();
        header("Location: http://" . $_SERVER{'SERVER_NAME'} . "/login.php");
    }
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
        onload="UserInterface.init();"
        type="gensmart::main"
        persist="screenX screenY width height sizemode">
        
<script src="/js/do.js" type="application/x-javascript"/>
<script src="/js/global.js" type="application/x-javascript"/> 

<!-- always include this box -->
<box id="globaljs_overlay"/>
<!-- always include this box -->
<broadcasterset>
  <?php
  if( isset($userInfo['roleName']) && ($userInfo['roleName'] === 'admin')) {
  ?>
    <broadcaster id="adminprivileges" hidden="false"/>
    <broadcaster id="genSmartAdmin" hidden="false"/>
  <?php
  }elseif (isset($userInfo['roleName']) && ($userInfo['roleName'] === 'manager')){
  ?>
    <broadcaster id="adminprivileges" hidden="false"/>
    <broadcaster id="genSmartAdmin" hidden="true"/>
  <?php
  }else {
  ?>  
    <broadcaster id="genSmartAdmin" hidden="true"/>
    <broadcaster id="adminprivileges" hidden="true"/>
  <?php  
  }
  ?>
  <broadcaster id="objList_sessions_count" value="0"/> 
</broadcasterset>
<!-- UI Start -->
<toolbox>
    <menubar id="main-menubar"/><!-- Overlay MenuBar -->
</toolbox>
<!-- contentPane -->
<hbox flex="1" id="main-box">
  <vbox flex="4">
    <vbox flex="1">
      <vbox flex="13">
        <iframe id="contentPane" name="contentPane" flex="1" src="/content/testbedelementmgmt.php" />
      </vbox>
      <splitter  style="width: 1px; border:0px; min-width: 1px;"/>
      <vbox id="msgWindow" flex="1" collapsed="true">
        <hbox>
          <label  class="header" value="Session Messages" style="margin-top:5px;margin-bottom:5px;"/>
        </hbox>
        <textbox id="msg-all" multiline="true" rows="3" value="" flex="1" readonly="true"/>
      </vbox>
    </vbox>
  </vbox>
  <splitter/>
  <vbox id="helpWindow" flex="1" collapsed="true">
    <vbox flex="1">
      <hbox>
        <label class="header" value="genSmart HELP" style="margin-top:8px;margin-bottom:5px;"/>
        <spacer flex="1"/>
        <toolbarbutton id="close1" image="/imgs/icons/set1/delete2.gif" tooltiptext="Close the Help Window" oncommand="document.getElementById('wHelp').click();"/>	
      </hbox>
      <groupbox flex="1">
        <iframe id="helpPane" name="helpPane" flex="1" src="/help/help.php" />
      </groupbox>
    </vbox>
  </vbox>
  <splitter/>
  <vbox id="sessionWindow" flex="1" collapsed="true" style="max-width:300px">
      <vbox flex="1">
          <hbox>
              <label class="header" value="genSmart SESSIONS" style="margin-top:5px;margin-bottom:5px;"/>
              <spacer flex="1"/>
              <toolbarbutton id="close2" image="/imgs/icons/set1/delete2.gif" tooltiptext="Close genSmart Sessions Window" oncommand="document.getElementById('wSessions').click();"/>	
          </hbox>
          <toolbox>
              <toolbar id="nav-toolbar">
                <toolbarbutton id="refreshSessions" class="refreshBtn" label="REFRESH" image="/imgs/icons/set1/refresh.gif" oncommand="UserInterface.refresh('objList_sessions', null);"  tooltiptext="Click to refresh"/>
                <toolbarseparator />
                <spacer flex="1" />
                <box align="center" flex="0">
                  <label control="counter2" value="Object Cnt: "/>
                  <label id="counter2" observes="objList_sessions_count"/>
                </box>
              </toolbar>
          </toolbox>
          <listbox id="objList_sessions" class="listobject" query="q49" flex="1">
          <listhead>
            <listheader label="Author" flex="1"/>
            <listheader label="Last Access" flex="1"/>
          </listhead>
          <listcols>
            <listcol id="objList_sessions_label1" flex="1"/>
            <splitter style="width: 1px; border:0px; min-width: 0%; background-color: transparent;"/>
            <listcol id="objList_sessions_label2" flex="1"/>
          </listcols>
          </listbox>
      </vbox>               
  </vbox>  
</hbox>
   <statusbar id="status-bar">
    <statusbarpanel flex="0" id="my-panel" label="User: <?php echo (isset($userInfo['distinguishedname']) ? $userInfo['distinguishedname'] : 'Test'); ?>" />
    <statusbarpanel flex="1" id="msg-panel" label ="" style="font-weight:bolder;"/>
    <statusbarpanel id="messageCollapsePanel" class="statusbarpanel-iconic-text" src="/imgs/icons/set1/collapse.gif"  tooltiptext="Expand or collapse the historical messages window" oncommand="(document.getElementById('msgWindow').collapsed == false) ?  document.getElementById('msgWindow').setAttribute('collapsed',true) :  document.getElementById('msgWindow').setAttribute('collapsed',false);" />
    <progressmeter id="progressMeter" mode="determined"/>
    <statusbarpanel class="statusbarpanel-iconic-text" src="/imgs/sm_logo3.png"  label="VER: 1.1" flex="0" id="my-panel" />
   </statusbar>
</window>
