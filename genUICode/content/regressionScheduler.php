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
<?xul-overlay href="/overlay/RegressionScheduler.xul"?>
<window id="objectadmin" orient="horizontal"
        xmlns:html="http://www.w3.org/1999/xhtml"
        xmlns="http://www.mozilla.org/keymaster/gatekeeper/there.is.only.xul"
        onload="UserInterface.init();document.getElementById('regdaemonstatus').click();UserInterface.clearSessionStorage();UserInterface.setClock('regtime');">
        
        
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
  <broadcaster id="objTree_suitesqueue_count" value="0"/>
  <broadcaster id="objTree_jobqueue_results_count" value="0"/>
  <broadcaster id="objTree_testbedtopology_count" value="0"/>
  <broadcaster id="objTree_regtests_count" value="0"/>
  
</broadcasterset>

<popupset>
    <menupopup id="regressionTestbedDetails_popup">
        <menuitem class="menuitem-iconic" image="/imgs/icons/set1/uparrow.gif" label="Upgrade To" onclick="storeTargetBuild();" />
    </menupopup>
    <menupopup id="regSelectedTopology_popup" onpopupshowing="if(document.getElementById('regSelectedTopology').selectedCount != 1){document.getElementById('showtbdetails').style.display='none';}else{document.getElementById('showtbdetails').style.display='';};" >
        <menuitem id="showtbdetails" class="menuitem-iconic" image="/imgs/icons/set1/listing.gif" label="Show TestBed Details" onclick="if(document.getElementById('regSelectedTopology').selectedCount == 1) { document.getElementById('tdDetails').style.display='';
        UserInterface.loadListBx('regressionTestbedDetails',
                                {'obj':'loadJob',
                                'method':'getTestBedDetails',
                                 'topologyuuid': UserInterface.retrieveLBSelectedListAttrib('id','regSelectedTopology')
                                });};" />
    </menupopup>
    <menupopup id="objList_regjobresult_popup" onpopupshowing="if(document.getElementById('objTree_jobqueue_results').view.selection.count > 1){document.getElementById('reexecute').style.display='none';document.getElementById('deleteresult').style.display='none';document.getElementById('grouplogs').style.display='';}else{document.getElementById('reexecute').style.display='';document.getElementById('deleteresult').style.display='';document.getElementById('grouplogs').style.display='none';}if (UserInterface.retrieveLBSelectedListAttrib('label','objTree_jobqueue_results',9) == 1 )   {document.getElementById('resumeEx').style.display='';} else {document.getElementById('resumeEx').style.display='none';}; ">
        <menuitem class="menuitem-iconic" image="/imgs/icons/set1/redo.gif" label="ReExecute" id="reexecute" onclick="UserInterface.clearTreeView('objTree_jobqueue_suite_results');UserInterface.openDialogBox('/overlay/regressionScheduler_rerun.xul','popupWindow',800,900)" />
        <menuitem class="menuitem-iconic" image="/imgs/icons/setpool/run_exc.gif" label="Resume Execution" id="resumeEx" onclick="UserInterface.requestAction(null,'/content/so.php',{'obj':'Regressionjob','method':'resumeExecution','resumeJobId':UserInterface.retrieveLBSelectedListAttrib('id','objTree_jobqueue_results')}, '', null);" />
        <menuitem class="menuitem-iconic" image="/imgs/icons/set1/summary.gif" label="Send Summary Report" onclick="UserInterface.requestAction(null,'/content/so.php',{'obj':'Regressionjob','method':'generateSummaryReport','regressionId':UserInterface.retrieveLBSelectedListAttrib('id','objTree_jobqueue_results'), 'job':UserInterface.retrieveLBSelectedListAttrib('label','objTree_jobqueue_results',1),'auth':UserInterface.retrieveLBSelectedListAttrib('label','objTree_jobqueue_results',2),'bld':UserInterface.retrieveLBSelectedListAttrib('label','objTree_jobqueue_results',3),'rel':UserInterface.retrieveLBSelectedListAttrib('label','objTree_jobqueue_results',4)}, '', null);" />
        <menuitem class="menuitem-iconic" image="/imgs/icons/set1/summary.gif" label="Group Automation Logs" id="grouplogs" onclick="UserInterface.requestAction(null,'/content/so.php',{'obj':'Regressionjob','method':'groupAutomationLogs','regressionId':UserInterface.retrieveLBSelectedListAttrib('id','objTree_jobqueue_results'),'build':UserInterface.retrieveLBSelectedListAttrib('label','objTree_jobqueue_results',4),'reexecuted':UserInterface.retrieveLBSelectedListAttrib('label','objTree_jobqueue_results',11), 'logpath':UserInterface.retrieveLBSelectedListAttrib('label','objTree_jobqueue_results',10)}, '', null);" />
        <menuitem class="menuitem-iconic" image="/imgs/icons/set1/delete.gif" label="Delete" id="deleteresult" oncommand="if ((document.getElementById('currentUser').getAttribute('value') ==  UserInterface.retrieveLBSelectedListAttrib('label','objTree_jobqueue_results',2) ) || (document.getElementById('adminprivileges').getAttribute('hidden') == 'false' )){UserInterface.requestAction(null,'/content/so.php',{'obj':'Regressionjob','method':'delete_obj','uuid':document.popupNode.childNodes[document.getElementById('objTree_jobqueue_results').currentIndex].getAttribute('id') }, 'jobqueue_results_refreshBtn', null);}else{alert('you are not authorized to perform this action')};" />
        <menuitem class="menuitem-iconic" image="/imgs/icons/setpool/external_browser.gif" label="View Logs" oncommand=" if (UserInterface.retrieveLBSelectedListAttrib('label','objTree_jobqueue_results',10) != '') UserInterface.openDialogBox(UserInterface.retrieveLBSelectedListAttrib('label','objTree_jobqueue_results',10), 'Load Summary Report');" />
    </menupopup>
    <menupopup id="objList_regjobresultsuite_popup">
        <menuitem class="menuitem-iconic" image="/imgs/icons/set1/redo.gif" label="ReExecute"  onclick="UserInterface.openDialogBox('/overlay/regressionScheduler_rerun.xul','popupWindow',800,900)" />
        <menuitem class="menuitem-iconic" image="/imgs/icons/set1/delete.gif" label="Delete" onclick="deleteRegJob();" />
    </menupopup>
    <menupopup id="objTree_regtestbedtopology_popup">
        <menu id="connect-menu" label="Make Connection" onmouseover="connectTo();" >
            <menupopup id="connect-popup">
            </menupopup>
        </menu>
    </menupopup>
    <menupopup id="objTree_jobqueue_popup" onpopupshown="if(document.getElementById( 'counter10').value == 0) {document.getElementById('objTree_jobqueue_popup').hidePopup();}">
        <menuitem id="queRegJobforCancel" class="menuitem-iconic" image="/imgs/icons/set1/delete2.gif" label="Queue For Graceful Stop" onclick="if ((document.getElementById('currentUser').getAttribute('value') ==  UserInterface.retrieveLBSelectedListAttrib('label','objTree_jobqueue',1) ) || (document.getElementById('adminprivileges').getAttribute('hidden') == 'false' )){jobCancel(0);} else { alert('you are not authorized to perform this action'); };" />
        <menuitem id="queRegJobforForcedCancel" class="menuitem-iconic" image="/imgs/icons/set1/delete2.gif" label="Queue For Forced Stop" onclick="if ((document.getElementById('currentUser').getAttribute('value') ==  UserInterface.retrieveLBSelectedListAttrib('label','objTree_jobqueue',1) ) || (document.getElementById('adminprivileges').getAttribute('hidden') == 'false' )){jobCancel(1);} else { alert('you are not authorized to perform this action'); };" />
    </menupopup>
    <menupopup id="objTree_regsuite_popup"  onpopupshown="if(document.getElementById('objTree_regtests').view.selection.count > 1) {document.getElementById('listtests').style.display='none';document.getElementById('edit-testsuite').style.display='none';}else{ document.getElementById('listtests').style.display=''; document.getElementById('edit-testsuite').style.display='';}">
        <menuitem class="menuitem-iconic" image="/imgs/icons/set1/edit.gif" label="Edit TestSuite" id="edit-testsuite" oncommand="document.getElementById('userInputTabs').selectedTab=document.getElementById('reg_sched_job1');editTestSuite();" />
        <menuitem class="menuitem-iconic" observes="adminprivileges" image="/imgs/icons/set1/delete.gif" label="Delete TestSuite" tooltiptext="TestSuite can be deleted only by Admin/Manager" oncommand="if(confirm('Are you sure you want to delete the selected testsuite?\r\n')){var suites = new Array(); suites = UserInterface.retrieveLBSelectedListAttrib('id','objTree_regtests').split(','); UserInterface.requestAction(null,'/content/so.php',{'obj':'Regressionsuite','method':'delete_obj','where':'regressionsuite_uuid in (\'' + suites.join('\',\'') + '\')' }, 'objTree_regtests_refreshBtn', null);}"/>
        <menu id="listtests" label="Test Case(s)" onmouseover="listTestCases();" >
            <menupopup id="testcases-popup">
            </menupopup>
        </menu>
        <menuitem class="menuitem-iconic" image="/imgs/icons/set1/edit.gif" label="Add Release" id="suiteReleasePointer" oncommand="document.getElementById('addrelease').openPopup(document.getElementById(this.id), 'before_start');" />
    </menupopup>
    
    <panel noautohide="true" onpopupshowing="if (event.target == this) listRelease();" id="addrelease" position="at_pointer">
        <hbox align="center">
            <vbox>
                <hbox>
                    <menulist id="release-popup" flex="0">
                    </menulist>
                    <button label="Add" onclick="addSuiteRelease();"/>
                    <button label="Close" onclick="document.getElementById('addrelease').hidePopup();"/>
                </hbox>
            </vbox>
        </hbox>
    </panel>
    <panel id="upgradetopanel" noautohide="true"  onpopupshowing="if (event.target == this) getBuilds();">
      <hbox align="start">
        <vbox>
          <description value="Upgrade To"/>
          <hbox>
            <menulist id="regression_builds" flex="0">
                <menupopup onpopupshowing=''>
                    <menuitem label="--SELECT BUILD --" value=""/>
                </menupopup>
            </menulist>        
            <button label="Ok" onclick="storeTargetBuild();"/>
            <button label="Close" onclick="document.getElementById('upgradetopanel').hidePopup();"/>
          </hbox>
        </vbox>
      </hbox>
    </panel>
</popupset>



<groupbox flex="1">
<caption label="Regression Scheduler Mgmt" style="font-size:9pt;font-weight:bold;"/>
<vbox flex="1" equalsize="always" style="overflow-x:auto; overflow-y:auto">
    <tabbox id="userInputTabs" flex="1">
    <tabs>
        <tab id="reg_sched_job" label="Run Regression Test" flex="1"/>
        <tab id="reg_sched_job1" label="Create Test Suite" flex="1" onclick="if(document.getElementById('test_dut_menulist').selectedIndex == 0){UserInterface.loadMenuItems('test_dut_menulist',{'obj':'Regressionjob','method':'loadListItems','listName':'DUT','listType':'dropdown','selectStr':'Select DUT'});}"/>
        <tab id="reg_import_test" label="Import Test Suites" flex="1" onclick="if(document.getElementById('import_dut_menulist').selectedIndex == 0){UserInterface.loadMenuItems('import_dut_menulist',{'obj':'Regressionjob','method':'loadListItems','listName':'DUT','listType':'dropdown','selectStr':'Select DUT'});}"/>
        <tab id="reg_testlist" label="Saved Test Suites" flex="1" onclick="if(document.getElementById('objTree_regtests').getElementsByTagName('treeitem').length == 0){ document.getElementById('objTree_regtests_refreshBtn').click();}"/>
    </tabs>
    <tabpanels flex="1" >
        <tabpanel flex="1">
          <vbox flex="0" >
              <grid>
                <rows flex="1">
                  <row>
                    <label control="jobName">Job Name</label>			
                    <textbox id="regJobName" value="" width="100" placeholder="Release-JobAlias" />
                  </row>
                  <row>
                    <label></label>
                    <caption label="Format: Release - JobAlias" style="font-size:9pt;font-weight:bold;"/>                    
                  </row>
                  <row>
                    <label></label>
                  </row>
                  <row>
                        <label control="reg_dut_menulist">DUT Type</label>
                        <menulist id="reg_dut_menulist" flex="1" oncommand="if (UserInterface.mlv(this.id) != '') {
                                UserInterface.refresh('regAvailableTopology',null,
                                {'where':' testbedtopology_dut = \'' + UserInterface.mlv(this.id) + '\' and testbedtopology_status != \'O\' and testbedtopology_type = \'FR\' and testbedtopology_usage = \'0\' or testbedtopology_username = \'' + document.getElementById('currentUser').getAttribute('value') + '\'',
                                'orderBy':'testbedtopology_name ASC' }, null)
                                } else {
                                UserInterface.clearListBox('regAvailableTopology');
                                };                                document.getElementById('regdate').value=document.getElementById('calender1').value;
                               ">
                            <menupopup>
                                <menuitem label="--SELECT DUT --" value=""/>
                            </menupopup>
                        </menulist>
                        <toolbarbutton id="menuListBtn1" collapsed="true" class="refreshBtn" oncommand="
                            UserInterface.loadMenuItems('reg_dut_menulist',
                                                  {'obj':'Regressionjob',
                                                  'method':'loadListItems',
                                                  'listName':'DUT',
                                                  'selectStr':'Select DUT',
                                                  'listType':'dropdown'});"/>
                  </row>
                  <row>
                    <label value="Test Bed Topology" />
                    <groupbox flex="1" style="overflow:auto">
                        <hbox flex="1">
                            <listbox id="regAvailableTopology" class="listobject" sortDirection="ascending" sortResource="label1"  query="o253"  whereStmt="" flex="1" seltype="multiple">
                                <listhead>
                                    <listheader id="regAvailableTopology_label1" label="Available Topology" flex="1"/>
                                </listhead>
                                <listcols>
                                    <listcol flex="1"/>
                                </listcols>
                            </listbox>
                        </hbox>
                        <toolbox align="center">
                            <toolbar id="nav-toolbar">
                                <toolbarbutton id="addBtn" label="ADD" image="/imgs/icons/set1/add.gif" oncommand="UserInterface.appendToListBox('regAvailableTopology','regSelectedTopology');" tooltiptext="Click to add selected Testbed"/>
                                <toolbarseparator />
                                <toolbarbutton id="deleteBtn" label="REMOVE" image="/imgs/icons/set1/delete.gif"                                oncommand="UserInterface.dropSelected('regSelectedTopology');" tooltiptext="Click to remove the selected Testbed"/>
                            </toolbar>
                        </toolbox>
                        <hbox flex="1">
                            <listbox id="regSelectedTopology" class="listobject" sortDirection="ascending" sortResource="label1" flex="1" context="regSelectedTopology_popup" seltype="multiple">
                                <listhead>
                                    <listheader id="regSelectedTopology_label1" label="Selected Topology" flex="1"/>
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
                    <groupbox flex="1" id="tdDetails" style="overflow:auto;display:none;" >
                        <caption class="header" label="TestBed Details:" style="margin-top:5px;margin-bottom:5px;"/>
                        <hbox flex="1" >
                            <listbox id="regressionTestbedDetails" class="listobject" sortDirection="ascending" sortResource="label1"  query=""  whereStmt="" flex="1"  context="upgradetopanel">
                                <listhead>
                                    <listheader id="regressionTestbedDetails_label1" label="Name" flex="1"/>
                                    <listheader id="regressionTestbedDetails_label2" label="Build" flex="1"/>
                                    <listheader id="regressionTestbedDetails_label3" label="Platform" flex="1"/>
                                    <listheader id="regressionTestbedDetails_label4" label="Release" flex="1"/>
                                    <listheader id="regressionTestbedDetails_label5" label="Obj Type" flex="1"/>
                                </listhead>
                                <listcols>
                                    <listcol flex="0"/>
                                    <listcol flex="0"/>
                                    <listcol flex="0"/>
                                    <listcol flex="0"/>
                                    <listcol flex="0"/>
                                </listcols>
                            </listbox>
                        </hbox>
                    </groupbox>
                  </row>
                  <row>
                    <label></label>
                    <groupbox>
                      <caption class="header" label="Regression Type:" style="margin-top:5px;margin-bottom:5px;"/>
                      <vbox>
                          <radiogroup id="reg_type" orient="horizontal" >
                              <radio label="Full" value='FULL' selected="true" />
                              <radio label="Smoke" value='SMOKE' />
                              <radio label="DSP" value='DSP' />
                              <radio label="NEXUNIT" value='NEXUNIT' />
                              <radio label="NEGATIVE" value='NEGATIVE' />
                          </radiogroup>
                      </vbox>
                    </groupbox>
                  </row>
                  <row>
                    <label value="Test Suites"/>
                    <button  class="createBtn" label=" Suites/Tests  " image="/imgs/icons/set1/add.gif" onclick="UserInterface.openDialogBox('/overlay/regressionScheduler_dialog.xul','popupWindow',800,900)"/>
                  </row>
                  <row>
                    <label></label>
                    <groupbox>
                      <caption class="header" label="Control Flags:" style="margin-top:5px;margin-bottom:5px;"/>
                      <vbox>
                        <row>
                            <checkbox id="debugMode" label="Debug Mode" checked="false" oncommand="if(this.checked){
                            document.getElementById('debugdata').style.display='';
                            document.getElementById('debugflags').value='nxconfig.pl -e debug-modpkt -v 4\nnxconfig.pl -e debug-modsip -v 4\nnxconfig.pl -e debug-modbridge -v 4\nnxconfig.pl -e debug-modfce -v 4\nnxconfig.pl -e debug-modtcf -v 4\nnxconfig.pl -e sdebug-level -v 4';
                            } else {document.getElementById('debugdata').style.display='none';};"/>
                            <checkbox id="skipBaseConfig" label="Skip BaseConfig" checked="false" />
                            <checkbox id="reg_rerun" label="ReRun Failed" checked="false" />
                        </row>
                        <splitter style="width: 1px; border:0px; min-width: 0%;" />
                        <row>
                            <label style="margin-top:5px;margin-bottom:5px;">Mail Notification: </label>
                            <radiogroup id="reg_notify" orient="horizontal" >
                                <radio label="Always" value='0' selected="true" />
                                <radio label="Failed" value='1' />
                            </radiogroup>
                        </row>
                        <splitter style="width: 1px; border:0px; min-width: 0%;" />
                        <row>
                            <label style="margin-top:5px;margin-bottom:5px;">Notify User(s): </label>
                            <textbox id="reg_notifyuser" style="margin-top:5px;margin-bottom:5px;" value="" width="200" placeholder="user1@test.com,user2@test.com" />
                        </row>
                      </vbox>
                    </groupbox>
                  </row>
                  <row id="debugdata" style="display:none;">
                    <label></label>
                    <groupbox flex="1">
                        <caption class="header" label="Debug Commands:" style="margin-top:5px;margin-bottom:5px;"/>
                        <vbox flex="1">
                            <textbox id="debugflags" value="" multiline="true" style="resize:both;overflow:auto;" />
                        </vbox>
                    </groupbox>
                  </row>
                  <row hidden="true">
                    <label control="regJobId">Job Id:</label>
                    <textbox id="regJobId" value="" width="100"/>             
                  </row>
                  <row hidden="true">
                    <label control="reg_release_filter">Job Id:</label>
                    <textbox id="reg_release_filter" value="" width="100"/>             
                  </row>
                  <row flex="1">
                    <label></label>
                    <groupbox flex="1">
                      <caption class="header" label="Schedule On:" style="margin-top:5px;margin-bottom:5px;"/>
                        <vbox flex="1">
                            <row flex="1" >
                                <label>Date : </label>
                                <textbox id="regdate" value="" placeholder='YYYY-MM-DD' width="90"/>
                                <toolbarbutton id="mycalendar" flex="1" image="/imgs/icons/set1/calendar.gif" width="10" onclick="if(document.getElementById('calender1').style.display == 'none')document.getElementById('calender1').style.display=''; else document.getElementById('calender1').style.display='none';"/>
                            </row>
                            <row>
                                <datepicker id="calender1" type="grid" style="display:none;" onchange="document.getElementById('regdate').value=this.value;document.getElementById('calender1').style.display='none';"/>
                            </row>
                            <row>                                
                                <label>Time: </label>
                                <timepicker id="regtime" value="" onclick="document.getElementById('scheduletime').value=1;"/>
                                <textbox id="scheduletime" value="" width="100" style="display:none;"/>
                            </row>
                        </vbox>
                    </groupbox>
                  </row>
                  <row>
                    <spacer/>
                    <hbox flex="0" pack="left">
                    <button id="clear" flex="0" label=" CLEAR " image="/imgs/icons/set1/clear.gif" oncommand="clearJobInputs(); "/>
                    <button id="submit" flex="0" label=" RUN " image="/imgs/icons/setpool/saveall_edit.gif" oncommand="addToRegressionQueue();"/>
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
                    <label control="regsuitename">Test Suite name</label>
                    <textbox id="regsuitename" value="" placeholder="Valid Test Suite Name"/>
                  </row>
                  <row>
                        <label control="test_dut_menulist">Type</label>
                        <menulist id="test_dut_menulist" flex="1" >
                            <menupopup>
                                <menuitem label="--SELECT DUT --" value=""/>
                            </menupopup>
                        </menulist>
                  </row>
                  <row>
                    <label control="test_release">Supporting Releases</label>
                    <textbox id="test_release" value="" placeholder="V8.1.1,V8.2.0,V8.X.X"/>
                    <button  class="createBtn" label=" Releases  " image="/imgs/icons/set1/add.gif" onclick="document.getElementById('popuptype').value='release';document.getElementById('dutid').value='test_dut_menulist';UserInterface.openDialogBox('/overlay/loadTest_dialog.xul','popupWindow',400,500)"/>
                  </row>
                  <row>
                    <label control="test_platform">Supporting Platforms</label>
                    <textbox id="test_platform" value="" placeholder="Annapolis,Sandybridge-1U"/>
                    <button  class="createBtn" label=" Platforms" image="/imgs/icons/set1/add.gif" onclick="document.getElementById('popuptype').value='platform';document.getElementById('dutid').value='test_dut_menulist';UserInterface.openDialogBox('/overlay/loadTest_dialog.xul','popupWindow',400,500)"/>
                  </row>
                  <row>
                    <label control="test_requireddevices">Required Devices</label>
                    <textbox id="test_requireddevices" value="" placeholder="SBC_HA,SBC,NXTEST"/>
                    <button  class="createBtn" label=" Devices   " image="/imgs/icons/set1/add.gif" onclick="document.getElementById('popuptype').value='requireddevices';document.getElementById('dutid').value='test_dut_menulist';UserInterface.openDialogBox('/overlay/loadTest_dialog.xul','popupWindow',400,500)"/>
                  </row>
                  <row>
                    <label control="reg_suitetests">Test Cases</label>
                    <textbox id="reg_suitetests" value="" placeholder="test case name "/>
                    <button  class="createBtn" label=" Tests   " image="/imgs/icons/set1/add.gif"/>
                  </row>
                  <row>
                    <label control="reg_suitepath">Path</label>
                    <textbox id="reg_suitepath" value="" placeholder="smoke.qms/abc.qms"/>
                  </row>
                  <row>
                    <label control="suitedescription">Suite Description</label>
                    <textbox id="suitedescription" value="" multiline="true" placeholder="Brief description of test case"/>
                  </row>
                  <row>
                    <label></label>
                    <groupbox>
                      <caption class="header" label="Regression Type:" style="margin-top:5px;margin-bottom:5px;"/>
                      <vbox>
                          <radiogroup id="reg_type1" orient="horizontal" >
                              <radio label="Full" value='FULL' selected="true" />
                              <radio label="Smoke" value='SMOKE' />
                              <radio label="DSP" value='DSP' />
                              <radio label="NEXUNIT" value='NEXUNIT' />
                              <radio label="NEGATIVE" value='NEGATIVE' />
                          </radiogroup>
                      </vbox>
                    </groupbox>
                  </row>
                  <row>
                    <label></label>
                    <groupbox>
                      <caption class="header" label="Suite Type:" style="margin-top:5px;margin-bottom:5px;"/>
                      <vbox>
                          <radiogroup id="reg_testsuite_bucket" orient="horizontal" >
                              <radio label="NonSCM" value='NONSCM' selected="true" />
                              <radio label="SCM" value='SCM' />
                          </radiogroup>
                      </vbox>
                    </groupbox>
                  </row>
                  <row>
                    <label></label>
                    <groupbox>
                      <caption class="header" label="Testbed Env:" style="margin-top:5px;margin-bottom:5px;"/>
                      <vbox>
                          <radiogroup id="reg_testbed_env" orient="horizontal" >
                              <radio label="Real" value='Real' selected="true" />
                              <radio label="Virtual" value='Virtual' />
                              <radio label="Any" value='Any' />
                          </radiogroup>
                      </vbox>
                    </groupbox>
                  </row>
                  <row>
                    <label></label>
                    <groupbox>
                        <caption class="header" label="Supporting DUT Types:" style="margin-top:5px;margin-bottom:5px;"/>
                        <vbox>
                            <row>
                                <checkbox id="D-SBC" label="D-SBC" checked="false" />
                                <checkbox id="V-SBC" label="V-SBC" checked="false" />
                                <checkbox id="G9" label="G9" checked="false" />
                                <checkbox id="I-SBC" label="I-SBC" checked="true" />
                            </row>
                        </vbox>
                    </groupbox>
                  </row>
                  <row>
                    <label></label>
                    <groupbox>
                        <caption class="header" label="Supporting CLI:" style="margin-top:5px;margin-bottom:5px;"/>
                        <vbox>
                            <row>
                                <checkbox id="ConfD" label="ConfD" checked="false" />
                                <checkbox id="Shell" label="Shell" checked="true" />
                            </row>
                        </vbox>
                    </groupbox>
                  </row>
                  <row>
                    <spacer/>
                    <hbox flex="0" pack="left">
                    <button id="clear2" label=" CLEAR " image="/imgs/icons/set1/clear.gif" oncommand="clearTestSuiteInputs();"/>
                    <button id="submit1" label=" CREATE/UPDATE " image="/imgs/icons/setpool/saveall_edit.gif" oncommand="addTestSuite();"/>
                    </hbox>
                  </row>
                  <row hidden="true">
                        <textbox id="popuptype" value="" width="100"/>             
                  </row>
                  <row hidden="true">
                        <textbox id="dutid" value="" width="100"/>             
                  </row>
                  <row hidden="true">
                        <label control="suiteuid">Suite UID:</label>
                        <textbox id="suiteuid" value="" width="100"/>       
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
                        <label control="import_dut_menulist">Type</label>
                        <menulist id="import_dut_menulist" flex="1" oncommand="UserInterface.loadMenuItems('import_nextest',
							    {'obj':'Testbedtopology',
							    'method':'loadTemplates_xml',
							    'query':'q51',
							    'selectStr':'Select Test Bed',
                                'whereStmt':' where testbedelement_objtype_uuid = \'95099839-051b-11e3-a40d-00155d361715\''
							    }, null);">
                            <menupopup>
                                <menuitem label="--SELECT DUT --" value=""/>
                            </menupopup>
                        </menulist>
                  </row>
                  <row>
                    <label control="import_release">Supporting Releases</label>
                    <textbox id="import_release" value="" placeholder="V8.1.1,V8.2.0,V8.X.X"/>
                    <button  class="createBtn" label=" Releases  " image="/imgs/icons/set1/add.gif" onclick="document.getElementById('popuptype').value='release';document.getElementById('dutid').value='import_dut_menulist';UserInterface.openDialogBox('/overlay/loadTest_dialog.xul','popupWindow',400,500)"/>
                  </row>
                  <row>
                    <label control="import_platform">Supporting Platforms</label>
                    <textbox id="import_platform" value="" placeholder="Annapolis,Sandybridge-1U"/>
                    <button  class="createBtn" label=" Platforms" image="/imgs/icons/set1/add.gif" onclick="document.getElementById('popuptype').value='platform';document.getElementById('dutid').value='import_dut_menulist';UserInterface.openDialogBox('/overlay/loadTest_dialog.xul','popupWindow',400,500)"/>
                  </row>
                  <row>
                    <label control="import_requireddevices">Required Devices</label>
                    <textbox id="import_requireddevices" value="" placeholder="SBC_HA,SBC,NXTEST"/>
                    <button  class="createBtn" label=" Devices   " image="/imgs/icons/set1/add.gif" onclick="document.getElementById('popuptype').value='requireddevices';document.getElementById('dutid').value='import_dut_menulist';UserInterface.openDialogBox('/overlay/loadTest_dialog.xul','popupWindow',400,500)"/>
                  </row>
                  <row>
                    <label control="import_suitepath">Feature Path</label>
                    <textbox id="import_suitepath" value="" placeholder="smoke.qms/abc.qms"/>
                  </row>
                  <row>
                    <label></label>
                    <groupbox>
                      <caption class="header" label="Regression Type:" style="margin-top:5px;margin-bottom:5px;"/>
                      <vbox>
                          <radiogroup id="import_type" orient="horizontal" >
                              <radio label="Full" value='FULL' selected="true" />
                              <radio label="Smoke" value='SMOKE' />
                              <radio label="DSP" value='DSP' />
                              <radio label="NEXUNIT" value='NEXUNIT' />
                              <radio label="NEGATIVE" value='NEGATIVE' />
                          </radiogroup>
                      </vbox>
                    </groupbox>
                  </row>
                  <row>
                    <label></label>
                    <groupbox>
                      <caption class="header" label="Suite Type:" style="margin-top:5px;margin-bottom:5px;"/>
                      <vbox>
                          <radiogroup id="import_testsuite_bucket" orient="horizontal" >
                              <radio label="NonSCM" value='NONSCM' selected="true" />
                              <radio label="SCM" value='SCM' />
                          </radiogroup>
                      </vbox>
                    </groupbox>
                  </row>
                  <row>
                    <label></label>
                    <groupbox>
                      <caption class="header" label="Testbed Env:" style="margin-top:5px;margin-bottom:5px;"/>
                      <vbox>
                          <radiogroup id="import_testbed_env" orient="horizontal" >
                              <radio label="Real" value='Real' selected="true" />
                              <radio label="Virtual" value='Virtual' />
                              <radio label="Any" value='Any' />
                          </radiogroup>
                      </vbox>
                    </groupbox>
                  </row>
                  <row>
                        <label control="import_nextest">NxTest Alias</label>
                        <menulist id="import_nextest" flex="1">
                            <menupopup>
                                <menuitem label="--SELECT Test Bed --" value=""/>
                            </menupopup>
                        </menulist>
                  </row>
                  <row>
                    <spacer/>
                    <hbox flex="0" pack="left">
                    <button id="clear2" label=" CLEAR " image="/imgs/icons/set1/clear.gif" oncommand="clearImportForm();"/>
                    <button id="submit1" label=" IMPORT " image="/imgs/icons/setpool/saveall_edit.gif" oncommand="importTestSuites();"/>
                    </hbox>
                  </row>
                  <row hidden="true">
                        <textbox id="popuptype" value="" width="100"/>             
                  </row>
                  <row hidden="true">
                        <textbox id="dutid" value="" width="100"/>             
                  </row>
                </rows>                                 
              </grid>
          </vbox>
        </tabpanel>
        <tabpanel flex="1">
            <vbox flex="10">    
                <groupbox flex="1">
                    <caption label="Regression Suites" class="header" style="margin-top:5px;margin-bottom:5px;" />
                    <toolbox>
                        <toolbar id="objTree_regtests_toolbar">
                            <menulist style="min-width:100px;" id="objTree_regtests_filter" oncommand="">
                                <menupopup>
                                    <menuitem label="Suite Name" value="regressionsuite_name"/>
                                    <menuitem label="Regression Type" value="regressionsuite_type"/>
                                    <menuitem label="Env" value="regressionsuite_env"/>
                                    <menuitem label="Type" value="regressionsuite_bucket"/>
                                    <menuitem label="Release" value="regressionsuite_releases"/>
                                </menupopup>
                            </menulist>
                            <radiogroup id="objTree_regtests_selection" orient="horizontal" oncommand="" flex="1">
                                <radio label="Matches" value='REGEXP' selected="true" />
                                <radio label="Does Not Match" value='NOT REGEXP' />
                            </radiogroup>
                            <textbox id="objTree_regtests_selection_filter" value=".*" 
                                                     size="30" maxlength="1024" flex="1" persist="value"
                                                     oncommand="refreshTestSuiteTree();" type="search" searchbutton="true" emptytext=".*" timeout="0" onfocus="select();"/>
                            
                        </toolbar>
                        <toolbar>
                            <box align="left" flex="1">
                              <label control="counter9" value="Suite Count: "/>
                              <label id="counter9" observes="objTree_regtests_count"/>
                            </box>
                            <spacer flex="1" />
                            <toolbarbutton id="objTree_regtests_refreshBtn" class="refreshBtn2" label="REFRESH" image="/imgs/icons/set1/refresh.gif" oncommand="refreshTestSuiteTree();"/>
                        </toolbar>
                    </toolbox>
                    <tree id="objTree_regtests" flex="1" hidecolumnpicker="false" seltype="multiple" class="tree" sortDirection="descending" enableColumnDrag="true"  sortResource="label1" query="q63" context="objTree_regsuite_popup" >
                        <treecols id="objTree_regtests">
                            <treecol id="objTree_regtests_label1" primary="true" flex="1" label="Test Suite Name" 
                                    persist="width ordinal hidden" ordinal="1" sortable="true" class="sortDirectionIndicator" onclick="refreshTestSuiteTree(this.id.replace('objTree_regtests_',''))"/>
                            <splitter class="tree-splitter" ordinal="2"/>
                            <treecol id="objTree_regtests_label2" flex="0" label="DUT"
                                    persist="width ordinal hidden" ordinal="3" sortable="true" class="sortDirectionIndicator" onclick="refreshTestSuiteTree(this.id.replace('objTree_regtests_',''))"/>
                            <splitter class="tree-splitter" ordinal="4"/>
                            <treecol id="objTree_regtests_label3" flex="1" label="Releases" hidden="true"
                                    persist="width ordinal hidden" ordinal="5" sortable="true" class="sortDirectionIndicator" onclick="refreshTestSuiteTree(this.id.replace('objTree_regtests_',''))"/>
                            <splitter class="tree-splitter" ordinal="6"/>                        
                            <treecol id="objTree_regtests_label4" flex="1" label="Platforms" hidden="true"
                                    persist="width ordinal hidden" ordinal="7" sortable="true" class="sortDirectionIndicator" onclick="refreshTestSuiteTree(this.id.replace('objTree_regtests_',''))"/>
                            <splitter class="tree-splitter" ordinal="8"/>
                            <treecol id="objTree_regtests_label5" flex="1" label="Devices" hidden="true"
                                    persist="width ordinal hidden" ordinal="9" sortable="true" class="sortDirectionIndicator" onclick="refreshTestSuiteTree(this.id.replace('objTree_regtests_',''))"/>
                            <splitter class="tree-splitter" ordinal="10"/>
                            <treecol id="objTree_regtests_label6" flex="1" label="Test(s)"  
                                    persist="width ordinal hidden" ordinal="11" sortable="true" class="sortDirectionIndicator" onclick="refreshTestSuiteTree(this.id.replace('objTree_regtests_',''))"/>
                            <splitter class="tree-splitter" ordinal="14"/>
                            <treecol id="objTree_regtests_label7" flex="1" label="Rgression Type" 
                                    persist="width ordinal hidden" ordinal="15" sortable="true" class="sortDirectionIndicator" onclick="refreshTestSuiteTree(this.id.replace('objTree_regtests_',''))"/>
                            <splitter class="tree-splitter" ordinal="12"/>
                            <treecol id="objTree_regtests_label8" flex="1" label="Description" hidden="true" 
                                    persist="width ordinal hidden" ordinal="13" sortable="true" class="sortDirectionIndicator" onclick="refreshTestSuiteTree(this.id.replace('objTree_regtests_',''))"/>
                            <splitter class="tree-splitter" ordinal="16"/>
                            <treecol id="objTree_regtests_label9" flex="1" label="Path" hidden="true" 
                                    persist="width ordinal hidden" ordinal="17" sortable="true" class="sortDirectionIndicator" onclick="refreshTestSuiteTree(this.id.replace('objTree_regtests_',''))"/>
                            <splitter class="tree-splitter" ordinal="18"/>
                            <treecol id="objTree_regtests_label10" flex="1" label="Type" hidden="true" 
                                    persist="width ordinal hidden" ordinal="18" sortable="true" class="sortDirectionIndicator" onclick="refreshTestSuiteTree(this.id.replace('objTree_regtests_',''))"/>
                            <splitter class="tree-splitter" ordinal="20"/>
                            <treecol id="objTree_regtests_label11" flex="1" label="Env"  
                                    persist="width ordinal hidden" ordinal="19" sortable="true" class="sortDirectionIndicator" onclick="refreshTestSuiteTree(this.id.replace('objTree_regtests_',''))"/>
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
                <tab id="regdaemonstatus" label="Job Status" flex="1" onclick=" document.getElementById('jobqueue_refreshBtn').click();document.getElementById('testbedtopology_refreshBtn').click()"/>
                <tab id="regressionresults" label="Regression Results" flex="1" onclick="if(document.getElementById('objTree_jobqueue_results').getElementsByTagName('treeitem').length == 0) document.getElementById('jobqueue_results_refreshBtn').click();"/>
            </tabs>
            <tabpanels flex="1">
                <tabpanel flex="1">
                  
                  <vbox flex="1">
                    <vbox flex="0">
                      <hbox flex="1">
                      <groupbox id="groupbox2"  flex="1">
                        <caption label="GENSMART REGRESSION SCHEDULER" class="header" style="margin-top:5px;margin-bottom:5px;"/>
                          <toolbox>
                          <toolbar id="remote_log_textbox_toolbar">
                            <toolbarbutton id="remote_log_toolbarbutton" class="log_refreshBtn" label="REFRESH" image="/imgs/icons/set1/refresh.gif" oncommand="viewLog(0);" tooltiptext="Click to refresh remote log"/>
                            <toolbarseparator />
                            <toolbarbutton id="tail_log_toolbarbutton" class="refreshBtn5" label="Tail Automation Log" image="/imgs/icons/set1/refresh.gif"  oncommand="viewLog(1);" tooltiptext="Click to refresh remote log"/>                 
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
                          <tree id="objTree_testbedtopology" flex="1" hidecolumnpicker="false" class="tree" seltype="single" sortDirection="descending" enableColumnDrag="true"  sortResource="label1" query="o257"  onselect="" context="objTree_regtestbedtopology_popup">
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
                      <groupbox id="groupbox3"  flex="1">
                        <caption label="JOB QUEUE STATUS" class="header" style="margin-top:5px;margin-bottom:5px;"/>
                          <toolbox>
                          <toolbar id="jobqueue_toolbar">
                            <toolbarbutton id="jobqueue_refreshBtn" class="refreshBtn0" label="REFRESH" image="/imgs/icons/set1/refresh.gif" oncommand="refreshQueue(1,'objTree_jobqueue');"/>
                            <spacer flex="1" />
                            <checkbox id="autoRefresh" label="Auto Refresh" checked="false" disabled="true" tooltiptext="set an auto refresh flag for every 10 seconds" oncommand="(this.checked)?autoRefresh(1):autoRefresh(0);"/>
                        <toolbarseparator />			    
                            <box align="center" flex="0">
                              <label control="counter10" value="Object Cnt: "/>
                              <label id="counter10" observes="objTree_jobqueue_count"/>
                            </box>
                          </toolbar>
                          </toolbox>
                          <tree id="objTree_jobqueue" flex="1" hidecolumnpicker="false" class="tree" seltype="single" sortDirection="descending" enableColumnDrag="true"  sortResource="label1" query="q64" context="objTree_jobqueue_popup" onselect="if(this.view.selection.count == 1){document.getElementById('remote_log_toolbarbutton').click();if(document.getElementById('autoRefresh').disabled) document.getElementById('autoRefresh').disabled=false;}" ondblclick="if(document.getElementById( 'counter10').value > 0)  UserInterface.refreshTreeView('objTree_suitesqueue',
                            {'where': 'regressionjobtests_suiteuuid not in (select suite_uuid from regressionsuiteresult where  regressionjob_uuid = regressionjobtests_jobuuid ) and regressionjobtests.regressionjobtests_jobuuid =\'' + UserInterface.retrieveLBSelectedListAttrib('id','objTree_jobqueue') + '\''
                            },null);">
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
                                <splitter class="tree-splitter" ordinal="12"/>
                                <treecol id="objTree_jobqueue_label6" flex="1" label="log"  hidden="true"
                                         persist="width ordinal hidden" ordinal="13" sortable="true" class="sortDirectionIndicator" />
                                <splitter class="tree-splitter" ordinal="14"/>
                                <treecol id="objTree_jobqueue_label7" flex="1" label="Time"
                                         persist="width ordinal hidden" ordinal="15" sortable="true" class="sortDirectionIndicator" />
                             <splitter class="tree-splitter" ordinal="16"/>
                                <treecol id="objTree_jobqueue_label8" flex="1" label="Topology"
                                         persist="width ordinal hidden" ordinal="17" sortable="true" class="sortDirectionIndicator" />
                            </treecols>
                          </tree>
                       </groupbox>
                      </hbox>
                    </vbox>
                    <splitter resizeafter="flex" />
                    <vbox flex="1">
                      <hbox flex="1">
                      <groupbox id="groupbox4"  flex="1">
                        <caption label="Test Suites in Queue" class="header" style="margin-top:5px;margin-bottom:5px;"/>
                          <toolbox>
                          <toolbar id="suitesqueue_toolbar">		    
                            <box align="center" flex="0">
                              <label control="counter11" value="Suites Cnt: "/>
                              <label id="counter11" observes="objTree_suitesqueue_count"/>
                            </box>
                          </toolbar>
                          </toolbox>
                          <tree id="objTree_suitesqueue" flex="1" hidecolumnpicker="false" class="tree" seltype="single" sortDirection="descending" enableColumnDrag="true"  sortResource="label1" query="q64a">
                            <treecols id="objTree_jobqueue_treeCols">
                                <treecol id="objTree_suitesqueue_label1" flex="1" label="Suite Name" 
                                         persist="width ordinal hidden" ordinal="1" class="sortDirectionIndicator"/>
                                <splitter class="tree-splitter" ordinal="2"/>
                                <treecol id="objTree_suitesqueue_label2" flex="1" label="# Tests"
                                         persist="width ordinal hidden" ordinal="3" sortable="true" class="sortDirectionIndicator" />
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
                                            <menuitem label="Job Name" value="regressionjob_name"/>
                                            <menuitem label="Author" value="regressionjob_username"/>
                                            <menuitem label="Start Time" value="StartTime"/>
                                            <menuitem label="End Time" value="EndTime"/>
                                            <menuitem label="Release" value="regressionjob_release"/>
                                            <menuitem label="Build" value="regressionjob_build"/>
                                        </menupopup>
                                    </menulist>
     
                                    <radiogroup id="radiogroup_jobresults" orient="horizontal" oncommand="">
                                        <radio label="Matches" value='REGEXP' selected="true"/>
                                        <radio label="Does NOT match" value='NOT REGEXP'/>
                                    </radiogroup>
                                    <textbox id="objTextbox_jobqueue_results_filter" value=".*" 
                                         size="30" maxlength="1024" flex="1" persist="value"
                                         oncommand="document.getElementById('jobqueue_results_refreshBtn').click();" type="search" searchbutton="true" emptytext=".*" timeout="0" onfocus="select();"/>
                                    <toolbarbutton id="jobqueue_results_refreshBtn" class="refreshBtn6" label="REFRESH" image="/imgs/icons/set1/refresh.gif" oncommand="refreshJobResults();"/>
                                    <spacer flex="1" />
                                    <toolbarseparator />
                                    <box align="center" flex="0">
                                        <label control="counter12" value="Object Cnt: "/>
                                        <label id="counter12" observes="objTree_jobqueue_results_count"/>
                                    </box>
                                </toolbar>
                            </toolbox>
                            <tree id="objTree_jobqueue_results" flex="1" hidecolumnpicker="false" seltype="multiple" class="tree" sortDirection="descending" enableColumnDrag="true"  sortResource="label1" query="q65" context="objList_regjobresult_popup"   ondblclick="if(this.view.selection.count == 1){ UserInterface.clearTreeView('objTree_jobqueue_suite_results');UserInterface.clearTreeView('objTree_jobqueue_notrunsuite_results');UserInterface.refreshTreeView('objTree_jobqueue_suite_results',
                                       {'where': 'regressionsuiteresult.regressionjob_uuid = \'' + UserInterface.retrieveLBSelectedListAttrib('id','objTree_jobqueue_results') + '\''
                                       },null);if (UserInterface.retrieveLBSelectedListAttrib('label','objTree_jobqueue_results',9) == 1 )   { document.getElementById('notrunsuite_results').style.display=''; UserInterface.refreshTreeView('objTree_jobqueue_notrunsuite_results',
                            {'where': 'regressionjobtests_suiteuuid not in (select suite_uuid from regressionsuiteresult where  regressionjob_uuid = regressionjobtests_jobuuid ) and regressionjobtests.regressionjobtests_jobuuid =\'' + UserInterface.retrieveLBSelectedListAttrib('id','objTree_jobqueue_results') + '\''
                            },null);} else { document.getElementById('notrunsuite_results').style.display='none';}}">
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
                                    <treecol id="objTree_jobqueue_results_label6" flex="0" label="# TC Not Pass"
                                            persist="width ordinal hidden" ordinal="11" sortable="true" class="sortDirectionIndicator" onclick="refreshJobResults(this.id.replace('objTree_jobqueue_results_',''))"/>
                                    <splitter class="tree-splitter" ordinal="12"/>
                                    <treecol id="objTree_jobqueue_results_label7" flex="1" label="# TC Pass" 
                                            persist="width ordinal hidden" ordinal="13" sortable="true" class="sortDirectionIndicator" onclick="refreshJobResults(this.id.replace('objTree_jobqueue_results_',''))"/>
                                    <splitter class="tree-splitter" ordinal="14"/>
                                    <treecol id="objTree_jobqueue_results_label8" flex="1" label="End Time" hidden="true"
                                            persist="width ordinal hidden" ordinal="15" sortable="true" class="sortDirectionIndicator" onclick="refreshJobResults(this.id.replace('objTree_jobqueue_results_',''))"/>
                                    <splitter class="tree-splitter" ordinal="16"/>
                                    <treecol id="objTree_jobqueue_results_label9" flex="1" label="Topology" 
                                            persist="width ordinal hidden" ordinal="17" sortable="true" class="sortDirectionIndicator" onclick="refreshJobResults(this.id.replace('objTree_jobqueue_results_',''))"/>
                                    <splitter class="tree-splitter" ordinal="18"/>
                                    <treecol id="objTree_jobqueue_results_label10" flex="1" label="Cancelled" hidden="true" 
                                            persist="width ordinal hidden" ordinal="19" sortable="true" class="sortDirectionIndicator" onclick="refreshJobResults(this.id.replace('objTree_jobqueue_results_',''))"/>
                                    <splitter class="tree-splitter" ordinal="20"/>
                                    <treecol id="objTree_jobqueue_results_label11" flex="1" label="Log Path" hidden="true" 
                                            persist="width ordinal hidden" ordinal="21" sortable="true" class="sortDirectionIndicator" onclick="refreshJobResults(this.id.replace('objTree_jobqueue_results_',''))"/>
                                    <splitter class="tree-splitter" ordinal="22"/>
                                    <treecol id="objTree_jobqueue_results_label12" flex="1" label="Re-Executed" hidden="true" style="display:none"
                                            persist="width ordinal hidden" ordinal="23" sortable="true" class="sortDirectionIndicator" onclick="refreshJobResults(this.id.replace('objTree_jobqueue_results_',''))"/>
                                </treecols>
                            </tree>
                        </vbox>
                        <hbox>
                            <label value="Suites Executed" class="header" style="margin:2px 5px 2px 5px;"/>
                        </hbox>
                        <vbox flex="1">
                            <tree id="objTree_jobqueue_suite_results" flex="1" hidecolumnpicker="false" seltype="multiple" class="tree" sortDirection="descending" enableColumnDrag="true"  sortResource="label1" query="q66" context="objList_regjobresultsuite_popup">
                                <treecols id="objTree_jobsuite_results_treeCols">
                                    <treecol id="objTree_jobqueue_suite_results_label1" primary="true" flex="1" label="Start Time" 
                                            persist="width ordinal hidden" ordinal="1" />
                                    <splitter class="tree-splitter" ordinal="2"/>
                                    <treecol id="objTree_jobqueue_suite_results_label2" flex="1" label="Suite Name" 
                                            persist="width ordinal hidden" ordinal="3" />
                                    <splitter class="tree-splitter" ordinal="4"/>
                                    <treecol id="objTree_jobqueue_suite_results_label3" flex="1" label="# TC Fail"
                                            persist="width ordinal hidden" ordinal="5" />
                                    <splitter class="tree-splitter" ordinal="6"/>
                                    <treecol id="objTree_jobqueue_suite_results_label4" flex="1" label="# TC Pass" 
                                            persist="width ordinal hidden" ordinal="7" />
                                    <splitter class="tree-splitter" ordinal="8"/>
                                    <treecol id="objTree_jobqueue_suite_results_label5" flex="1" label="# TC Error"
                                            persist="width ordinal hidden" ordinal="9" />
                                    <splitter class="tree-splitter" ordinal="10"/>
                                    <treecol id="objTree_jobqueue_suite_results_label6" flex="1" label="# TC UnTest"
                                            persist="width ordinal hidden" ordinal="11" />
                                    <splitter class="tree-splitter" ordinal="12"/>
                                    <treecol id="objTree_jobqueue_suite_results_label7" flex="1" label="End Time" 
                                            persist="width ordinal hidden" ordinal="13" />
                                </treecols>
                            </tree>
                        </vbox>                        
                        <vbox flex="1"  id="notrunsuite_results" style="display:none;">
                        <hbox>
                            <label value="Suites Not Executed" class="header" style="margin:2px 5px 2px 5px;"/>
                        </hbox>
                            <tree id="objTree_jobqueue_notrunsuite_results" flex="1" hidecolumnpicker="false" seltype="multiple" class="tree" sortDirection="descending" enableColumnDrag="true"  sortResource="label1" query="q64a" >
                                <treecols id="objTree_jobqueue_notrunsuite_results_treeCols">
                                    <treecol id="objTree_jobqueue_notrunsuite_results_label1" primary="true" flex="1" label="Suite Name" 
                                            persist="width ordinal hidden" ordinal="1" />
                                    <splitter class="tree-splitter" ordinal="2"/>
                                    <treecol id="objTree_jobqueue_notrunsuite_results_label2" flex="1" label="# Tests" 
                                            persist="width ordinal hidden" ordinal="3" />
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
