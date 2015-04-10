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
<?xul-overlay href="/overlay/testbedTopology.xul"?>

<window id="testbedtopology" orient="horizontal"
        xmlns:html="http://www.w3.org/1999/xhtml"
        xmlns="http://www.mozilla.org/keymaster/gatekeeper/there.is.only.xul"
        onload="UserInterface.init();UserInterface.clearSessionStorage();document.getElementById('objTree_topology_refreshBtn').click();">
        
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
    <menupopup id="objTree_topology_popup" onpopupshowing="if(document.popupNode.childNodes[document.getElementById('objTree_topology').currentIndex].firstChild.childNodes[10].getAttribute('label') == 'Running')document.getElementById('stopScheduler').style.display=''; else document.getElementById('stopScheduler').style.display='none';" >
        <menuitem class="menuitem-iconic" image="/imgs/icons/set1/edit.gif" label="Edit Topology" oncommand="editTopology();" />
        <menuitem class="menuitem-iconic" image="/imgs/icons/set1/clone.gif" label="Clone Topology" oncommand="editTopology('clone');" />
        <menuitem class="menuitem-iconic" observes="adminprivileges" image="/imgs/icons/set1/delete.gif" label="Delete Topology" tooltiptext="Topology can be deleted only by Admin/Manager" oncommand=";if(confirm('Are you sure you want to delete the selected testbed topology?\r\n')){UserInterface.requestAction(null,'/content/so.php',{'obj':'Testbedtopology','method':'delete_obj','uuid':document.popupNode.childNodes[document.getElementById('objTree_topology').currentIndex].getAttribute('testbedtopology_uuid') }, 'objTree_topology_refreshBtn', null);}"/>
        <menu id="status-menu" label="Set Status"  observes="adminprivileges" >
            <menupopup id="status-popup">
                <menuitem class="menuitem-iconic" observes="adminprivileges" image="/imgs/icons/set1/unlock.gif" label="Free" oncommand="UserInterface.requestAction(null,'/content/so.php',{'obj': 'Testbedtopology','method':'setStatus','uuid':UserInterface.retrieveLBSelectedListAttrib('id','objTree_topology'),'status':'F'}, 'objTree_topology_refreshBtn', null);"/>
                <menuitem class="menuitem-iconic" observes="adminprivileges" image="/imgs/icons/set1/lock.gif" label="Busy" oncommand="UserInterface.requestAction(null,'/content/so.php',{'obj': 'Testbedtopology','method':'setStatus','uuid':UserInterface.retrieveLBSelectedListAttrib('id','objTree_topology'),'status':'B'}, 'objTree_topology_refreshBtn', null);"/>
                <menuitem class="menuitem-iconic" observes="adminprivileges" image="/imgs/icons/set1/terminatepid.gif" label="Out of Service" oncommand="UserInterface.requestAction(null,'/content/so.php',{'obj': 'Testbedtopology','method':'setStatus','uuid':UserInterface.retrieveLBSelectedListAttrib('id','objTree_topology'),'status':'O'}, 'objTree_topology_refreshBtn', null);"/>
            </menupopup>
        </menu>
        <menuitem class="menuitem-iconic" observes="adminprivileges" image="/imgs/icons/set1/redo.gif" label="Start/Restart Scheduler" oncommand="UserInterface.editTreeItem('objTree_topology', document.getElementById('objTree_topology').currentIndex, {'obj':'Testbedtopology', 'method':'schedulerStatus', 'topologyName': document.popupNode.childNodes[document.getElementById('objTree_topology').currentIndex].getAttribute('testbedtopology_name'), 'action' : 'restart' }, 10);" />
        <menuitem id="stopScheduler" class="menuitem-iconic" observes="adminprivileges" image="/imgs/icons/set1/stop.gif" label="Stop Scheduler" oncommand="UserInterface.editTreeItem('objTree_topology', document.getElementById('objTree_topology').currentIndex, {'obj':'Testbedtopology', 'method':'schedulerStatus', 'topologyName': document.popupNode.childNodes[document.getElementById('objTree_topology').currentIndex].getAttribute('testbedtopology_name'), 'action' : 'stop'}, 10);" />
    </menupopup>
</popupset>

<groupbox flex="0">
    <caption label="TestBed Topology Mgmt" style="font-size:9pt;font-weight:bold;"/>
    <vbox flex="6" >
        <tabbox id="userInputTabs" flex="1">
        <tabs>
            <tab id="create_topology" label="Create TestBed Topology" flex="1" />
        </tabs>
        <tabpanels flex="1" >
            <tabpanel flex="1" style="overflow:auto">
                <vbox flex="1" >
                    <grid>
                        <rows flex="1">
                            <row>
                                <label control="topologyName">Topology Name:</label>			
                                <textbox id="topologyName" value="" width="100" placeholder="LoadSetup1" />
                            </row>
                            <row>
                                <label control="topology_dut_menulist">DUT:</label>
                                <menulist id="topology_dut_menulist" flex="1" oncommand="UserInterface.loadListBx ('availableTestbedTypeList',{'obj':'Testbedtopology',
                                                                'method':'load_objList_testbed_xml',
                                                                'query' : 'q50',
                                                                'where': 'objtype_name != \''+ UserInterface.mlv('topology_dut_menulist') + '\''});UserInterface.clearSessionStorage();emptyListBox(document.getElementById('availableTestbedAlias'));emptyListBox(document.getElementById('selectedTestbedTypeList'));emptyListBox(document.getElementById('selectedTestbedAlias'));loadDutDetails();">
                                    <menupopup>
                                        <menuitem label="--SELECT DUT --" value=""/>
                                        <menuitem label="SBC" value="SBC"/>
                                    </menupopup>
                                </menulist>
                            </row>
                            <row>
                                <label value="# of DUT's:"/>
                                <menulist id="dutnumber" flex="1" oncommand="loadDutDetails();"> 
                                    <menupopup> 
                                      <menuitem label="1" value="1"/>
                                      <menuitem label="2" value="2"/>
                                    </menupopup>
                                </menulist>
                            </row>
                            <row>
                                <label>DUT Details</label>
                                <groupbox id="dutdetails"  flex="1">
                                </groupbox>
                            </row>
                            <row>
                                <label>Other Testbed Elements:</label>
                                <groupbox flex="1">
                                    <hbox flex="1" >
                                        <listbox id="availableTestbedTypeList" class="listobject" sortDirection="ascending" sortResource="label1"  query="50"  whereStmt="" flex="1"  ondblclick="UserInterface.loadListBx ('availableTestbedAlias',
                                                                        {'obj':'Testbedtopology',
                                                                        'method':'load_objList_testbed_xml',
                                                                        'query' : 'q51',
                                                                        'topologyId' : document.getElementById('topologyId').value,
                                                                         'where': 'testbedelement_objtype_uuid = \''+ UserInterface.retrieveLBSelectedListAttrib('id','availableTestbedTypeList') + '\''
                                                                        });">
                                            <listhead>
                                                <listheader id="availableTestbedTypeList_label1" label="Testbed Type" flex="1"/>
                                            </listhead>
                                            <listcols>
                                                <listcol flex="1"/>
                                            </listcols>
                                        </listbox>
                                        <listbox id="availableTestbedAlias" class="listobject" seltype="multiple" sortDirection="ascending" sortResource="label1"  query="q51"  whereStmt="" flex="1" >
                                            <listhead>
                                                <listheader id="availableTestbedAlias_label1" label="Testbed Alias" flex="1"/>
                                            </listhead>
                                            <listcols>
                                                <listcol flex="1"/>
                                            </listcols>
                                        </listbox>
                                    </hbox>
                                    <toolbox align="center">
                                        <toolbar id="nav-toolbar">
                                            <toolbarbutton id="addBtn" label="ADD" image="/imgs/icons/set1/add.gif" oncommand="addAvailableTestbed();" tooltiptext="Click to add selected Testbed"/>
                                            <toolbarseparator />
                                            <toolbarbutton id="deleteBtn" label="REMOVE" image="/imgs/icons/set1/delete.gif"                                oncommand="removeAddedTestbed();" tooltiptext="Click to remove the selected Testbed"/>
                                        </toolbar>
                                    </toolbox>
                                    <hbox flex="1" >
                                        <listbox id="selectedTestbedTypeList" class="listobject" flex="1" ondblclick="loadSelectedTestbeds();">
                                            <listhead>
                                                <listheader id="selectedTestbedTypeList_label1" label="Selected Testbed Type" flex="1" />
                                            </listhead>
                                            <listcols>
                                                <listcol flex="1"/>
                                            </listcols>
                                        </listbox>
                                        <listbox id="selectedTestbedAlias" class="listobject" seltype="multiple" flex="1" >
                                            <listhead>
                                                <listheader id="selectedTestbedAlias_label1" label="Selected Testbed Alias" flex="1" />
                                            </listhead>
                                            <listcols>
                                                <listcol flex="1"/>
                                            </listcols>
                                        </listbox>
                                    </hbox>
                                </groupbox>
                            </row>
                            <row>
                                <label></label>
                                <groupbox>
                                <caption class="header" label="TestBed Topology Type:" style="margin-top:5px;margin-bottom:5px;"/>
                                <vbox>
                                    <row>
                                        <radiogroup id="radiogroup_type" orient="horizontal">
                                            <radio label="Regression" value="FR" selected="true" />
                                            <radio label="Load" value="LT" />
                                            <radio label="RSM" value="RSM" />
                                        </radiogroup>
                                    </row>
                                </vbox>
                                </groupbox>
                            </row>
                            <row>
                                <label></label>
                                <groupbox>
                                <caption class="header" label="TestBed Location:" style="margin-top:5px;margin-bottom:5px;"/>
                                <vbox>
                                    <row>
                                        <radiogroup id="testbedtopology_location" orient="horizontal">
                                            <radio label="Billerica" value="BL" selected="true"/>
                                            <radio label="Noida" value="IND" />
                                            <radio label="Ottawa" value="OTW" />
                                        </radiogroup>
                                    </row>
                                </vbox>
                                </groupbox>
                            </row>
                            <row>
                                <label></label>
                                <groupbox>
                                <caption class="header" label="TestBed Usage Type:" style="margin-top:5px;margin-bottom:5px;"/>
                                <vbox>
                                    <row>
                                        <radiogroup id="testbedtopology_usage"  orient="horizontal">
                                            <radio label="Public" value="0" selected="true"/>
                                            <radio label="Private" value="1" />
                                        </radiogroup>
                                    </row>
                                </vbox>
                                </groupbox>
                            </row>
                            <row>
                                <label></label>
                                <groupbox>
                                <caption class="header" label="Topology Category:" style="margin-top:5px;margin-bottom:5px;"/>
                                <vbox>
                                    <row>
                                        <radiogroup id="testbedtopology_category"  orient="horizontal">
                                            <radio label="Real" value="0" selected="true"/>
                                            <radio label="Virtual" value="1" />
                                        </radiogroup>
                                    </row>
                                </vbox>
                                </groupbox>
                            </row>
                            <row hidden="true">
                                <label control="topologyId">Topology ID:</label>
                                <textbox id="topologyId" value="" width="100"/>             
                            </row> 
                            <row>
                                <spacer/>
                                <hbox flex="0" pack="left">
                                    <button id="clear2" flex="0" label=" CLEAR " image="/imgs/icons/set1/clear.gif" oncommand="clearTopologyInputs();"/>
                                    <button id="submit" flex="0" label=" CREATE/UPDATE " image="/imgs/icons/setpool/saveall_edit.gif" oncommand="addTopology();"/>
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
            <tab id="list_topology" label="TestBed Topology's" flex="1" />
        </tabs>
        <tabpanels flex="1">
            <tabpanel flex="1">
                <vbox flex="1" >
                    <hbox>
                        <label value="TestBed Topology List" class="header" style="margin:2px 5px 2px 5px;"/>
                    </hbox>
                    <toolbox>
                        <toolbar id="objTree_topology_toolbar">
                            <toolbarbutton id="objTree_topology_refreshBtn" class="refreshBtn2" label="REFRESH" image="/imgs/icons/set1/refresh.gif" oncommand="refreshTopologyList();"/>
                        </toolbar>
                    </toolbox>
                    <tree id="objTree_topology" flex="1" hidecolumnpicker="false" seltype="multiple" class="tree" sortDirection="descending" enableColumnDrag="true"  sortResource="label1" query="q52" context="objTree_topology_popup" >
                        <treecols id="objTree_topology">
                            <treecol id="objTree_topology_label1" primary="true" flex="1" label="Topology Name" 
                                    persist="width ordinal hidden" ordinal="1" class="sortDirectionIndicator" onclick="refreshTopologyList(this.id.replace('objTree_topology_',''))"/>
                            <splitter class="tree-splitter" ordinal="2"/>
                            <treecol id="objTree_topology_label2" flex="0" label="DUT Type"
                                    persist="width ordinal hidden" ordinal="3" sortable="true" class="sortDirectionIndicator" onclick="refreshTopologyList(this.id.replace('objTree_topology_',''))"/>
                            <splitter class="tree-splitter" ordinal="4"/>
                            <treecol id="objTree_topology_label3" flex="1" label="DUT TestBeds"
                                    persist="width ordinal hidden" ordinal="5" sortable="true" class="sortDirectionIndicator" onclick="refreshTopologyList(this.id.replace('objTree_topology_',''))"/>
                            <splitter class="tree-splitter" ordinal="6"/>                        
                            <treecol id="objTree_topology_label4" flex="1" label="Supporting TestBeds"
                                    persist="width ordinal hidden" ordinal="7" sortable="true" class="sortDirectionIndicator" onclick="refreshTopologyList(this.id.replace('objTree_topology_',''))"/>
                            <splitter class="tree-splitter" ordinal="8"/>
                            <treecol id="objTree_topology_label5" flex="1" label="Topology Type" 
                                    persist="width ordinal hidden" ordinal="9" sortable="true" class="sortDirectionIndicator" onclick="refreshTopologyList(this.id.replace('objTree_topology_',''))"/>
                            <splitter class="tree-splitter" ordinal="10"/>
                            <treecol id="objTree_topology_label6" flex="1" label="Status" 
                                    persist="width ordinal hidden" ordinal="11" sortable="true" class="sortDirectionIndicator" onclick="refreshTopologyList(this.id.replace('objTree_topology_',''))"/>
                            <splitter class="tree-splitter" ordinal="12"/>
                            <treecol id="objTree_topology_label7" flex="1" label="Owner" hidden="true"
                                    persist="width ordinal hidden" ordinal="13" sortable="true" class="sortDirectionIndicator" onclick="refreshTopologyList(this.id.replace('objTree_topology_',''))"/>   
                            <splitter class="tree-splitter" ordinal="14"/>
                            <treecol id="objTree_topology_label8" flex="1" label="Location" hidden="true"
                                    persist="width ordinal hidden" ordinal="15" sortable="true" class="sortDirectionIndicator" onclick="refreshTopologyList(this.id.replace('objTree_topology_',''))"/>
                            <splitter class="tree-splitter" ordinal="16"/>
                            <treecol id="objTree_topology_label9" flex="1" label="Usage Type" hidden="true"
                                    persist="width ordinal hidden" ordinal="17" sortable="true" class="sortDirectionIndicator" onclick="refreshTopologyList(this.id.replace('objTree_topology_',''))"/>
                            <splitter class="tree-splitter" ordinal="18"/>
                            <treecol id="objTree_topology_label10" flex="1" label="Category" hidden="true"
                                    persist="width ordinal hidden" ordinal="19" sortable="true" class="sortDirectionIndicator" onclick="refreshTopologyList(this.id.replace('objTree_topology_',''))"/>
                            <splitter class="tree-splitter" ordinal="20"/>
                            <treecol id="objTree_topology_label11" flex="1" label="Scheduler Status" 
                                    persist="width ordinal hidden" ordinal="21" sortable="false" />
                        </treecols>
                    </tree>
                </vbox>
            </tabpanel>
        </tabpanels>
    </tabbox>
</vbox>
</groupbox>
</window>
