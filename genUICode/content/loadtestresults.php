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
  <label value="Job Results" class="header" style="margin:2px 5px 2px 5px;"/>
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
            </rows>
          </grid>
        </groupbox>
        </hbox>
    </vbox>
</vbox>
<splitter/>
<vbox flex="6" style="margin: 0px 0px 0px 0px;">
    <vbox flex="1">
        <tabbox id="tabs1" flex="1">
            <tabs>
                <tab id="graph_tab" label="GRAPH" flex="1"/>
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

