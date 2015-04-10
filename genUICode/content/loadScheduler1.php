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
        onload="UserInterface.init();document.getElementById('daemonstatus').click();">
        
        
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

<popupset>
    <menupopup id="objList_jobresult_popup">
        <menuitem id="resultStats" class="menuitem-iconic" image="/imgs/icons/setpool/external_browser.gif" label="View Report" oncommand="UserInterface.openDialogBox(UserInterface.retrieveLBSelectedListAttrib('resulturl','objTree_jobqueue_results'), 'Load Automation Report');" />
    </menupopup>
    <menupopup id="objTree_testbedtopology_popup">
        <menu id="connect-menu" label="Make Connection" onmouseover="connectTo();" >
            <menupopup id="connect-popup">
            </menupopup>
        </menu>
    </menupopup>
    <menupopup id="objTree_loadtests_popup">
        <menuitem class="menuitem-iconic" image="/imgs/icons/set1/edit.gif" label="Edit TestCase" oncommand="document.getElementById('userInputTabs').selectedTab=document.getElementById('load_test');editTestcase();" />
        <menuitem class="menuitem-iconic" image="/imgs/icons/set1/clone.gif" label="Clone TestCase" oncommand="document.getElementById('userInputTabs').selectedTab=document.getElementById('load_test');editTestcase('clone');" />
        <menuitem class="menuitem-iconic" observes="adminprivileges" image="/imgs/icons/set1/delete.gif" label="Delete TestCase" tooltiptext="TestCase can be deleted only by Admin/Manager" oncommand="if(confirm('Are you sure you want to delete the selected testcase?\r\n')){UserInterface.requestAction(null,'/content/so.php',{'obj':'Loadtest','method':'delete_obj','uuid':document.popupNode.childNodes[document.getElementById('objTree_loadtests').currentIndex].getAttribute('loadtest_uuid') }, 'objTree_loadtests_refreshBtn', null);}"/>
    </menupopup>
</popupset>

<groupbox flex="1">
<caption label="Load Testing Mgmt" style="font-size:9pt;font-weight:bold;"/>
<vbox flex="1" equalsize="always" style="overflow-x:auto; overflow-y:auto">
    <tabbox id="userInputTabs" flex="1">
    <tabs>
        <tab id="load_sched_job" label="Run Load Test" flex="1"  onclick="if(document.getElementById('dut_menulist').selectedIndex == 0){UserInterface.loadMenuItems('dut_menulist',{'obj':'loadJob','method':'loadListItems','listName':'DUT','listType':'dropdown','selectStr':'Select DUT'});}"/>
        <tab id="load_test"  label="Create Test Case" flex="1" onclick="if(document.getElementById('test_dut_menulist').selectedIndex == 0){UserInterface.loadMenuItems('test_dut_menulist',{'obj':'loadJob','method':'loadListItems','listName':'DUT','listType':'dropdown','selectStr':'Select DUT'});}"/>
        <tab id="load_testlist" label="Saved Test Cases" flex="1" onclick="if(document.getElementById('objTree_loadtests').getElementsByTagName('treeitem').length == 0) document.getElementById('objTree_loadtests_refreshBtn').click();"/>
    </tabs>
    <tabpanels flex="1">
        <tabpanel flex="1">
          <vbox flex="1" >
              <grid>
                <rows flex="1">
                  <row>
                    <label control="jobName">Job Name</label>			
                    <textbox id="jobName" value="" width="100" placeholder="Release-TestCase-Duration" />
                  </row>
                  <row>
                        <label control="dut_menulist">DUT</label>
                        <menulist id="dut_menulist" flex="1" oncommand="if (UserInterface.mlv(this.id) != '') {
                                loadTestCaseMenu();
                                UserInterface.loadMenuItems('testbed_topology',
                                {'obj':'loadJob',
                                'method':'loadTemplates_xml',
                                'query':'q61',
                                'selectStr':'Select Topology',
                                'whereStmt':' testbedtopology_dut = \'' + UserInterface.mlv(this.id) + '\' and testbedtopology_status != \'O\'',
                                'orderBy':'testbedtopology_name ASC'                              
                                });
                                UserInterface.loadMenuItems('release',
                                {'obj':'loadJob',
                                'method':'loadListItems',
                                'listName':'release',
                                'selectStr':'Select Release',
                                'listType':'dropdown'                                
                                });
                                UserInterface.loadMenuItems('platform',
                                {'obj':'loadJob',
                                'method':'loadListItems',
                                'listName':'platform',
                                'selectStr':'Select Platform',
                                'listType':'dropdown'                                
                                })};
                               ">
                            <menupopup>
                                <menuitem label="--SELECT DUT --" value=""/>
                            </menupopup>
                        </menulist>
                        <toolbarbutton id="menuListBtn1" collapsed="true" class="refreshBtn" oncommand="
                            UserInterface.loadMenuItems('dut_menulist',
                                                  {'obj':'loadJob',
                                                  'method':'loadListItems',
                                                  'listName':'DUT',
                                                  'selectStr':'Select DUT',
                                                  'listType':'dropdown'});"/>
                  </row>
                  <row>
                    <label value="Test Bed Topology"/>
                    <menulist id="testbed_topology" flex="1" oncommand="
                        UserInterface.setLoadJobCallRate('callrate',
                                {'obj':'loadJob',
                                'method':'getTopologyDataAsText',
                                'topologyuuid': UserInterface.mlv(this.id)
                                });"> 
                        <menupopup>
                          <menuitem label="--Select Topology --" value=""/>
                        </menupopup>
                    </menulist>
                  </row>
                  <row>
                    <label value="Release"/>
                    <menulist id="release" flex="1" oncommand="loadTestCaseMenu();" >
                        <menupopup>
                          <menuitem label="--Select Release --" value=""/>
                        </menupopup>
                    </menulist>
                  </row>
                  <row>
                    <label value="Platform"/>
                    <menulist id="platform" flex="1" oncommand="loadTestCaseMenu();" >
                        <menupopup>
                          <menuitem label="--Select Platform --" value=""/>
                        </menupopup>
                    </menulist>
                  </row>
                  <row>
                    <label value="Test Case"/>
                    <menulist id="load_testcases" flex="1"> 
                        <menupopup>
                          <menuitem label="--Select TestCase --" value=""/>
                        </menupopup>
                    </menulist>
                  </row>
                  <row>
                    <label control="build">Build</label>
                    <textbox id="build" value="" placeholder="V0.0.0.0[A-Z]0" onchange="var val = trim(this.value); if(!val.match(/^V\d{1,2}\.\d\.\d\.\d[A-Z]{1,2}\d{1,2}$/)){ alert('Invalid Build Version: ' + this.value + ' .\nExpected format: V0[0].0.0{A-z}0[0]');}"/>
                  </row>
                  <row>
                    <label control="duration">Test Duration </label>
                    <textbox id="duration_hr" value="" placeholder="Hours" />
                    <textbox id="duration_min" value="" placeholder="Minutes"/>
                  </row>
                  <row>
                    <label control="callrate">Call Rate</label>
                    <groupbox id="callrate"  flex="1">
                    </groupbox>
                  </row>
                  <row>
                    <label control="holdtime">Call Hold Time</label>
                    <textbox id="holdtime" value="" placeholder="Seconds"/>
                  </row>
                  <row hidden="true">
                    <label control="loadJobId">Topology ID:</label>
                    <textbox id="loadJobId" value="" width="100"/>             
                  </row>
                  <row>
                    <spacer/>
                    <hbox flex="0" pack="left">
                    <button id="clear" flex="0" label=" CLEAR " image="/imgs/icons/set1/clear.gif" oncommand="clearJobInputs();"/>
                    <button id="submit" flex="0" label=" RUN " image="/imgs/icons/setpool/saveall_edit.gif" oncommand="addToJobQueue();"/>
                    </hbox>
                  </row>
                </rows>
              </grid>
          </vbox>
        </tabpanel>
        <tabpanel flex="1">
          <vbox flex="1" style="margin:10px; valign:middle;">
              <grid>
                <rows flex="1">                         
                  <row>
                    <label control="testid">TestID</label>
                    <textbox id="testid" value="" placeholder="Number"/>
                  </row>
                  <row>
                    <label control="testalias">Test Case Alias</label>
                    <textbox id="testalias" value="" placeholder="Valid Test Case Name"/>
                  </row>
                  <row>
                    <label control="test_dut_menulist">DUT</label>
                    <menulist id="test_dut_menulist" flex="1">
                        <menupopup>
                            <menuitem label="--SELECT DUT --" value=""/>
                        </menupopup>
                    </menulist>
                  </row>
                  <row>
                    <label control="test_release">Supporting Releases</label>
                    <textbox id="test_release" value="" placeholder="V8.1.1,V8.2.0,V8.X.X"/>
                    <button  class="createBtn" label=" Releases  " image="/imgs/icons/set1/add.gif" onclick="document.getElementById('popuptype').value='release';UserInterface.openDialogBox('/overlay/loadTest_dialog.xul','popupWindow',400,500)"/>
                  </row>
                  <row>
                    <label control="test_platform">Supporting Platforms</label>
                    <textbox id="test_platform" value="" placeholder="Annapolis,Sandybridge-1U"/>
                    <button  class="createBtn" label=" Platforms" image="/imgs/icons/set1/add.gif" onclick="document.getElementById('popuptype').value='platform';UserInterface.openDialogBox('/overlay/loadTest_dialog.xul','popupWindow',400,500)"/>
                  </row>
                  <row>
                    <label control="test_requireddevices">Required Devices</label>
                    <textbox id="test_requireddevices" value="" placeholder="SBC_HA,SBC,NXTEST"/>
                    <button  class="createBtn" label=" Devices   " image="/imgs/icons/set1/add.gif" onclick="document.getElementById('popuptype').value='requireddevices';UserInterface.openDialogBox('/overlay/loadTest_dialog.xul','popupWindow',400,500)"/>
                  </row>
                  <row>
                    <label control="testdescription">Test Description</label>
                    <textbox id="testdescription" value="" placeholder="Brief description of test case"/>
                  </row>
                  <row>
                    <spacer/>
                    <hbox flex="0" pack="left">
                    <button label=" CLEAR " image="/imgs/icons/set1/clear.gif" oncommand="clearTestCaseInputs();"/>
                    <button label=" CREATE/UPDATE " image="/imgs/icons/setpool/saveall_edit.gif" oncommand="addTestCase();"/>
                    </hbox>
                  </row>
                  <row hidden="true">
                                <textbox id="popuptype" value="" width="100"/>             
                  </row>
                  <row hidden="true">
                                <label control="testcaseid">TestCase UID:</label>
                                <textbox id="testuid" value="" width="100"/>       
                  </row>
                </rows>                                 
              </grid>
          </vbox>
        </tabpanel>
        <tabpanel flex="1">
            <vbox flex="10">    
                <groupbox flex="1">
                    <caption label="Load Test Cases" class="header" style="margin-top:5px;margin-bottom:5px;" />
                    <toolbox>
                        <toolbar id="objTree_loadtests_toolbar">
                            <toolbarbutton id="objTree_loadtests_refreshBtn" class="refreshBtn2" label="REFRESH" image="/imgs/icons/set1/refresh.gif" oncommand="refreshTestCaseTree();"/>
                        </toolbar>
                    </toolbox>
                    <tree id="objTree_loadtests" flex="1" hidecolumnpicker="false" seltype="multiple" class="tree" sortDirection="descending" enableColumnDrag="true"  sortResource="label1" query="q60" context="objTree_loadtests_popup" >
                        <treecols id="objTree_loadtests">
                            <treecol id="objTree_loadtests_label1" primary="true" flex="1" label="TestCase ID" 
                                    persist="width ordinal hidden" ordinal="1" class="sortDirectionIndicator" onclick="refreshTestCaseTree(this.id.replace('objTree_loadtests_',''))"/>
                            <splitter class="tree-splitter" ordinal="2"/>
                            <treecol id="objTree_loadtests_label2" flex="0" label="TestCase Alias"
                                    persist="width ordinal hidden" ordinal="3" sortable="true" class="sortDirectionIndicator" onclick="refreshTestCaseTree(this.id.replace('objTree_loadtests_',''))"/>
                            <splitter class="tree-splitter" ordinal="4"/>
                            <treecol id="objTree_loadtests_label3" flex="1" label="Releases"
                                    persist="width ordinal hidden" ordinal="5" sortable="true" class="sortDirectionIndicator" onclick="refreshTestCaseTree(this.id.replace('objTree_loadtests_',''))"/>
                            <splitter class="tree-splitter" ordinal="6"/>                        
                            <treecol id="objTree_loadtests_label4" flex="1" label="Platforms"
                                    persist="width ordinal hidden" ordinal="7" sortable="true" class="sortDirectionIndicator" onclick="refreshTestCaseTree(this.id.replace('objTree_loadtests_',''))"/>
                            <splitter class="tree-splitter" ordinal="8"/>
                            <treecol id="objTree_loadtests_label5" flex="1" label="Devices" 
                                    persist="width ordinal hidden" ordinal="9" sortable="true" class="sortDirectionIndicator" onclick="refreshTestCaseTree(this.id.replace('objTree_loadtests_',''))"/>
                            <splitter class="tree-splitter" ordinal="10"/>
                            <treecol id="objTree_loadtests_label6" flex="1" label="Dut" hidden="true" 
                                    persist="width ordinal hidden" ordinal="11" sortable="true" class="sortDirectionIndicator" onclick="refreshTestCaseTree(this.id.replace('objTree_loadtests_',''))"/>
                        </treecols>
                    </tree>
                </groupbox>    
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
            <tabs>
                <tab id="daemonstatus" label="Job Status" flex="1" onclick="if(document.getElementById('objTree_jobqueue').getElementsByTagName('treeitem').length == 0) document.getElementById('jobqueue_refreshBtn').click();document.getElementById('testbedtopology_refreshBtn').click()"/>
                <tab id="submission" label="Test Results" flex="1" onclick="if(document.getElementById('objTree_jobqueue_results').getElementsByTagName('treeitem').length == 0) document.getElementById('jobqueue_results_refreshBtn').click();"/>
            </tabs>
            <tabpanels flex="1">
                <tabpanel flex="1">
                  
                  <vbox flex="1">
                    <vbox flex="0">
                      <hbox flex="1">
                      <groupbox id="groupbox2"  flex="1">
                        <caption label="CURRENT JOB - REMOTE LOG" class="header" style="margin-top:5px;margin-bottom:5px;"/>
                          <toolbox>
                          <toolbar id="remote_log_textbox_toolbar">
                            <toolbarbutton id="remote_log_toolbarbutton" class="log_refreshBtn" label="REFRESH" image="/imgs/icons/set1/refresh.gif" oncommand="viewLog(0);" tooltiptext="Click to refresh remote log"/>
                            <toolbarseparator />
                            <toolbarbutton id="tail_log_toolbarbutton" class="refreshBtn5" label="Tail Automatio Log" image="/imgs/icons/set1/refresh.gif"  oncommand="viewLog(1);" tooltiptext="Click to refresh remote log"/>                 
                            <spacer flex="1" />
                          </toolbar>
                          </toolbox>
                          <vbox flex="1" style="margin: 3px 5px 3px 5px;">
                            <stack style="border: 0px solid black;">
                              <textbox id="remote_log_textbox" class="plain" spellcheck="false" readonly="true" flex="1" rows="7" multiline="true" style="background-color:#000000; color:#FFFFFF;"/>
                              <resizer dir="bottomright" style="cursor: se-resize;" element="_parent" right="5" bottom="3" width="10" height="10"/>
                            </stack>			  
                          </vbox>
                          
                      </groupbox>
                      </hbox>
                    </vbox>
                    <splitter resizeafter="flex" />		    
                    <vbox flex="1">
                      <hbox flex="1">
                      <groupbox id="groupbox2"  flex="1">
                        <caption label="TESTBED TOPOLOGY STATUS" class="header" style="margin-top:5px;margin-bottom:5px;"/>
                          <toolbox>
                          <toolbar id="testbedtopology_toolbar">
                            <toolbarbutton id="testbedtopology_refreshBtn" class="refreshBtn2" label="REFRESH" image="/imgs/icons/set1/refresh.gif" oncommand="refreshTestBedTopology();"/>
                            <spacer flex="1" />
                            <toolbarseparator />			    
                            <box align="center" flex="0">
                              <label control="counter11" value="Object Cnt: "/>
                              <label id="counter11" observes="objTree_testbedtopology_count"/>
                            </box>
                          </toolbar>
                          </toolbox>
                          <tree id="objTree_testbedtopology" flex="1" hidecolumnpicker="false" class="tree" seltype="single" sortDirection="descending" enableColumnDrag="true"  sortResource="label1" query="o257"  onselect="" context="objTree_testbedtopology_popup">
                            <treecols id="objTree_testbedtopology_treeCols">
                                <treecol id="objTree_testbedtopology_label1" flex="1" label="Topology Name" 
                                         persist="width ordinal hidden" ordinal="1" class="sortDirectionIndicator" onclick="refreshTestBedTopology(this.id.replace('objTree_testbedtopology_',''));" />
                                <splitter class="tree-splitter" ordinal="2"/>
                                <treecol id="objTree_testbedtopology_label2" flex="1" label="DUT TestBeds"
                                         persist="width ordinal hidden" ordinal="3" sortable="true" class="sortDirectionIndicator" onclick="refreshTestBedTopology(this.id.replace('objTree_testbedtopology_',''));" />
                                <splitter class="tree-splitter" ordinal="4"/>
                                <treecol id="objTree_testbedtopology_label3" flex="1" label="Other TestBeds" 
                                         persist="width ordinal hidden" ordinal="5" sortable="true" class="sortDirectionIndicator" onclick="refreshTestBedTopology(this.id.replace('objTree_testbedtopology_',''));" />
                                <splitter class="tree-splitter" ordinal="6"/>
                                <treecol id="objTree_testbedtopology_label4" flex="1" label="Status"
                                         persist="width ordinal hidden" ordinal="7" sortable="true" class="sortDirectionIndicator" onclick="refreshTestBedTopology(this.id.replace('objTree_testbedtopology_',''));" />
                            </treecols>
                          </tree>
                       </groupbox>
                      </hbox>
                    </vbox>
                    <splitter resizeafter="flex" />		    
                    <vbox flex="1">
                      <hbox flex="1">
                      <groupbox id="groupbox2"  flex="1">
                        <caption label="JOB QUEUE STATUS" class="header" style="margin-top:5px;margin-bottom:5px;"/>
                          <toolbox>
                          <toolbar id="jobqueue_toolbar">
                            <toolbarbutton id="jobqueue_refreshBtn" class="refreshBtn2" label="REFRESH" image="/imgs/icons/set1/refresh.gif" oncommand="refreshQueue(1,'objTree_jobqueue');"/>
                            <spacer flex="1" />
                            <checkbox id="autoRefresh" label="Auto Refresh" checked="false" disabled="true" tooltiptext="set an auto refresh flag for every 10 seconds" oncommand="(this.checked)?autoRefresh(1):autoRefresh(0);"/>
                        <toolbarseparator />			    
                            <box align="center" flex="0">
                              <label control="counter10" value="Object Cnt: "/>
                              <label id="counter10" observes="objTree_jobqueue_count"/>
                            </box>
                          </toolbar>
                          </toolbox>
                          <tree id="objTree_jobqueue" flex="1" hidecolumnpicker="false" class="tree" seltype="single" sortDirection="descending" enableColumnDrag="true"  sortResource="label1" query="o254" context="objTree_jobqueue_popup" onselect="if(this.view.selection.count == 1){document.getElementById('remote_log_toolbarbutton').click();if(document.getElementById('autoRefresh').disabled) document.getElementById('autoRefresh').disabled=false;}">
                            <treecols id="objTree_jobqueue_treeCols">
                                <treecol id="objTree_jobqueue_label1" flex="1" label="Job Name" 
                                         persist="width ordinal hidden" ordinal="1" class="sortDirectionIndicator"/>
                                <splitter class="tree-splitter" ordinal="2"/>
                                <treecol id="objTree_jobqueue_label2" flex="1" label="Author"
                                         persist="width ordinal hidden" ordinal="3" sortable="true" class="sortDirectionIndicator" />
                                <splitter class="tree-splitter" ordinal="4"/>
                                <treecol id="objTree_jobqueue_label3" flex="1" label="Status" 
                                         persist="width ordinal hidden" ordinal="5" sortable="true" class="sortDirectionIndicator" />
                                <splitter class="tree-splitter" ordinal="6"/>
                                <treecol id="objTree_jobqueue_label4" flex="1" label="Release"
                                         persist="width ordinal hidden" ordinal="7" sortable="true" class="sortDirectionIndicator" />
                                <splitter class="tree-splitter" ordinal="8"/>
                                <treecol id="objTree_jobqueue_label5" flex="1" label="Build" 
                                         persist="width ordinal hidden" ordinal="9" sortable="true" class="sortDirectionIndicator" />
                                <splitter class="tree-splitter" ordinal="10"/>
                                <treecol id="objTree_jobqueue_label6" flex="1" label="Test" 
                                         persist="width ordinal hidden" ordinal="11" sortable="true" class="sortDirectionIndicator" />
                                <splitter class="tree-splitter" ordinal="12"/>
                                <treecol id="objTree_jobqueue_label7" flex="1" label="log"  hidden="true"
                                         persist="width ordinal hidden" ordinal="13" sortable="true" class="sortDirectionIndicator" />
                            </treecols>
                          </tree>
                       </groupbox>
                      </hbox>
                    </vbox>
                  </vbox>
                </tabpanel>
                <tabpanel flex="1">
                    <vbox flex="1" style="overflow:auto">
                      <hbox>
                      <label value="Job Results" class="header" style="margin:2px 5px 2px 5px;"/>
                      </hbox>
                        <vbox flex="1">
                            <toolbox>
                                <toolbar id="jobqueue_results_toolbar">
                                    <label class="header" value="Filter by:" style="margin-top:7px;margin-bottom:5px;"/>
                                    <menulist style="min-width:100px;" id="filter_jobresults" oncommand="">
                                        <menupopup>
                                            <menuitem label="Job Name" value="loadJob_name"/>
                                            <menuitem label="Test Case" value="loadJob_testcaseuuid"/>
                                            <menuitem label="Author" value="loadJob_username"/>
                                            <menuitem label="Start Time" value="StartTime"/>
                                            <menuitem label="End Time" value="EndTime"/>
                                            <menuitem label="Release" value="loadJob_release"/>
                                            <menuitem label="Build" value="loadJob_build"/>
                                            <menuitem label="Result" value="executionStatus"/>
                                        </menupopup>
                                    </menulist>
     
                                    <radiogroup id="radiogroup_jobresults" orient="horizontal" oncommand="">
                                        <radio label="Matches" value='REGEXP' selected="true"/>
                                        <radio label="Does NOT match" value='NOT REGEXP'/>
                                    </radiogroup>
                                    <textbox id="objTextbox_jobqueue_results_filter" value=".*" 
                                         size="30" maxlength="1024" flex="1" persist="value"
                                         oncommand="document.getElementById('jobqueue_results_refreshBtn').click();" type="search" searchbutton="true" emptytext=".*" timeout="0" onfocus="select();"/>
                                    <toolbarbutton id="jobqueue_results_refreshBtn" class="refreshBtn3" label="REFRESH" image="/imgs/icons/set1/refresh.gif" oncommand="refreshJobResults();"/>
                                    <spacer flex="1" />
                                    <toolbarseparator />
                                    <box align="center" flex="0">
                                        <label control="counter12" value="Object Cnt: "/>
                                        <label id="counter12" observes="objTree_jobqueue_results_count"/>
                                    </box>
                                </toolbar>
                            </toolbox>
                            <tree id="objTree_jobqueue_results" flex="1" hidecolumnpicker="false" seltype="multiple" class="tree" sortDirection="descending" enableColumnDrag="true"  sortResource="label1" query="o255" context="objList_jobresult_popup" >
                                <treecols id="objTree_jobqueue_results_treeCols">
                                    <treecol id="objTree_jobqueue_results_label1" primary="true" flex="1" label="Start Time" 
                                            persist="width ordinal hidden" ordinal="1" class="sortDirectionIndicator" onclick="refreshJobResults(this.id.replace('objTree_jobqueue_results_',''))"/>
                                    <splitter class="tree-splitter" ordinal="2"/>
                                    <treecol id="objTree_jobqueue_results_label2" flex="0" label="Job Name"
                                            persist="width ordinal hidden" ordinal="3" sortable="true" class="sortDirectionIndicator" onclick="refreshJobResults(this.id.replace('objTree_jobqueue_results_',''))"/>
                                    <splitter class="tree-splitter" ordinal="4"/>
                                    <treecol id="objTree_jobqueue_results_label3" flex="1" label="Author"
                                            persist="width ordinal hidden" ordinal="5" sortable="true" class="sortDirectionIndicator" onclick="refreshJobResults(this.id.replace('objTree_jobqueue_results_',''))"/>
                                    <splitter class="tree-splitter" ordinal="6"/>                        
                                    <treecol id="objTree_jobqueue_results_label4" flex="1" label="Release"
                                            persist="width ordinal hidden" ordinal="7" sortable="true" class="sortDirectionIndicator" onclick="refreshJobResults(this.id.replace('objTree_jobqueue_results_',''))"/>
                                    <splitter class="tree-splitter" ordinal="8"/>
                                    <treecol id="objTree_jobqueue_results_label5" flex="1" label="Build" 
                                            persist="width ordinal hidden" ordinal="9" sortable="true" class="sortDirectionIndicator" onclick="refreshJobResults(this.id.replace('objTree_jobqueue_results_',''))"/>
                                    <splitter class="tree-splitter" ordinal="10"/>
                                    <treecol id="objTree_jobqueue_results_label6" flex="1" label="TestCase" 
                                            persist="width ordinal hidden" ordinal="11" sortable="true" class="sortDirectionIndicator" onclick="refreshJobResults(this.id.replace('objTree_jobqueue_results_',''))"/>
                                    <splitter class="tree-splitter" ordinal="12"/>
                                    <treecol id="objTree_jobqueue_results_label7" flex="0" label="Result"
                                            persist="width ordinal hidden" ordinal="13" sortable="true" class="sortDirectionIndicator" onclick="refreshJobResults(this.id.replace('objTree_jobqueue_results_',''))"/>
                                    <splitter class="tree-splitter" ordinal="14"/>
                                    <treecol id="objTree_jobqueue_results_label8" flex="1" label="End Time" hidden="true"
                                            persist="width ordinal hidden" ordinal="15" sortable="true" class="sortDirectionIndicator" onclick="refreshJobResults(this.id.replace('objTree_jobqueue_results_',''))"/>
                                    <splitter class="tree-splitter" ordinal="16"/>
                                    <treecol id="objTree_jobqueue_results_label9" flex="1" label="Result URL" hidden="true"
                                            persist="width ordinal hidden" ordinal="17" sortable="true" class="sortDirectionIndicator" onclick="refreshJobResults(this.id.replace('objTree_jobqueue_results_',''))"/>
                                </treecols>
                            </tree>
                        </vbox>
                    </vbox>
                </tabpanel>
            </tabpanels>
        </tabbox>
    </vbox>
</vbox>
</window>
