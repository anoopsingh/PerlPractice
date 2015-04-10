<?php
  header( "Pragma: private");
  header( "Expires: Mon, 20 Dec 1998 01:00:00 GMT" );
  #header( "Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT" );
  header( "Cache-Control: s-maxage=0, no-store, no-cache, must-revalidate, post-check=0, pre-check=0" );
  header( "Pragma: no-cache" );
  include_once("session.php");
  if(isset($_SESSION['USERINFO'])){
    $tmp = $_SESSION['USERINFO'];
    $userInfo = unserialize(base64_decode($tmp));
  }else{
    header("Cache-control: private");
    session_destroy();
    header("Location: /");
  }
  header ("Content-type: application/vnd.mozilla.xul+xml; charset=utf-8");
  echo '<' . '?xml version="1.0" encoding="iso-8859-15" ?' . '>';
  echo '<' . '?xml-stylesheet href="chrome://global/skin/" type="text/css"?' . '>' . "";
  echo '<' . '?xml-stylesheet href="/inc/sitedb.css" type="text/css"?' . '>' . "";
?>

<?xml-stylesheet href="spinnerUI.css" type="text/css"?>
<?xul-overlay href="/overlay/globaljs_overlay.xul"?>
<?xul-overlay href="/overlay/ManageUser.xul"?>
<window id="testbedtopology" orient="horizontal"
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

<groupbox flex="0">
    <caption label="Change Password" style="font-size:9pt;font-weight:bold;"/>
    <vbox flex="1" >
        <tabbox id="userInputTabs1" flex="1">
        <tabs>
            <tab id="changepass" label="Change Password" flex="1" />
        </tabs>
        <tabpanels flex="1" >
            <tabpanel flex="1">
                <vbox flex="1" >
                    <grid>
                        <rows flex="1">
				<row>
                	            <label control="new_pass">New Password </label>
		                    <textbox id="new_pass" value="" placeholder="New Password" />
        		          </row>
                		  <row>
                    	            <label control="cnf_new_pass">Confirm New Password</label>
		                    <textbox id="cnf_new_pass" value="" placeholder="Confirm New Password"/>
        		          </row>
                            <row>
                                <spacer/>
                                <hbox flex="0" pack="left">
                                    <button id="clear3" flex="0" label=" CLEAR " image="/imgs/icons/set1/clear.gif" oncommand="clearPassInputs();"/>
                                    <button id="submit" flex="0" label=" Change Password " image="/imgs/icons/setpool/saveall_edit.gif" oncommand="changePassword();"/>
                                </hbox>
                            </row>
                        </rows>
                    </grid>
                </vbox>
            </tabpanel>
        </tabpanels>
        </tabbox>
    </vbox>
</groupbox>
</window> 
