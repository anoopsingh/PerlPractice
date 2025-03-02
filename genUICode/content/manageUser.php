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

<window id="manageUser" orient="horizontal"
        xmlns:html="http://www.w3.org/1999/xhtml"
        xmlns="http://www.mozilla.org/keymaster/gatekeeper/there.is.only.xul"
        onload="UserInterface.init();document.getElementById('objTree_user_refreshBtn').click();document.getElementById('create_user').click();">
        
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

<popupset>
    <menupopup id="objTree_user_popup">
        <menuitem class="menuitem-iconic" image="/imgs/icons/set1/edit.gif" label="Update User" oncommand="editUser();" />
        <menuitem class="menuitem-iconic" observes="adminprivileges" image="/imgs/icons/set1/delete.gif" label="Delete User" tooltiptext="User can be deleted only by Admin/Manager" oncommand=";if(confirm('Are you sure you want to delete the selected user?\r\n')){UserInterface.requestAction(null,'/content/so.php',{'obj':'Users','method':'delete_obj','userName':document.popupNode.childNodes[document.getElementById('objTree_user').currentIndex].getAttribute('userName'), }, 'objTree_user_refreshBtn', null);}"/>
    </menupopup>
</popupset>

<groupbox flex="0">
    <caption label="Create User" style="font-size:9pt;font-weight:bold;"/>
    <vbox flex="1" >
        <tabbox id="userInputTabs" flex="1">
        <tabs>
              <tab id="create_user" label="Create User" flex="1" onclick="UserInterface.loadMenuItems('user_role',
                                {'obj':'Users',
                                'method':'loadTemplates_xml',
                                'query':'q61',
                                'selectStr':'Select Role',
                                });" />

        </tabs>
        <tabpanels flex="1" >
            <tabpanel flex="1">
                <vbox flex="1" >
                    <grid>
                              <rows flex="1">
                                <row>
                                        <label control="first_name">First Name </label>
                                    <textbox id="first_name" value="" placeholder="First Name" />
                                  </row>
                                  <row>
                                <label control="last_name">Last Name</label>
                                    <textbox id="last_name" value="" placeholder="Last Name"/>
                                  </row>
                                  <row>
                                         <label control="email_id">E-mail </label>
                                     <textbox id="email_id" value="" placeholder="test@test.com" />
                                  </row>
                                 <row>
                                     <label control="user_name">User Name </label>
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
                        <row hidden="true">
                                <label control="userId">User ID:</label>
                                <textbox id="userId" value="" width="100"/>
                        </row>

                            <row>
                                <spacer/>
                                <hbox flex="0" pack="left">
                                    <button id="clear2" flex="0" label=" CLEAR " image="/imgs/icons/set1/clear.gif" oncommand="clearUserInputs();"/>
                                    <button id="submit" flex="0" label=" CREATE/UPDATE " image="/imgs/icons/setpool/saveall_edit.gif" oncommand="createUser();"/>
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
<groupbox flex="1">
<vbox flex="6">
    <tabbox id="resultlist" flex="1">
        <tabs>
            <tab id="list_user" label="User Information" flex="1" />
        </tabs>
        <tabpanels flex="1">
            <tabpanel flex="1">
                <vbox flex="1" >
                    <hbox>
                        <label value="User List" class="header" style="margin:2px 5px 2px 5px;"/>
                    </hbox>
                    <toolbox>
                        <toolbar id="objTree_user_toolbar">
                           <toolbarbutton id="objTree_user_refreshBtn" class="refreshBtn2" label="REFRESH" image="/imgs/icons/set1/refresh.gif" oncommand="refreshTopologyList();"/>
                        </toolbar>
                    </toolbox>
                    <tree id="objTree_user" flex="1" hidecolumnpicker="false" seltype="multiple" class="tree" sortDirection="descending" enableColumnDrag="true"  sortResource="label1" query="q62" context="objTree_user_popup" >
                        <treecols id="objTree_user">
                            <treecol id="objTree_user_label1" primary="true" flex="1" label="User Name"
                                    persist="width ordinal hidden" ordinal="1" class="sortDirectionIndicator" onclick="refreshTopologyList(this.id.replace('objTree_user_',''))"/>
                            <splitter class="tree-splitter" ordinal="2"/>
                            <treecol id="objTree_user_label2" flex="0" label="First Name"
                                    persist="width ordinal hidden" ordinal="3" sortable="true" class="sortDirectionIndicator" onclick="refreshTopologyList(this.id.replace('objTree_user_',''))"/>
                            <splitter class="tree-splitter" ordinal="4"/>
                            <treecol id="objTree_user_label3" flex="1" label="Last Name"
                                    persist="width ordinal hidden" ordinal="5" sortable="true" class="sortDirectionIndicator" onclick="refreshTopologyList(this.id.replace('objTree_user_',''))"/>
                            <splitter class="tree-splitter" ordinal="6"/>
                            <treecol id="objTree_user_label4" flex="1" label="Email Id"
                                    persist="width ordinal hidden" ordinal="7" sortable="true" class="sortDirectionIndicator" onclick="refreshTopologyList(this.id.replace('objTree_user_',''))"/>
                            <splitter class="tree-splitter" ordinal="7"/>
                            <treecol id="objTree_user_label5" flex="1" label="Status"
                                    persist="width ordinal hidden" ordinal="8" sortable="true" class="sortDirectionIndicator" onclick="refreshTopologyList(this.id.replace('objTree_user_',''))"/>
                            <splitter class="tree-splitter" ordinal="9"/>
                            <treecol id="objTree_user_label6" flex="1" label="Role"
                                    persist="width ordinal hidden" ordinal="10" sortable="true" class="sortDirectionIndicator" onclick="refreshTopologyList(this.id.replace('objTree_user_',''))"/>
                        </treecols>
                    </tree>
                </vbox>
            </tabpanel>
        </tabpanels>

    </tabbox>
</vbox>
</groupbox>
</window> 
