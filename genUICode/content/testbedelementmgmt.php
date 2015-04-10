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
<?xul-overlay href="/overlay/objList_testbedelementmgmt_jscript.xul"?>

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
  <broadcaster id="serverObject" value="Nelementattr" label="Nelementattr"/>
  <broadcaster id="objList_group_count" value="0"/>
  <broadcaster id="objList_type_count" value="0"/>
  <broadcaster id="objList_attribute_count" value="0"/>
  <broadcaster id="objList_nelementattr_count" value="0"/>
  <broadcaster id="objList_assesttags_count" value="0"/>
  <broadcaster id="testbed_templates_count" value="0"/>
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
    <menupopup id="objList_type_popup">
        <menuitem class="menuitem-iconic" image="/imgs/icons/set1/clone.gif" label="Clone Element" oncommand="cloneElement(document.popupNode.childNodes[4].getAttribute('label'));" tooltiptext="Enter new alias name for cloned object (Must be unique)"/>     
        <menuseparator/>
        <menuitem class="menuitem-iconic" image="/imgs/icons/set1/user.gif" label="Change Ownership" oncommand="changeOwnership();" tooltiptext="Enter new owner's Login ID for object ownership change" observes="admincollapsed" />   	
        <menuitem id="syncWithMaster" class="menuitem-iconic" image="/imgs/icons/set1/process.gif" label="Sync With Master" collapsed="false" oncommand="UserInterface.requestAction(null,'/content/so.php',{'obj':'TestBedElement','method':'replicate_nelement','uuid':document.popupNode.getAttribute('id'),'master':document.popupNode.childNodes[4].getAttribute('label') }, 'templateRefresh', null);" />  	
        <menuseparator/>
        <menuitem class="menuitem-iconic" image="/imgs/icons/set1/delete.gif" tooltiptext="To delete an element - you must have created it, or have administrative privileges" label="Delete Element" oncommand="if(confirm('Are you sure you want to try and delete this element?\r\n')){UserInterface.requestAction(null,'/content/so.php',{'obj':'TestBedElement','method':'delete_nelement','testbedelement_uuid':document.popupNode.getAttribute('id') }, 'refreshBtn1a', null);}" />
    </menupopup>
    <menupopup id="objList_nelementattr_popup">
        <menuitem class="menuitem-iconic" image="/imgs/icons/set1/edit.gif" label="Edit Attribute" tooltiptext="Reload the attribute parameters into the form above for editing" oncommand="UserInterface.editTestBedElement({'obj':'TestBedElementAttr','method':'retrieveAttribute_xml','testbedelementattr_uuid':document.getElementById('objList_nelementattr').getSelectedItem(0).getAttribute('id'),'user_info':'<?php echo $userInfo['userName']; ?>'});" />
        <menu id="edit-menu" class="menu-iconic" image="/imgs/icons/set1/edit.gif" label="Change Attribute Position">
            <menupopup id="edit-menu-popup">
                <menuitem class="menuitem-iconic" image="/imgs/icons/set1/plus.gif" label="Increase Index" oncommand="UserInterface.requestTemplateAction('objList_template','/content/so.php',{'obj': 'TestBedElementAttr','method':'increase_position','testbedelementattr_uuid':document.popupNode.getAttribute('id')},'templateRefresh');"/>
                <menuitem class="menuitem-iconic" image="/imgs/icons/set1/minus.gif" label="Decrease Index" oncommand="UserInterface.requestTemplateAction('objList_template','/content/so.php',{'obj': 'TestBedElementAttr','method':'decrease_position','testbedelementattr_uuid':document.popupNode.getAttribute('id')},'templateRefresh');"/>
            </menupopup>
        </menu>
	<menuseparator/>
	<menuitem class="menuitem-iconic" image="/imgs/icons/set1/terminatepid.gif" label="Clear Suspect" disabled="true" oncommand="UserInterface.requestAction(null,'/content/so.php',{'obj': 'TestBedElementAttr','method':'setSuspect','uuid':document.popupNode.getAttribute('id'),'suspect':'N'}, 'templateRefresh', null);"/>	
        <menuseparator/>
        <menuitem id="removeAttribute" class="menuitem-iconic" image="/imgs/icons/set1/delete.gif" label="Remove Attribute" oncommand="if(confirm('Are you sure you want to remove the selected attribute?')){UserInterface.requestTemplateAction('objList_template','/content/so.php',{'obj': 'TestBedElementAttr','method':'remove_obj','testbedelementattr_uuid':document.popupNode.getAttribute('id')},'templateRefresh');}" />
    </menupopup>    
</popupset>

<vbox flex="1" style="overflow:auto">
  <hbox>
  <label value="Test Bed Element Management" class="header" style="margin:2px 5px 2px 5px;"/>
  </hbox>
    <vbox flex="1">
        <hbox flex="0">
        <groupbox id="groupbox1" flex="1">
          <caption label="Generation via Standardized Template" class="header" style="margin-top:5px;margin-bottom:5px;" />
          <grid>
            <columns>
              <column flex="1"/>
              <column flex="2"/>
            </columns>
            <rows>
              <row>
                <label value="Test Bed Templates"/>
                <menulist id="testbed_templates" flex="1" oncommand="
		var whereStmt = (UserInterface.mlv(this.id))?'testbedelement_objtype_uuid=\'' + UserInterface.mlv(this.id) + '\'':'testbedelement_objtype_uuid is not null';
		UserInterface.refreshQualified('objList_type',
						{'where':whereStmt,
						 'orderby': UserInterface.getOrderStmt(null,'objList_type', 'label1')},
						null);
		document.getElementById('objList_type').setAttribute('whereStmt',whereStmt);
						">  
                    <menupopup>
                      <menuitem label="--SELECT TEMPLATE --" value=""/>
                    </menupopup>
                </menulist>
              </row>
              <toolbarbutton id="menuListBtn1" collapsed="true" class="refreshBtn" oncommand="UserInterface.loadMenuItems('testbed_templates',{'obj':'TestBedElement','method':'loadTemplates_xml'});UserInterface.loadMenuItems('groupinfo_menulist',{'obj':'TestBedElement','method':'loadTemplates_xml', 'query':'q8a','selectStr':'SELECT GROUP'});UserInterface.loadMenuItems('attributeinfo_menulist',{'obj':'TestBedElement','method':'loadTemplates_xml', 'query':'q10a','selectStr':'SELECT ATTRIBUTE'});"/>
              <row>
                <label control="testbedelement_alias">Test Bed Alias</label>
                <textbox id="testbedelement_alias" value=""/>
              </row>
              <row>
                <spacer/>
                <hbox flex="0" pack="left">
                <button label=" Generate Test Bed Element from Template" image="/imgs/icons/set1/add.gif" oncommand="
		UserInterface.requestTemplateAction('objList_template','/content/so.php',
                    {
                    'obj': 'TestBedElement',
                    'method':'generateElementFromTemplate',
                    'objtype_uuid': UserInterface.mlv('testbed_templates'),
		    'objtype_name': UserInterface.mlv2('testbed_templates'),
                    'testbedelement_alias': document.getElementById('testbedelement_alias').value,
                    },
                    'refreshBtn1a'); 
		"/>
                  </hbox>
              </row>
            </rows>
          </grid>
        </groupbox>
        </hbox>
        <groupbox flex="1">
          <caption label="Test Bed Elements Aliases" class="header" style="margin-top:5px;margin-bottom:5px;" />
          <toolbox>
              <toolbar id="nav-toolbar">
                <textbox id="testbed_element_filter" value=".*"
                         size="30" maxlength="1024" flex="1"
                         oncommand="document.getElementById('refreshBtn1').click();" type="search" searchbutton="true" emptytext=".*" timeout="0" onfocus="select();"/>
                <toolbarbutton id="refreshBtn1" class="refreshBtn2" label="REFRESH" image="/imgs/icons/set1/refresh.gif"
                               oncommand="refreshTestbedElements(null,true);" tooltiptext="Click to refresh object type listing"/>
		<toolbarbutton id="refreshBtn1a" collapsed="true" oncommand="refreshTestbedElements(null);"/>
                <spacer flex="1" />
                <box align="center" flex="0">
                  <label control="counter2" value="Object Cnt: "/>
                  <label id="counter2" observes="objList_type_count"/>
                </box>
              </toolbar>
          </toolbox>
          <listbox id="objList_type" class="listobject" query="q20" pup="objList_type_popup" flex="1" sortDirection="ascending" sortResource="label2" 
                   onselect="UserInterface.setText('objList_type','objtypeinfo', 0);
                   document.getElementById('defaultvalue').value = ''; document.getElementById('obj_description').value = ''; 
                   document.getElementById('templateRefresh').click();
		    if(this.selectedItem.childNodes[4].getAttribute('label') == 1){
		      document.getElementById('syncWithMaster').setAttribute('collapsed','true');
		      if(this.selectedItem.childNodes[2].getAttribute('label').search('<?php echo $userInfo['userName']; ?>') == -1){
			document.getElementById('linkAttribute').setAttribute('disabled','true');
			document.getElementById('edit-menu').setAttribute('disabled','true');
			document.getElementById('removeAttribute').setAttribute('disabled','true');
		      }else{
			document.getElementById('linkAttribute').setAttribute('disabled','false');
			document.getElementById('edit-menu').setAttribute('disabled','false');
			document.getElementById('removeAttribute').setAttribute('disabled','false');
		      }
		    }else{
		      document.getElementById('linkAttribute').setAttribute('disabled','false');
		      document.getElementById('edit-menu').setAttribute('disabled','false');
		      document.getElementById('removeAttribute').setAttribute('disabled','false');		      
		      if(this.selectedItem.childNodes[4].getAttribute('label').length == 0)
			document.getElementById('syncWithMaster').setAttribute('collapsed','true');
		      else{
			document.getElementById('syncWithMaster').setAttribute('label','Sync With ' + this.selectedItem.childNodes[1].getAttribute('label') + '-MASTER element');
			document.getElementById('syncWithMaster').setAttribute('collapsed','false');
		      }
		    }" whereStmt="">
          <listhead>
           <listheader id="objList_type_label1" label="Testbed Alias" flex="1" sortable="true" class="sortDirectionIndicator"
                        onclick="refreshTestbedElements(this.id.replace('objList_type_', ''));"/>
            <listheader id="objList_type_label2" label="Testbed Type" flex="0" sortable="true" class="sortDirectionIndicator"
                        onclick="refreshTestbedElements(this.id.replace('objList_type_', ''));"/>
            <listheader id="objList_type_label5" label="Owner" flex="1" sortable="true" class="sortDirectionIndicator"
                        onclick="refreshTestbedElements(this.id.replace('objList_type_', ''));"/>
            <listheader id="objList_type_label3" label="Version" flex="1" sortable="true" class="sortDirectionIndicator"
                        onclick="refreshTestbedElements(this.id.replace('objList_type_', ''));"/>
            <listheader id="objList_type_label4" label="Master" flex="1" collapsed="true" />	    
          </listhead>
          <listcols>
            <listcol flex="1" />
            <splitter style="width: 1px; border:0px; min-width: 0%; background-color: transparent;"/> 
            <listcol flex="0" />
            <splitter style="width: 1px; border:0px; min-width: 0%; background-color: transparent;"/> 
            <listcol flex="1"/>
            <splitter style="width: 1px; border:0px; min-width: 0%; background-color: transparent;"/> 
            <listcol flex="2"/>
            <splitter style="width: 1px; border:0px; min-width: 0%; background-color: transparent;"/> 
            <listcol flex="0" collapsed="true" />	    
          </listcols>
          </listbox>
          <spacer height="5"/>
        </groupbox>
    </vbox>
</vbox>
<splitter/>
<vbox flex="6" style="margin: 0px 0px 0px 0px;">
    <vbox flex="1">
        <tabbox id="tabs1" flex="1">
            <tabs>
                <tab id="elementmgmt_tab" label="Test Bed Attribute Managment" flex="1"/>
            </tabs>
            <tabpanels flex="1">
                <tabpanel flex="1">
                    <vbox flex="1">
                    <vbox flex="0" style="overflow:auto">
                        <hbox flex="1">
                        <groupbox id="groupbox2"  flex="1">
                          <caption label="Current Selections" class="header" style="margin-top:5px;margin-bottom:5px;"/>
                          <grid>
                            <columns>
                              <column flex="0"/>
                              <column flex="1"/>
                            </columns>
                            <rows>
                              <row>
                                <label control="objtypeinfo">Test Bed Alias</label>
                                <textbox id="objtypeinfo" readonly="true" uuid="" value=""/>
                              </row>
                              <row>
                                <!--<label control="groupinfo">Group Name</label>-->
                                <!--<textbox id="groupinfo" readonly="true" uuid="" value=""/>-->
                                <label control="groupinfo">Group Name</label>
                                <menulist style="min-width:200px;" id="groupinfo_menulist" flex="1"> 
                                  <menupopup>
                                    <menuitem label="-- SELECT GROUP --" value=""/>
                                  </menupopup>
                                </menulist>                                
                              </row>
                              <row>
                                <!--<label control="attributeinfo">Attribute</label>-->
                                <!--<textbox id="attributeinfo" readonly="true" uuid="" value=""/>-->
                                <label control="attributeinfo">Attribute</label>
                                <menulist style="min-width:200px;" id="attributeinfo_menulist" flex="1">  
                                  <menupopup>
                                    <menuitem label="-- SELECT ATTRIBUTE --" value=""/>
                                  </menupopup>
                                </menulist>                                 
                              </row>
                              <row>
                                <label value="Group Index"/>
                                <spinner id="positioninfo" value="1" min="1" max="100" step="1" precision="0" />
                              </row>
                               <row id="defaultvalue_txt">
                                <label control="defaultvalue">Attribute Value</label>
				<stack style="border: 0px solid black;">
				  <textbox id="defaultvalue" value=""/>
				  <resizer dir="bottomright" style="cursor: se-resize;" element="_parent" right="5" bottom="3" width="10" height="10"/>
				</stack>				
                              </row>
                              <row>
                                <label control="obj_description">Attribute Comment</label>
				<stack style="border: 0px solid black;">
				  <textbox id="obj_description" value=""/>
				  <resizer dir="bottomright" style="cursor: se-resize;" element="_parent" right="5" bottom="3" width="10" height="10"/>
				</stack>
                              </row> 			      
                              <row>
                                <spacer/>
                                <hbox flex="0" pack="left">
                                <button id="linkAttribute" label=" Link Group/Attribute to Test Bed Alias" image="/imgs/icons/set1/link.gif" disabled="false" oncommand="UserInterface.requestTemplateAction('objList_nelementattr','/content/so.php',
                                    {
                                    'obj': 'TestBedElementAttr',
                                    'method':'update_obj',
                                    'testbedelementattr_testbedelement_uuid': document.getElementById('objtypeinfo').getAttribute('uuid'),
                                    'testbedelementattr_objgrp_uuid': UserInterface.mlv('groupinfo_menulist'),
                                    'testbedelementattr_objattr_uuid': UserInterface.mlv('attributeinfo_menulist'),
                                    'testbedelementattr_index': document.getElementById('positioninfo').value,
                                    'testbedelementattr_val': document.getElementById('defaultvalue').value,
                                    'testbedelementattr_comment': (document.getElementById('obj_description').value.length > 0)? document.getElementById('obj_description').value : '',
				    'testbedelement_alias': document.getElementById('objtypeinfo').value,
				    'master_element': document.getElementById('objList_type').selectedItem.childNodes[4].getAttribute('label')
                                    },'templateRefresh');"
                                    />
                                  </hbox>
                              </row>
                            </rows>
                          </grid>
                        </groupbox>
                        </hbox>
                    </vbox>
		    <splitter/>
                    <vbox flex="1">
                        <tabbox id="tabs2" flex="1">
                            <tabs>
                                <tab label="Test Bed Attributes- Network Related" flex="1"/>
                            </tabs>
                            <tabpanels flex="1">
                                <tabpanel flex="1" id="networktabpanel">
                                    <vbox flex="1">
                                    <hbox pack="start">
                                        <label class="header" value="Test Bed Attributes" style="margin-top:5px;margin-bottom:5px;"/>
                                      </hbox>
                                      <toolbar id="nav-toolbar">
                                        <toolbarbutton id="templateRefresh" label="REFRESH" image="/imgs/icons/set1/refresh.gif"
                                                       oncommand="refreshTestbedAttributes();" tooltiptext="Click to refresh object attribute listing"/>
                                        <spacer flex="1" />
                                        <box align="center" flex="0">
                                          <label control="counter4" value="Object Cnt: "/>
                                          <label id="counter3" observes="objList_nelementattr_count"/>
                                        </box>
                                      </toolbar>
                                      <listbox id="objList_nelementattr" class="listobject" query="q21" pup="objList_nelementattr_popup" flex="1" sortDirection="ascending" sortResource="label4" ondblclick = "document.getElementById(this.getAttribute('pup')).firstChild.click();">
                                        <listhead>
                                          <listheader id="objList_nelementattr_label1" label="Network Element Instance" flex="1" sortable="true" class="sortDirectionIndicator"
                                                onclick="refreshTestbedAttributes(this.id.replace('objList_nelementattr_', ''));"/>
                                          <listheader id="objList_nelementattr_label2" label="Attribute Group" flex="1" sortable="true" class="sortDirectionIndicator"
                                                onclick="refreshTestbedAttributes(this.id.replace('objList_nelementattr_', ''));"/>
                                          <listheader id="objList_nelementattr_label3" label="Group Index" flex="0" sortable="true" class="sortDirectionIndicator"
                                                onclick="refreshTestbedAttributes(this.id.replace('objList_nelementattr_', ''));"/>
                                          <listheader id="objList_nelementattr_label4" label="Attribute Name" flex="1" sortable="true" class="sortDirectionIndicator"
                                                onclick="refreshTestbedAttributes(this.id.replace('objList_nelementattr_', ''));"/>
                                          <listheader id="objList_nelementattr_label5" label="Attribute Value" flex="1" sortable="true" class="sortDirectionIndicator"
                                                onclick="refreshTestbedAttributes(this.id.replace('objList_nelementattr_', ''));"/>
                                          <listheader id="objList_nelementattr_label6" label="Comment" flex="1" sortable="true" class="sortDirectionIndicator"
                                                onclick="refreshTestbedAttributes(this.id.replace('objList_nelementattr_', ''));"/>
                                        </listhead>
                                        <listcols>
                                          <listcol flex="1"/>
                                          <splitter style="width: 1px; border:0px; min-width: 0%; background-color: transparent;"/> 
                                          <listcol flex="1"/>
                                          <splitter style="width: 1px; border:0px; min-width: 0%; background-color: transparent;"/> 
                                          <listcol flex="0"/>
                                          <splitter style="width: 1px; border:0px; min-width: 0%; background-color: transparent;"/> 
                                          <listcol flex="1"/>
                                          <splitter style="width: 1px; border:0px; min-width: 0%; background-color: transparent;"/> 
                                          <listcol flex="1"/>
                                          <splitter style="width: 1px; border:0px; min-width: 0%; background-color: transparent;"/> 
                                          <listcol flex="1"/>
                                        </listcols>
                                      </listbox>
                                    </vbox>
                                </tabpanel>                                
                            </tabpanels>
                        </tabbox>
                    </vbox>
                    </vbox>
                </tabpanel>
                
            </tabpanels>
        </tabbox>
    </vbox>
</vbox>
</window>

