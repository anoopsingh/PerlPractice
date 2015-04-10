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
<?xul-overlay href="/overlay/buildMgmt.xul"?>

<window id="buildmgmt" orient="horizontal"
        xmlns:html="http://www.w3.org/1999/xhtml"
        xmlns="http://www.mozilla.org/keymaster/gatekeeper/there.is.only.xul"
        onload="UserInterface.init();document.getElementById('objTree_builds_refreshBtn').click();">
        
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
    <menupopup id="objTree_builds_popup" >
        <menuitem class="menuitem-iconic" image="/imgs/icons/set1/edit.gif" label="Edit Build" oncommand="editBuilds();" />
        <menuitem class="menuitem-iconic" observes="adminprivileges" image="/imgs/icons/set1/delete.gif" label="Delete Build" oncommand=";if(confirm('Are you sure you want to delete the selected testbed topology?\r\n')){UserInterface.requestAction(null,'/content/so.php',{'obj':'Builds','method':'delete_obj','uuid':UserInterface.retrieveLBSelectedListAttrib('id','objTree_builds') }, 'objTree_builds_refreshBtn', null);}"/>        
    </menupopup>
</popupset>

<groupbox flex="1">
    <caption label="Build Mgmt" style="font-size:9pt;font-weight:bold;"/>
    <vbox flex="6" >
        <tabbox id="userInputTabs" flex="1">
        <tabs>
            <tab id="add_build" label="Add Build" flex="1" />
        </tabs>
        <tabpanels flex="1" >
            <tabpanel flex="1" style="overflow:auto">
                <vbox flex="1" >
                    <grid>
                        <rows flex="1">
                            <row>
                                <label control="build_dut_menulist">Element Type:</label>
                                <menulist id="build_dut_menulist" flex="0" oncommand="if (UserInterface.mlv(this.id) != 'SBC') { document.getElementById('build_operation').disabled = true;}else{document.getElementById('build_operation').disabled = false;};">
                                    <menupopup>
                                        <menuitem label="--SELECT TYPE --" value=""/>
                                    </menupopup>
                                </menulist>
                                <toolbarbutton id="menuListBtn1" collapsed="true" class="refreshBtn" oncommand="                            UserInterface.loadMenuItems('build_dut_menulist',
                                                  {'obj':'dbTableObj',
                                                  'method':'loadListItems',
                                                  'listName':'INSTALL',
                                                  'selectStr':'Select DUT',
                                                  'listType':'dropdown'});"/>
                            </row>
                            <row>
                            <label></label>
                                <groupbox>
                                    <caption class="header" label="Control Flags:" style="margin-top:5px;margin-bottom:5px;"/>
                                    <vbox>
                                        <radiogroup id="build_operation" orient="horizontal" oncommand="if(this.value=='IMPORT'){document.getElementById('build_row').style.display='none';document.getElementById('branch_row').style.display='';}else{document.getElementById('build_row').style.display='';document.getElementById('branch_row').style.display='none';};">
                                            <radio label="Add" value='ADD' selected="true" />
                                            <radio label="Import" value='IMPORT' />
                                        </radiogroup>
                                    </vbox>
                                </groupbox>
                            </row>
                            <row id="build_row">
                                <label control="build">Build:</label>			
                                <textbox id="build" value="" width="100" placeholder="V0.0.0.0" />
                            </row>
                            <row id="branch_row" style="display:none;">
                                <label control="branch">Branch:</label>			
                                <textbox id="branch" value="" width="100" placeholder="rel0.0" />
                            </row>
                            <row hidden="true">
                                <label control="buildId">Build ID:</label>
                                <textbox id="buildId" value="" width="100"/>             
                            </row>
                            <row>
                            <spacer/>
                                <hbox flex="0" pack="left">
                                    <button id="clear" flex="0" label=" CLEAR " image="/imgs/icons/set1/clear.gif" oncommand="clearBuildInputs(); "/>
                                    <button id="submit" flex="0" label=" ADD " image="/imgs/icons/setpool/saveall_edit.gif" oncommand="addBuilds();"/>
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
            <tab id="list_builds" label="Builds" flex="1" />
        </tabs>
        <tabpanels flex="1">
            <tabpanel flex="1">
                <vbox flex="1" >
                    <hbox>
                        <label value="Builds" class="header" style="margin:2px 5px 2px 5px;"/>
                    </hbox>
                    <toolbox>
                        <toolbar id="objTree_builds_toolbar">
                            <toolbarbutton id="objTree_builds_refreshBtn" class="refreshBtn2" label="REFRESH" image="/imgs/icons/set1/refresh.gif" oncommand="refreshBuildsList();"/>
                        </toolbar>
                    </toolbox>
                    <tree id="objTree_builds" flex="1" hidecolumnpicker="false" seltype="multiple" class="tree" sortDirection="descending" enableColumnDrag="true"  sortResource="label1" query="q70" context="objTree_builds_popup">
                        <treecols id="objTree_builds">
                            <treecol id="objTree_builds_label1" primary="true" flex="1" label="Type" 
                                    persist="width ordinal hidden" ordinal="1" class="sortDirectionIndicator" onclick="refreshBuildsList(this.id.replace('objTree_builds_',''))"/>
                            <splitter class="tree-splitter" ordinal="2"/>
                            <treecol id="objTree_builds_label2" flex="1" label="Build"
                                    persist="width ordinal hidden" ordinal="3" sortable="true" class="sortDirectionIndicator" onclick="refreshBuildsList(this.id.replace('objTree_builds_',''))"/>
                        </treecols>
                    </tree>
                </vbox>
            </tabpanel>
        </tabpanels>
    </tabbox>
</vbox>
</groupbox>
</window>
