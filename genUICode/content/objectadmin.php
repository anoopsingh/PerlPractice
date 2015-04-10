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
  <broadcaster id="serverObject" value="null" label="null"/>
  <broadcaster id="objList_group_count" value="0"/>
  <broadcaster id="objList_type_count" value="0"/>
  <broadcaster id="objList_attribute_count" value="0"/>
</broadcasterset>
<popupset>
    <menupopup id="objList_type_popup">
        <menuitem class="menuitem-iconic" image="/imgs/icons/set1/edit.gif" label="Edit Object" oncommand="UserInterface.requestContent('/content/fe.php',{'obj': 'Objtype','method':'edit_obj','uuid':document.popupNode.getAttribute('id'),'return':'form'});" />
        <menuitem class="menuitem-iconic" image="/imgs/icons/set1/clone.gif" label="Clone Object" oncommand="UserInterface.requestContent('/content/fe.php',{'obj': 'Objtype','method':'clone_obj','uuid':document.popupNode.getAttribute('id'),'return':'form'});" />
	<menuseparator/>
        <menu id="status-menu" label="Object Status">
            <menupopup id="status-popup">
              <menuitem class="menuitem-iconic" image="/imgs/icons/set1/ok2.gif" label="Flag Selected Items as Active" oncommand="UserInterface.requestAction(null,'/content/so.php',{'obj': 'Objtype','method':'setStatus','uuid':UserInterface.retrieveLBSelectedListAttrib('id','objList_type'),'status':'A'}, null, null);"/>
              <menuitem class="menuitem-iconic" image="/imgs/icons/set1/retire.gif" label="Flag Selected Items as Retired" oncommand="UserInterface.requestAction(null,'/content/so.php',{'obj': 'Objtype','method':'setStatus','uuid':UserInterface.retrieveLBSelectedListAttrib('id','objList_type'),'status':'R'}, null, null);"/>
            </menupopup>
        </menu>
        <menuseparator/>
        <menuitem class="menuitem-iconic" image="/imgs/icons/set1/delete.gif" label="Delete Object" tooltiptext="Objects have restrictive constraints on them - heirarchal delete is not possible" oncommand="UserInterface.requestAction(null,'/content/so.php',{'obj': 'Objtype','method':'delete_obj','uuid':document.popupNode.getAttribute('id')},'refreshBtn1', null, null);"/>
    </menupopup>
    <menupopup id="objList_group_popup">
        <menuitem class="menuitem-iconic" image="/imgs/icons/set1/edit.gif" label="Edit Object" oncommand="UserInterface.requestContent('/content/fe.php',{'obj': 'Objgrp','method':'edit_obj','uuid':document.popupNode.getAttribute('id'),'return':'form'});" />
        <menuitem class="menuitem-iconic" image="/imgs/icons/set1/clone.gif" label="Clone Object" oncommand="UserInterface.requestContent('/content/fe.php',{'obj': 'Objgrp','method':'clone_obj','uuid':document.popupNode.getAttribute('id'),'return':'form'});" />
	<menuseparator/>
        <menu id="status-menu" label="Object Status">
            <menupopup id="status-popup">
              <menuitem class="menuitem-iconic" image="/imgs/icons/set1/ok2.gif" label="Flag Selected Items as Active" oncommand="UserInterface.requestAction(null,'/content/so.php',{'obj': 'Objgrp','method':'setStatus','uuid':UserInterface.retrieveLBSelectedListAttrib('id','objList_group'),'status':'A'}, null, null);"/>
              <menuitem class="menuitem-iconic" image="/imgs/icons/set1/retire.gif" label="Flag Selected Items as Retired" oncommand="UserInterface.requestAction(null,'/content/so.php',{'obj': 'Objgrp','method':'setStatus','uuid':UserInterface.retrieveLBSelectedListAttrib('id','objList_group'),'status':'R'}, null, null);"/>
            </menupopup>
        </menu>
        <menuseparator/>
        <menuitem class="menuitem-iconic" image="/imgs/icons/set1/delete.gif" label="Delete Object" tooltiptext="Objects have restrictive constraints on them - heirarchal delete is not possible" oncommand="UserInterface.requestAction(null,'/content/so.php',{'obj': 'Objgrp','method':'delete_obj','uuid':document.popupNode.getAttribute('id')},'refreshBtn2', null);"/>
    </menupopup>
    <menupopup id="objList_attribute_popup">
        <menuitem class="menuitem-iconic" image="/imgs/icons/set1/edit.gif" label="Edit Object" oncommand="UserInterface.requestContent('/content/fe.php',{'obj': 'Objattr','method':'edit_obj','uuid':document.popupNode.getAttribute('id'),'return':'form'});" />
        <menuitem class="menuitem-iconic" image="/imgs/icons/set1/clone.gif" label="Clone Object" oncommand="UserInterface.requestContent('/content/fe.php',{'obj': 'Objattr','method':'clone_obj','uuid':document.popupNode.getAttribute('id'),'return':'form'});" />
	<menuseparator/>
        <menu id="status-menu" label="Object Status">
            <menupopup id="status-popup">
              <menuitem class="menuitem-iconic" image="/imgs/icons/set1/ok2.gif" label="Flag Selected Items as Active" oncommand="UserInterface.requestAction(null,'/content/so.php',{'obj': 'Objattr','method':'setStatus','uuid':UserInterface.retrieveLBSelectedListAttrib('id','objList_attribute'),'status':'A'}, null, null);"/>
              <menuitem class="menuitem-iconic" image="/imgs/icons/set1/retire.gif" label="Flag Selected Items as Retired" oncommand="UserInterface.requestAction(null,'/content/so.php',{'obj': 'Objattr','method':'setStatus','uuid':UserInterface.retrieveLBSelectedListAttrib('id','objList_attribute'),'status':'R'}, null, null);"/>
            </menupopup>
        </menu>
        <menuseparator/>
        <menuitem class="menuitem-iconic" image="/imgs/icons/set1/delete.gif" label="Delete Object" tooltiptext="Objects have restrictive constraints on them - heirarchal delete is not possible" oncommand="UserInterface.requestAction(null,'/content/so.php',{'obj': 'Objattr','method':'delete_obj','uuid':document.popupNode.getAttribute('id')},'refreshBtn3', null, null);"/>

    </menupopup>
</popupset>
<vbox flex="2">
    <vbox flex="1">
        <hbox>
            <label  class="header" value="Object Type" style="margin-top:5px;margin-bottom:5px;"/>
        </hbox>
        <toolbox>
            <toolbar id="nav-toolbar">
              <toolbarbutton id="createBtn1" class="createBtn" label="CREATE" image="/imgs/icons/set1/add.gif" oncommand="UserInterface.requestContent('/content/fe.php',{'obj':'Objtype','method':'add_obj'});" tooltiptext="Click to load object type creation interface"/>
              <toolbarseparator />
              <toolbarbutton id="refreshBtn1" class="refreshBtn" label="REFRESH" image="/imgs/icons/set1/refresh.gif"
                               oncommand="UserInterface.refreshQualified('objList_type',
                                                                         {'orderby': UserInterface.getOrderStmt(null,'objList_type', 'label1')},
                                                                         null);" tooltiptext="Click to refresh object type listing"/>
              <toolbarseparator />
              <spacer flex="1" />
              <box align="center" flex="0">
                <label control="counter2" value="Object Cnt: "/>
                <label id="counter2" observes="objList_type_count"/>
              </box>
            </toolbar>
        </toolbox>
        <listbox id="objList_type" class="listobject" query="q9" pup="objList_type_popup" flex="1" sortDirection="ascending" sortResource="label1"
                 ondblclick="UserInterface.requestContent('/content/fe.php',{'obj': 'Objtype','method':'edit_obj','uuid':document.getElementById('objList_type').selectedItems[0].getAttribute('id')});"
                 >
        <listhead>
          <listheader id="objList_type_label1" label="Type Name" flex="1" sortable="true" class="sortDirectionIndicator"
                      onclick="UserInterface.refreshQualified('objList_type',
                                                                         {'orderby': UserInterface.getOrderStmt(this.id.replace('objList_type_', ''),'objList_type', 'label1')},
                                                                         null);"/>
          <listheader id="objList_type_label2" label="Version" flex="0" sortable="true" class="sortDirectionIndicator"
                      onclick="UserInterface.refreshQualified('objList_type',
                                                                         {'orderby': UserInterface.getOrderStmt(this.id.replace('objList_type_', ''),'objList_type', 'label1')},
                                                                         null);"/>
          <listheader id="objList_type_label3" label="Status" flex="0" sortable="true" class="sortDirectionIndicator"
                      onclick="UserInterface.refreshQualified('objList_type',
                                                                         {'orderby': UserInterface.getOrderStmt(this.id.replace('objList_type_', ''),'objList_type', 'label1')},
                                                                         null);"/>
        </listhead>
        <listcols>
          <listcol flex="1"/>
          <splitter style="width: 1px; border:0px; min-width: 0%; background-color: transparent;"/>
          <listcol flex="1"/>
          <splitter style="width: 1px; border:0px; min-width: 0%; background-color: transparent;"/>
          <listcol flex="0"/>
          <splitter style="width: 1px; border:0px; min-width: 0%; background-color: transparent;"/>
        </listcols>
        </listbox>
    </vbox>
    <splitter/>
    <vbox flex="1">
      <hbox>
	  <label  class="header" value="Object Group" style="margin-top:5px;margin-bottom:5px;"/>
      </hbox>
      <toolbox>
	  <toolbar id="nav-toolbar">
	    <toolbarbutton id="createBtn2" class="createBtn" label="CREATE" image="/imgs/icons/set1/add.gif" oncommand="UserInterface.requestContent('/content/fe.php',{'obj':'Objgrp','method':'add_obj'});" tooltiptext="Click to load object group creation interface"/>
	    <toolbarseparator />
	    <toolbarbutton id="refreshBtn2" class="refreshBtn" label="REFRESH" image="/imgs/icons/set1/refresh.gif"
                               oncommand="UserInterface.refreshQualified('objList_group',
                                                                         {'orderby': UserInterface.getOrderStmt(null,'objList_group', 'label1')},
                                                                         null);" tooltiptext="Click to refresh object group listing"/>
	    <toolbarseparator />
	    <spacer flex="1" />
	    <box align="center" flex="0">
	      <label control="counter1" value="Object Cnt: "/>
	      <label id="counter1" observes="objList_group_count"/>
	    </box>
	  </toolbar>
      </toolbox>
      <listbox id="objList_group" class="listobject" seltype="multiple" query="q8" pup="objList_group_popup" flex="1" sortDirection="ascending" sortResource="label1"
               ondblclick="UserInterface.requestContent('/content/fe.php',{'obj': 'Objgrp','method':'edit_obj','uuid':document.getElementById('objList_group').selectedItems[0].getAttribute('id')});"
               >
      <listhead>
	<listheader id="objList_group_label1" label="Group Name" flex="1" sortable="true" class="sortDirectionIndicator"
                      onclick="UserInterface.refreshQualified('objList_group',
                                                                         {'orderby': UserInterface.getOrderStmt(this.id.replace('objList_group_', ''),'objList_group', 'label1')},
                                                                         null);"/>
	<listheader id="objList_group_label2" label="Version" flex="0" sortable="true" class="sortDirectionIndicator"
                      onclick="UserInterface.refreshQualified('objList_group',
                                                                         {'orderby': UserInterface.getOrderStmt(this.id.replace('objList_group_', ''),'objList_group', 'label1')},
                                                                         null);"/>
	<listheader id="objList_group_label3" label="Status" flex="0" sortable="true" class="sortDirectionIndicator"
                      onclick="UserInterface.refreshQualified('objList_group',
                                                                         {'orderby': UserInterface.getOrderStmt(this.id.replace('objList_group_', ''),'objList_group', 'label1')},
                                                                         null);"/>
      </listhead>
      <listcols>
	<listcol flex="1"/>
        <splitter style="width: 1px; border:0px; min-width: 0%; background-color: transparent;"/>
	<listcol flex="1"/>
        <splitter style="width: 1px; border:0px; min-width: 0%; background-color: transparent;"/>
        <listcol flex="0"/>
        <splitter style="width: 1px; border:0px; min-width: 0%; background-color: transparent;"/>
      </listcols>
      </listbox>
    </vbox>
    <splitter/>
    <vbox flex="1">
      <hbox>
	  <label  class="header" value="Object Attribute" style="margin-top:5px;margin-bottom:5px;"/>
      </hbox>
      <toolbox>
	  <toolbar id="nav-toolbar">
	    <toolbarbutton id="createBtn3" class="createBtn" label="CREATE" image="/imgs/icons/set1/add.gif" oncommand="UserInterface.requestContent('/content/fe.php',{'obj':'Objattr','method':'add_obj'});" tooltiptext="Click to load object attribute creation interface"/>
	    <toolbarseparator />
	    <toolbarbutton id="refreshBtn3" class="refreshBtn" label="REFRESH" image="/imgs/icons/set1/refresh.gif"
                               oncommand="UserInterface.refreshQualified('objList_attribute',
                                                                         {'orderby': UserInterface.getOrderStmt(null,'objList_attribute', 'label1')},
                                                                         null);" tooltiptext="Click to refresh object attribute listing"/>
	    <toolbarseparator />
	    <spacer flex="1" />
	    <box align="center" flex="0">
	      <label control="counter3" value="Object Cnt: "/>
	      <label id="counter3" observes="objList_attribute_count"/>
	    </box>
	  </toolbar>
      </toolbox>
      <listbox id="objList_attribute" class="listobject" seltype="multiple" query="q10" pup="objList_attribute_popup" flex="1" sortDirection="ascending" sortResource="label1"
               ondblclick="UserInterface.requestContent('/content/fe.php',{'obj': 'Objattr','method':'edit_obj','uuid':document.getElementById('objList_attribute').selectedItems[0].getAttribute('id')});"
               >
      <listhead>
	<listheader id="objList_attribute_label1" label="Attribute" flex="1" sortable="true" class="sortDirectionIndicator"
                      onclick="UserInterface.refreshQualified('objList_attribute',
                                                                         {'orderby': UserInterface.getOrderStmt(this.id.replace('objList_attribute_', ''),'objList_attribute', 'label1')},
                                                                         null);"/>
	<listheader id="objList_attribute_label2" label="Version" flex="0" sortable="true" class="sortDirectionIndicator"
                      onclick="UserInterface.refreshQualified('objList_attribute',
                                                                         {'orderby': UserInterface.getOrderStmt(this.id.replace('objList_attribute_', ''),'objList_attribute', 'label1')},
                                                                         null);"/>
	<listheader id="objList_attribute_label3" label="Status" flex="0" sortable="true" class="sortDirectionIndicator"
                      onclick="UserInterface.refreshQualified('objList_attribute',
                                                                         {'orderby': UserInterface.getOrderStmt(this.id.replace('objList_attribute_', ''),'objList_attribute', 'label1')},
                                                                         null);"/>
      </listhead>
      <listcols>
	<listcol flex="1"/>
        <splitter style="width: 1px; border:0px; min-width: 0%; background-color: transparent;"/>
	<listcol flex="1"/>
        <splitter style="width: 1px; border:0px; min-width: 0%; background-color: transparent;"/>
	<listcol flex="0"/>
        <splitter style="width: 1px; border:0px; min-width: 0%; background-color: transparent;"/>
      </listcols>
      </listbox>
    </vbox>
               
</vbox>
<splitter/>
<vbox flex="6">
    <vbox height="100%" flex="1" >
	<iframe id="mainframe" class="iframe" name="mainframe" flex="1" src="/content/_blank.xul"/>
    </vbox>
</vbox>
</window> 
