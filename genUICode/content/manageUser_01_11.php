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
<?xul-overlay href="/overlay/LoadScheduler.xul"?>
<window id="objectadmin" orient="horizontal"
        xmlns:html="http://www.w3.org/1999/xhtml"
        xmlns="http://www.mozilla.org/keymaster/gatekeeper/there.is.only.xul"
        onload="UserInterface.init();document.getElementById('create_user').click();">
        
        
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
  <broadcaster id="objTree_jobqueue_count" value="0"/>
  <broadcaster id="objTree_jobqueue_results_count" value="0"/>
  <broadcaster id="objTree_testbedtopology_count" value="0"/>
  
</broadcasterset>


<groupbox flex="1">
<caption label="Create User" style="font-size:9pt;font-weight:bold;"/>
<vbox flex="1" equalsize="always" style="overflow-x:auto; overflow-y:auto">
    <tabbox id="userInputTabs" flex="1">
    <tabs>
        <tab id="create_user" label="Create User" flex="1" onclick="UserInterface.loadMenuItems('user_role',
                                {'obj':'ManageUser',
                                'method':'loadTemplates_xml',
                                'query':'q61',
                                'selectStr':'Select Role',
                                });" />
    </tabs>
    <tabpanels flex="1">
        <tabpanel flex="1">
          <vbox flex="1" >
              <grid>
                <rows flex="1">
                  <row>
                    <label control="firstname">First Name </label>
                    <textbox id="first_name" value="" placeholder="First Name" />
                  </row>
                  <row>
                    <label control="lastname">Last Name</label>
                    <textbox id="last_name" value="" placeholder="Last Name"/>
                  </row>
                  <row>
                    <label control="email">E-mail </label>
                    <textbox id="email_id" value="" placeholder="test@test.com" />
                  </row>
                  <row>
                    <label control="username">User Name </label>
                    <textbox id="user_name" value="" placeholder="username" />
                  </row>
                  <row>
                    <label control="password">Password </label>
                    <textbox id="password" value="" placeholder="password" type="password" />
                  </row>
					<row>
                    <label control="user_role">User Role</label>
                    <menulist id="user_role" flex="1">
                        <menupopup>
                            <menuitem label="--SELECT ROLE --" value=""/>
                        </menupopup>
                    </menulist>
                  </row>
                  <row>
                    <spacer/>
                    <hbox flex="0" pack="left">
                    <button id="clear" flex="0" label=" CLEAR " image="/imgs/icons/set1/clear.gif" oncommand="clearJobInputs();"/>
                    <button id="submit" flex="0" label=" CREATE " image="/imgs/icons/setpool/saveall_edit.gif" oncommand="addToJobQueue();"/>
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
<splitter resizeafter="flex" resizebefore="closest" />
<vbox flex="1" equalsize="always" style="overflow-x:auto; margin: 0px 5px 0px 5px;">
    <vbox flex="1">
        <tabbox id="atdtabs" flex="1">
        </tabbox>
    </vbox>
</vbox>
</window>
