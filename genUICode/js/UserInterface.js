var UserInterface = new Object();
UserInterface.initflag = false;
UserInterface.debug = false;
UserInterface.msgPool = new Array();
UserInterface.refreshflag = false;
UserInterface.popupWindow = null;
//Object functionality:
UserInterface.init = function() {
  if(!this.initflag){   
    // gather some interface information:
    this.tabbox = document.getElementById('contentTabs');
    //this.tabpanels = YAHOO.util.Dom.getElementsByClassName('tabpanels');
    this.iframe =  document.getElementById('mainframe');
    this.refreshBtns = YAHOO.util.Dom.getElementsByClassName('refreshBtn'); //document.getElementById('refreshBtn');
    this.menuListBtns = YAHOO.util.Dom.getElementsByClassName('menuListBtn'); //document.getElementById('refreshBtn');
    this.createBtns = YAHOO.util.Dom.getElementsByClassName('createBtn');  //document.getElementById('createBtn');
    this.listBoxIDS = YAHOO.util.Dom.getElementsByClassName('listobject');
    this.treeIDS = YAHOO.util.Dom.getElementsByClassName('tree');
    
    
    var btns = this.refreshBtns;
    for( var i=0; i < btns.length; i++ ){
      /* Get the hover div */
      bid = btns[i].getAttribute('id');
      abtn = document.getElementById( bid );
      abtn.click();
    }
    //for(i=0;i<this.refreshBtns.length;i++){
    //  this.refreshBtns[i].click();
    //}
    var mbtns = this.menuListBtns;
    for( var i=0; i < mbtns.length; i++ ){
      /* Get the hover div */
      mbid = mbtns[i].getAttribute('id');
      mabtn = document.getElementById( bid );
      mabtn.click();
    }
    this.initflag = true;
  }
}

UserInterface.setMsgPanel = function(msg){
  try {
    if(parent.parent.document.getElementById('msg-panel')){
      parent.parent.document.getElementById('msg-panel').setAttribute('label', msg.toUpperCase());
    }else if(parent.document.getElementById('msg-panel')){
      parent.document.getElementById('msg-panel').setAttribute('label', msg.toUpperCase());
    }else if(document.getElementById('msg-panel')){
      document.getElementById('msg-panel').setAttribute('label', msg.toUpperCase());
    }
    if(parent.parent.document.getElementById('msg-all')){
      var msgall = parent.parent.document.getElementById('msg-all');
      msgall.setAttribute('value',  msgall.value + msg.toUpperCase() + '\n');
      msgall.inputField.scrollTop = msgall.inputField.scrollHeight
    }else if(parent.document.getElementById('msg-all')){
      var msgall = parent.document.getElementById('msg-all');
      msgall.setAttribute('value', msgall.value + msg.toUpperCase()+ '\n');
      msgall.inputField.scrollTop = msgall.inputField.scrollHeight
    }else if(document.getElementById('msg-all')){
      var msgall = document.getElementById('msg-all');
      msgall.setAttribute('label', msgall.value + msg.toUpperCase()+ '\n');
      msgall.inputField.scrollTop = msgall.inputField.scrollHeight
    }
  }catch(e){
    //alert(e.message);
  }
}
UserInterface.clearMsgPanel = function(){
  UserInterface.setMsgPanel('');
}
// Arguments:
// * listID - data source (listBox) identifier
// * textID - data destination (textBox) identifier
// * index - identifies the element to be displayed; 0 means label1, 1 means label2, etc.
//
// Returns:
// * nothing
//
UserInterface.setText = function(listID,textID,index){
  var listBox = document.getElementById(listID);
  var textBox = document.getElementById(textID);
  var selectedItem = listBox.getSelectedItem(0);
  textBox.setAttribute('uuid', selectedItem.getAttribute('id'));
  textBox.setAttribute('value', selectedItem.childNodes[index].getAttribute('label'));
  return;
}
UserInterface.setTextValue = function(value,textID){
  var textBox = document.getElementById(textID);
  textBox.setAttribute('uuid', value);
  textBox.value = value;
  return;
}
UserInterface.clearTextValue = function(){
  var params = UserInterface.clearTextValue.arguments;
  if(params.length > 0){
    for(i=0;i <= params.length;++i){
      try{
        var tbox = document.getElementById(params[i]);
        tbox.setAttribute('value',"");
        tbox.setAttribute('uuid',"");
      }catch(e){
        // Do nothing - the object has no properties - possibly empty?
      }
    }
  }else{
    this.setMsgPanel("FUNC ERROR: clearTextValue :: params length <= 0");
  }
  
}

UserInterface.refreshPage = function() {
  for(i=0;i<this.refreshBtns.length;++i){
    try{
      if( this.refreshBtns[i].getAttribute('collapsed') !== 'true'){
        this.refreshBtns[i].click();
      }
    }catch(e){}
  }
}
UserInterface.requestContent2 = function(url,params,target){
  if(target == "mainframe"){
    this.requestContent(url, params);
    return;
  }
  if((target == "mainframe4") && window.frames.mainframe4){
    var currentLocation = window.frames.mainframe4.location.href;
    var checkLocation = currentLocation.search(/edit_obj|add_obj|clone_obj|clone_result|edit_bulk|copy_obj|add_bulk/i);
    if((checkLocation != -1) && (document.getElementById('mainframe4').getAttribute('edit') == 'true')){
      var msg = "You have an open edit interface.\n\n" +
                "Press 'OK' to ignore any changes and continue.\n" +
                "Press 'Cancel' to keep the interface open.\n \n";
      if(confirm(msg)){
        document.getElementById('mainframe4').setAttribute('edit','false');
        this.loadContent('/content/_blank.xul');
      }else{
        this.setMsgPanel("TASK CANCELLED");
        this.setLoading(false); return;
      }
    }
  }  
  this.setLoading(true);
  var contentUrl = url;
  var paramArray = new Array();
  var paramString = "";
  for(var i in params) {
    if(this.checkParamLength(params[i])){
      alert("Your Selection is quite large (" + params[i].split(",").length + " items selected).\n ** Please narrow down your selection (maximum 2000) before proceeding **");
      this.setLoading(false);
      return;
    }else{ 
      paramString = i + "=" + escape(params[i]);
      paramArray.push(paramString);
    }
  }
  if(paramArray.length > 0){
    contentUrl += "?" + paramArray.join('&');
  }
  //  this.loadContent(contentUrl);this.iframe.setAttribute("src",url);
  if(document.getElementById(target).getAttribute("src") !== '/content/_blank.xul')
    document.getElementById(target).setAttribute("src", "/content/_blank.xul");
  document.getElementById(target).setAttribute("src", contentUrl);
  this.setLoading(false);
}
UserInterface.requestContent = function(url,params){
  this.setLoading(true);
  var contentUrl = url;
  var paramArray = new Array();
  var paramString = "";
  
  for(var i in params) {
    if(this.checkParamLength(params[i])){
      alert("Your Selection is quite large (" + params[i].split(",").length + " items selected).\n ** Please narrow down your selection (maximum 2000) before proceeding **");
      this.setLoading(false);
      return;
    }else{
      paramString = i + "=" + params[i];
      paramArray.push(paramString);
    }
  }
  
  if(paramArray.length > 0){
    contentUrl += "?" + paramArray.join('&');
  }

  if((contentUrl.search(/callbackEvalSuccess/)) == -1)
    contentUrl += "&callbackEvalSuccess=parent.document.getElementById(\\\'mainframe\\\').setAttribute(\\\'src\\\',\\\'/content/_blank.xul\\\');";

  if(window.frames.mainframe){
    var currentLocation = window.frames.mainframe.location.href;
    var checkLocation = currentLocation.search(/edit_obj|add_obj|clone_obj|clone_result|edit_bulk|add_bulk/i);
    if((checkLocation != -1) && (document.getElementById('mainframe').getAttribute('edit') == 'true')){
      var msg = "You have an open edit interface.\n\n" +
                "Press 'OK' to ignore any changes and continue.\n" +
                "Press 'Cancel' to keep the interface open.\n \n";
      if(confirm(msg)){
        document.getElementById('mainframe').setAttribute('edit','false');
        this.loadContent('/content/_blank.xul');
      }else{
        this.setMsgPanel("TASK CANCELLED");
        this.setLoading(false); return;
      }
    }
  }
  this.loadContent(contentUrl);
  this.setLoading(false);
}

UserInterface.mlv = function(ml){
  var val=0;
  var m11;
  
  if(ml !==null){
    m11 = document.getElementById(ml);
    if((m11 === null) && (this.popupWindow !== null)){
      m11 = this.popupWindow.document.getElementById(ml);
    }
  }
  
  if(m11){
    try {
      val = m11.selectedItem.value;
      if(val === "" || val === null){ val=0;}
    }catch(e){}
  }
  return val;
}

UserInterface.mlv2 = function(ml1, ml2){
  var val="";
  if(document.getElementById(ml1)){
    try {
      val = document.getElementById(ml1).selectedItem.label;
    }catch(e){}
  }

  if(document.getElementById(ml2)){
    try {
      val = val + " / " + document.getElementById(ml2).selectedItem.label;
    }catch(e){}
  }
  return val;
}

UserInterface.requestAction = function(id,url,params,refresh, callbackEval){
  this.setMsgPanel("PERFORMING REQUESTED TASK");
  this.setLoading(true);
  var paramArray = new Array();
  var paramString = "";
  for(var i in params) {
    if(this.checkParamLength(params[i])){
      alert("Your Selection is quite large (" + params[i].split(",").length + " items selected).\n ** Please narrow down your selection (maximum 2000) before proceeding **");
      this.setMsgPanel("TASK CANCELLED");
      this.setLoading(false);
      return;
    }else{    
      paramString = i + "=" + escape(params[i]);
      paramArray.push(paramString);
    }
  }
  var callback = { 
    success: function(o) {
      this.setLoading(false); 
      if(o.responseXML){
        var response = UserInterface.evaluateXMLResponse(o.responseXML.documentElement,o.argument);
        this.setMsgPanel(response);
        if(response.search(/ERROR/i) > -1){
          alert(response);
          if ( callbackEval && callbackEval["failure"] ) {
            //alert(callbackEval["failure"]);
            try { eval(callbackEval["failure"]); } catch(e) {};
          }
          return;
        }
        try{ if (refresh) { document.getElementById(refresh).click(); } }catch(e){ /* Do nothing - this is a best attempt*/ }
        
        if ( callbackEval && callbackEval["success"] ) {
            //alert(callbackEval["success"]);
            try { eval(callbackEval["success"]); } catch(e) {};
        }
        
        return;
      }else if(o.responseText){
        if(UserInterface.evaluateTextResponse(o.responseText)){
          this.setMsgPanel("ERROR[4]: Invalid (text) response recieved from XHR.  Please try again");
          this.setMsgPanel(o.responseText);
          return;
        }
      }else{
        this.setMsgPanel("ERROR[5]: Invalid (empty) response recieved from XHR.  Please try again");
        return;
      }
    }, 
    failure: function(o) {
      this.setMsgPanel('ERROR[6]: XHR Failure, please re-submit form.');
      this.setLoading(false);

        if ( callbackEval && callbackEval["failure"] ) {
            //alert(callbackEval["failure"]);
            try { eval(callbackEval["failure"]); } catch(e) {};
        }

        return;
    }, 
    timeout: 600000,
    scope: this,
    arguments: refresh
  }
  YAHOO.util.Connect.asyncRequest('POST', url, callback, paramArray.join('&'));
}

UserInterface.requestTemplateAction = function(id,url,params,refresh,callbackEval){
  this.setMsgPanel("PERFORMING REQUESTED TASK");
  this.setLoading(true);
  var paramArray = new Array();
  var paramString = "";
  for(var i in params) {
    if(params[i] !== null){
      paramString = i + "=" + escape(params[i]);
      paramArray.push(paramString);
    }
  }
  var callback = { 
    success: function(o) {
      this.setLoading(false); 
      if(o.responseXML){        
        var response = o.responseXML.documentElement;
        var error = response.getElementsByTagName("error");
        var success = response.getElementsByTagName("success");
        if(error.length > 0){
          var msg = error[0].getElementsByTagName('message');
          if ( callbackEval && callbackEval["failure"] ) {
              //alert(callbackEvalFailure);
              var callbackEvalFailure = callbackEval["failure"];
              if(callbackEvalFailure == "alertErrorMsg"){
                alert(msg[0].firstChild.data);
                setMsgFlag = false;
              }else
                try { eval(callbackEvalFailure); } catch(e) { alert('Exception: ' + e.description) };
          }          
        }
        if(success.length > 0) {
          try {
            if(document.getElementById(refresh)){
              document.getElementById(refresh).click();
            }
          }catch(e){}
          if ( callbackEval && callbackEval["success"] ) {
          //alert(callbackEval["success"]);
          try { eval(callbackEval["success"]); } catch(e) {};
        }
          var msg = success[0].getElementsByTagName('message');
          if(msg[0].firstChild.data.search(/^Warning:/i) != -1){
            alert(msg[0].firstChild.data);
          }
        }
        this.setMsgPanel(msg[0].firstChild.data);
        return;
      }else if(o.responseText){
        //alert(o.responseText);
        if(UserInterface.evaluateTextResponse(o.responseText)){
          this.setMsgPanel("ERROR[7]: Invalid (text) response recieved from XHR.  Please try again");
          return;
        }
      }else{
        this.setMsgPanel("ERROR[8]: Invalid (empty) response recieved from XHR.  Please try again");
        return;
      }
    }, 
    failure: function(o) { this.setMsgPanel("ERROR[9]: XHR Failure.  Please try again");this.setLoading(false); return;}, 
    timeout: 60000,
    scope: this
  }
  var cObj = YAHOO.util.Connect.asyncRequest('POST', url, callback, paramArray.join('&'));
}

UserInterface.evaluateTextResponse = function(x) {
  // This function will check text response for session time out and set the document to the login page.
  var index = x.toLowerCase().indexOf("::login::");
  if(index >= 0)  { 
    //set current page to itself, forces login page.
    document.location.href = document.location.href;
    return false;
  }else{
    return true;
  }
}


UserInterface.evaluateXMLResponse = function(x,argument) {
  var error = x.getElementsByTagName("error");
  var success = x.getElementsByTagName("success");
  if(error.length > 0){
    var msg = error[0].getElementsByTagName('message');
  }
  if(success.length > 0) {
    var msg = success[0].getElementsByTagName('message');
  }
  return(msg[0].firstChild.data);
}

UserInterface.timeZoneTest = function() {
  x = new Date()
  currentTimeZoneOffsetInHours = x.getTimezoneOffset()/60;
  //alert('Date: ' + x + '\n' + 'Offset: ' + currentTimeZoneOffsetInHours);
}


UserInterface.IframeContent = function(c) {
  window.frames['mainframe'].document.body.innerHTML = c;
}

UserInterface.loadContent = function(url) {
	if(this.debug) {alert(url);}
  if((this.iframe.getAttribute('src') !== '/content/_blank.xul') && (url !== '/content/_blank.xul'))
    this.iframe.setAttribute("src", "/content/_blank.xul");
    
  var k = url.indexOf("content") - 1;
  if(k > 0){
    if(url.charAt(k) == "/")
      url = url.slice(k);
  }
  this.iframe.setAttribute("src",url);
}

UserInterface.refreshQualified = function(id,params, callbackEval, limit, selectedItem){
  // Check document list boxes for match, if match, clear ListBox.
  this.setMsgPanel("RETRIEVING DATA");
  this.setLoading(true);
  
  for(i=0;i<this.listBoxIDS.length;++i){
  
      var objectID = this.listBoxIDS[i].getAttribute('id');
      //alert('ID = ' + objectID);
      if(objectID === id){
        if(this.debug) {alert(objectID);}
        var query = this.listBoxIDS[i].getAttribute('query');
        //alert(query);
        //var limit = this.listBoxIDS[i].getAttribute('limit');
        if (!limit) { limit = 10000 };

        var lb = new XULListInterface(this.listBoxIDS[i]);
	if(selectedItem)
		lb.selectedItems = selectedItem;        
        lb.refreshDataQualified(query,params,limit, callbackEval);
        this.setLoading(false);
      }
  }
  this.setLoading(false);
}

UserInterface.refreshTreeView = function(id,params, callbackEval, limit){
  // Check document list boxes for match, if match, clear ListBox.
  this.setLoading(true);
  this.setMsgPanel("RETRIEVING DATA");
  for(i=0;i<this.treeIDS.length;++i){
      var objectID = this.treeIDS[i].getAttribute('id');
      //alert('ID = ' + objectID + '=' + id);
      if(objectID === id){
        if(this.debug) {alert(objectID);}
        var query = this.treeIDS[i].getAttribute('query');
	//alert(query);
        //var limit = this.listBoxIDS[i].getAttribute('limit');
        if (!limit) { limit = 10000 };
	//alert(this.treeIDS[i]);
        var lb = new XULTreeInterface(this.treeIDS[i]);
        lb.refreshTreeView2(query,params,limit, callbackEval);
        //this.setLoading(false);
      }
  }
  //this.setLoading(false);
}


UserInterface.refresh = function(id, callbackEval, params) {
  // Check document list boxes for match, if match, clear ListBox.
  for(i=0;i<this.listBoxIDS.length;++i){
      var objectID = this.listBoxIDS[i].getAttribute('id');
      if(objectID === id){
        if(this.debug) {alert(objectID);}
        var query = this.listBoxIDS[i].getAttribute('query');
        var lb = new XULListInterface(this.listBoxIDS[i]);
        lb.refreshData(query, callbackEval, params);
        return;
      }
  }
  if(this.debug){
    alert("Problem: list box id [" + id + "] does not exist in document");
  }
}

// This function is for select boxes that require another selection in a different list box.
UserInterface.refreshSel = function(id,id2, callbackEval) {
  for(i=0;i<this.listBoxIDS.length;++i){
      var objectID = this.listBoxIDS[i].getAttribute('id');
      if(objectID === id){
        if(this.debug) {alert(objectID);}
        var query = this.listBoxIDS[i].getAttribute('query');
        var lb = new XULListInterface(this.listBoxIDS[i]);
        lb.refreshData(query, callbackEval);
        return;
      }
  }
  if(this.debug){ alert("ERROR:  unable to get instance of listbo:" + id); }
}

UserInterface.clearMenuList = function (o){
  var ml;
  try{
    ml = document.getElementById(o);
    ml.removeAllItems();
  }catch(e){
    this.setMsgPanel("ERROR:  unable to get instance of menu list: " + o);
  }
}

UserInterface.editTestBedElement = function(params){
  this.setMsgPanel("RETRIEVING DATA");
  this.setLoading(true);
  var paramArray = new Array();
  var paramString = "";
  var user = null;
  for(var i in params) {
    paramString = i + "=" + escape(params[i]);
    if(i == 'user_info')
      user = params[i];
    paramArray.push(paramString);
  }
  var callback = { 
    success: function(o) {
      this.setLoading(false);
      if(o.responseXML){
          var response = o.responseXML.documentElement;
          var error = response.getElementsByTagName("error");
          var success = response.getElementsByTagName("success");
          var nodes = response.getElementsByTagName("node");
          if(error.length > 0){
            var msg = error[0].getElementsByTagName('message');
            this.setMsgPanel("ERROR: " + msg[0].firstChild.data);
            this.setLoading(false); 
            return;
          }
          if(success.length > 0) {
            var msg = success[0].getElementsByTagName('message');
            if(nodes.length >0 ){
                for(i=0;i<nodes.length;i++){
                  var node = nodes[i];
                  var data =  node.getElementsByTagName("data");
                  var nelementattr_nelement_uuid = ""; var nelement_alias = "";
                  var nelementattr_objgrp_uuid   = ""; var objgrp_name    = "";
                  var nelementattr_index         = "";
                  var nelementattr_objattr_uuid  = ""; var objattr_name;
                  var nelementattr_val           = "";
                  var nelementattr_val_encrypt   = "";
                  var nelementattr_comment       = "";
                  if(data[0].attributes.getNamedItem('testbedelementattr_testbedelement_uuid').value !== null){
                    nelementattr_nelement_uuid = data[0].attributes.getNamedItem('testbedelementattr_testbedelement_uuid').value;
                  }else{
                    this.setMsgPanel("ERROR: Unable to fetch: ALIAS UUID");
                    return;
                  }
                  if(data[0].attributes.getNamedItem('testbedelement_alias').value !== null){
                    nelement_alias = data[0].attributes.getNamedItem('testbedelement_alias').value;
                  }else{
                    this.setMsgPanel("ERROR: Unable to fetch: ELEMENT ALIAS");
                    return;
                  }
                  if(data[0].attributes.getNamedItem('testbedelementattr_objgrp_uuid').value !== null){
                    nelementattr_objgrp_uuid = data[0].attributes.getNamedItem('testbedelementattr_objgrp_uuid').value;
                  }else{
                    this.setMsgPanel("ERROR: Unable to fetch: GROUP UUID");
                    return;
                  }
                  if(data[0].attributes.getNamedItem('objgrp_name').value !== null){
                    objgrp_name = data[0].attributes.getNamedItem('objgrp_name').value;
                  }else{
                    this.setMsgPanel("ERROR: Unable to fetch: GROUP NAME");
                    return;
                  }
                  if(data[0].attributes.getNamedItem('testbedelementattr_index').value !== null){
                    nelementattr_index = data[0].attributes.getNamedItem('testbedelementattr_index').value;
                  }else{
                    this.setMsgPanel("ERROR: Unable to fetch: GROUP INDEX");
                    return;
                  }
                  if(data[0].attributes.getNamedItem('testbedelementattr_objattr_uuid').value !== null){
                    nelementattr_objattr_uuid = data[0].attributes.getNamedItem('testbedelementattr_objattr_uuid').value;
                  }else{
                    this.setMsgPanel("ERROR: Unable to fetch: ATTRIBUTE UUID");
                    return;
                  }
                  if(data[0].attributes.getNamedItem('objattr_name').value !== null){
                    objattr_name = data[0].attributes.getNamedItem('objattr_name').value;
                  }else{
                    this.setMsgPanel("ERROR: Unable to fetch: ATTRIBUTE NAME");
                    return;
                  }
                  if(data[0].attributes.getNamedItem('testbedelementattr_val').value !== null){
                    nelementattr_val = data[0].attributes.getNamedItem('testbedelementattr_val').value;
                  }else{
                    this.setMsgPanel("ERROR: Unable to fetch: ATTRIBUTE VALUE");
                    return;
                  }
                                   
                  if(data[0].attributes.getNamedItem('testbedelementattr_comment').value !== null){
                    nelementattr_comment = data[0].attributes.getNamedItem('testbedelementattr_comment').value;
                  }
                  // If we are here, we have all the fields required for edit:
                  if(document.getElementById('objtypeinfo')){
                    document.getElementById('objtypeinfo').setAttribute('uuid',nelementattr_nelement_uuid);
                    document.getElementById('objtypeinfo').setAttribute('value',nelement_alias);
                  }
                  if(document.getElementById('groupinfo_menulist')){
                    if(document.getElementById('groupinfo_menulist').selectedItem.value != nelementattr_objgrp_uuid){
                      var count = document.getElementById('groupinfo_menulist').itemCount;
                      for(j=0; j < count; j++){
                          var item = document.getElementById('groupinfo_menulist').getItemAtIndex(j);
                          var id = item.value;
                          if((id != "") && (id == nelementattr_objgrp_uuid)){
                              document.getElementById('groupinfo_menulist').selectedIndex = j;
                              break;
                          }else{
                              document.getElementById('groupinfo_menulist').selectedIndex = 0;
                          }
                      }
                    }
                  }
                  if(document.getElementById('positioninfo')){
                    
                    document.getElementById('positioninfo').value = nelementattr_index;
                  }
                  if(document.getElementById('attributeinfo_menulist')){
                   
                    if(document.getElementById('attributeinfo_menulist').selectedItem.value != nelementattr_objattr_uuid){
                      var count = document.getElementById('attributeinfo_menulist').itemCount;
                      for(j=0; j < count; j++){
                          var item = document.getElementById('attributeinfo_menulist').getItemAtIndex(j);
                          var id = item.value;
                          if((id != "") && (id == nelementattr_objattr_uuid)){
                              document.getElementById('attributeinfo_menulist').selectedIndex = j;
                              break;
                          }else{
                              document.getElementById('attributeinfo_menulist').selectedIndex = 0;
                          }
                      }
                    }
                  }
                  if(document.getElementById('defaultvalue')){
                      document.getElementById('defaultvalue').value = nelementattr_val;
                  }
                  if(document.getElementById('obj_description')){
                    document.getElementById('obj_description').value = nelementattr_comment;
                  }
                }
                
            }else{
              this.setMsgPanel("RETRIEVED DATA CONTAINS NO VALID INFORMATION");
              return;
            }
          }
          this.setMsgPanel("ATTRIBUTE RETRIEVED AND READY FOR EDIT");
          return;
      }else if(o.responseText){
        if(UserInterface.evaluateTextResponse(o.responseText)){
          return;
        }
      }else{
        this.setMsgPanel("ERROR[22]: Invalid (empty) response recieved from XHR.  Please try again");
        return;
      }
    }, 
    failure: function(o) { this.setMsgPanel("ERROR[23]: XHR (COMM) Failure.   Please try again");this.setLoading(false); return;}, 
    timeout: 60000,
    scope: this
  }
  YAHOO.util.Connect.asyncRequest('POST', '/content/so.php', callback, paramArray.join('&'));
}

UserInterface.loadMenuItems = function(ml1,params,ml2,selectValue,removeValue){  
  var m1;
  
  if(ml1 !==null){
    m1 = document.getElementById(ml1);
    if((m1 === null) && (this.popupWindow !== null)){
      m1 = this.popupWindow.document.getElementById(ml1);      
    }
  }
  this.setMsgPanel("RETRIEVING DATA.  PLEASE WAIT, THIS MAY TAKE TIME");
  this.setLoading(true);
  var paramArray = new Array();
  var paramString = "";
  for(var i in params) {
    paramString = i + "=" + escape(params[i]);
    paramArray.push(paramString);
  }
  //alert(m1.selectedItem.label);
  var callback = { 
    success: function(o) {
      this.setLoading(false);
      if(o.responseXML){
          var response = o.responseXML.documentElement;
          var error = response.getElementsByTagName("error");
          var success = response.getElementsByTagName("success");
          var nodes = response.getElementsByTagName("node");
          if(error.length > 0){
            var msg = error[0].getElementsByTagName('message');
          }
          if(success.length > 0) {
            var msg = success[0].getElementsByTagName('message');
            if(m1){ m1.removeAllItems();}
            if(nodes.length >0 ){
              try {
                  var counter = document.getElementById(ml1 + '_count');
                  counter.setAttribute('value',nodes.length-1);
              }catch(e){}
              if(m1){
                if(ml2)
                  m1.appendItem(ml2,'');
                var sindex = 0;
                for(i=0;i<nodes.length;i++){
                  var node = nodes[i];
                  var data =  node.getElementsByTagName("data");
                  var v = "";
                  var t = "Error: Element without a title";
                  if(data[0].attributes.getNamedItem('uuid').value !== null){
                    v = data[0].attributes.getNamedItem('uuid').value;
                  }
                  if(data[0].attributes.getNamedItem('title').value !== null){
                    t = data[0].attributes.getNamedItem('title').value;
                  }
                  m1.appendItem(t,v);
                  if((selectValue !== null) && ((v == selectValue) || (t == selectValue))){
                    sindex = i;
                  }
                }
                m1.selectedIndex = sindex;
                if (removeValue !== null || removeValue != '') {
                    for (i=0; i < m1.itemCount; i++) {
                        if (m1.getItemAtIndex(i).value == removeValue) {
                            m1.removeItemAt(i);
                        }
                    }
                }
              }
            }else{
              this.setMsgPanel("RETRIEVED DATA CONTAINS NO VALID INFORMATION");
              return;
            }
          }
          this.setMsgPanel(msg[0].firstChild.data + " (ENTRIES: " + (nodes.length-1) + ")");
          return;
      }else if(o.responseText){
        if(UserInterface.evaluateTextResponse(o.responseText)){
          this.setMsgPanel("Invalid (text) response recieved from XHR.  Please try again");
          return;
        }
      }else{
        this.setMsgPanel("Invalid (empty) response recieved from XHR.  Please try again");
        return;
      }
    }, 
    failure: function(o) { this.setMsgPanel("XHR (COMM) Failure.   Please try again");this.setLoading(false); return;}, 
    timeout: 60000,
    scope: this
  }
  YAHOO.util.Connect.asyncRequest('POST', '/content/so.php', callback, paramArray.join('&'));
}

UserInterface.retrieveLBSelectedListAttrib = function(attrib,listBoxID, childNo, nullNo){
  var lb;
  if(listBoxID.search(/^objTree/) > -1){
    lb = new XULTreeInterface(document.getElementById(listBoxID));
  }else if(listBoxID.search(/^objPopupList/) > -1){
    if(this.popupWindow !== null){
      lb = new XULListInterface(this.popupWindow.document.getElementById(listBoxID));
    }
  }else{
      lb = new XULListInterface(document.getElementById(listBoxID));        
  }
  return lb.getSelectedIDs(attrib, childNo, nullNo);              
}



UserInterface.setLoading = function(state) {
  try {
    if(parent.parent.document.getElementById('progressMeter')){
      parent.parent.document.getElementById('progressMeter').mode = (state) ? 'undetermined' : 'determined';
    }else if(parent.document.getElementById('progressMeter')){
      parent.document.getElementById('progressMeter').mode = (state) ? 'undetermined' : 'determined';
    }else if(document.getElementById('progressMeter')){
      document.getElementById('progressMeter').mode = (state) ? 'undetermined' : 'determined';
    }
  }catch(e){
    //alert(e.message);
  }
  //parent.document.getElementById('progressMeter').mode = (state) ? 'undetermined' : 'determined';
}
UserInterface.openHelp = function(state) {
  try {
    if(parent.parent.document.getElementById('helpWindow')){
      if(parent.parent.document.getElementById('helpWindow').collapsed == true){parent.parent.document.getElementById('helpWindow').setAttribute('collapsed',false);}
    }else if(parent.document.getElementById('helpWindow')){
      if(parent.document.getElementById('helpWindow').collapsed == true){parent.document.getElementById('helpWindow').setAttribute('collapsed',false);}
    }else if(document.getElementById('helpWindow')){
      if(document.getElementById('helpWindow').collapsed == true){document.getElementById('helpWindow').setAttribute('collapsed',false);}
    }
  }catch(e){
    //alert(e.message);
  }
  
}

UserInterface.removeSortDirection = function(id, listBoxID){
    var cells;
    if(listBoxID.search(/^objPopupList/) > -1){
      if(this.popupWindow !== null){
        cells = this.popupWindow.document.getElementById(listBoxID).getElementsByTagName(id);
      }
    }else
      cells = document.getElementById(listBoxID).getElementsByTagName(id);
    for(i=0; i<cells.length; i++)
      cells[i].removeAttribute("sortDirection");    
}

UserInterface.getOrderStmt = function(id,listBoxID, ids){
  var resetSortDirection = true;
  var lb;
  if(ids === null)
    ids = "label1, label2, label3";
    

  if(listBoxID.search(/^objPopupList/) > -1){
    if(this.popupWindow !== null){
      lb = this.popupWindow.document.getElementById(listBoxID);
    }
  }else{
    lb = document.getElementById(listBoxID);
  }
  order = lb.getAttribute('sortDirection');
    if(id) {
      if(lb.getAttribute('sortResource') == id) {
        if(order == "ascending")
          order = "descending";
        else
          order = "ascending";
      } else {
        order = "ascending";
      }
    } else {
      id = lb.getAttribute("sortResource");
    }
    //alert(id + order);
    // clear all listheader sortDirection attribute
    if(listBoxID.search(/^objTree/) > -1)
      this.removeSortDirection("treecol", listBoxID);
    else
      this.removeSortDirection("listheader", listBoxID);
    
    if(resetSortDirection){        
      // reset listbox sortDirection and sortResource attribute     
      lb.setAttribute("sortDirection", order); 
      lb.setAttribute("sortResource", id);
              
      // set selected header sortDirection attribute 
      if(listBoxID.search(/^objPopupList/) > -1){
        if(this.popupWindow !== null){
          this.popupWindow.document.getElementById(listBoxID + "_" + id).setAttribute("sortDirection", order);
        }        
      }else
        document.getElementById(listBoxID + "_" + id).setAttribute("sortDirection", order);
    }
      orderStmt = id;
            
    orderStmt += (order == "ascending")?" asc":" desc";	

    if(ids != "")
      orderStmt += ", " + ids + " asc";
    
    if(this.debug){alert(orderStmt);}
    return orderStmt;                
}


UserInterface.clearListBox = function(id) {
    try {
        var counter = document.getElementById(id + '_count');
        counter.setAttribute('value',0);
    }catch(e){}
      
    // Check document list boxes for match, if match, clear ListBox.
    for (i = 0; i < this.listBoxIDS.length; ++i) {

        if (this.listBoxIDS[i].getAttribute('id') === id) {
            var lb = new XULListInterface(this.listBoxIDS[i]);
            lb.clearListBox();
            return;
        }
    }
}

UserInterface.appendToListBox = function(source,dest) {
    var values = UserInterface.retrieveLBSelectedListAttrib('label',source,0).split(',');
    var ids = UserInterface.retrieveLBSelectedListAttrib('id',source).split(',');
    var listObj = document.getElementById(dest);    
    var destIds = UserInterface.retrieveLBAllAttrib('id',dest).split(',');
    
    if (values == "") {
        alert('you should select least one available element, to perform this action');
        return false;
    }
    
    for(i=0; i < values.length; i++){
        if (destIds.indexOf(ids[i]) >= 0 )
            continue;
        var listitem = document.createElement("listitem");
        var listcell = document.createElement("listcell");
        listitem.setAttribute("id", ids[i]);
        listcell.setAttribute("label", values[i]);
        listcell.setAttribute("type", 'description');
        
        listitem.appendChild(listcell);
        listObj.appendChild(listitem);
    }
    return true;
}

UserInterface.dropSelected = function(id) {
    var listObj = document.getElementById(id);
    if (listObj.selectedIndex > -1) {
        while (listObj.selectedIndex > -1) {
            listObj.removeItemAt(listObj.selectedIndex);
        }
    } else {
        alert('you should select least one available element, to perform this action');
        return false;
    }
    return true;
}

UserInterface.checkParamLength = function(param){
    //convert param to string datatype  
    param += "";
    var paramArray = param.split(",");
    if(paramArray.length > 2000)
      return true;
    else
      return false;
}

UserInterface.selectAll = function (id){
    document.getElementById(id).clearSelection();
    document.getElementById(id).selectAll();
    var obj_count = (document.getElementById(id + '_count').getAttribute('value'))?document.getElementById(id + '_count').getAttribute('value'):0;
    for(var i = document.getElementById(id).selectedItems.length; i < obj_count; i++){
      var item = document.getElementById(id).getItemAtIndex(i);
      document.getElementById(id).ensureIndexIsVisible(i);
      document.getElementById(id).addItemToSelection(item);
    }
}

UserInterface.clearTreeView = function(id) {

    // Check document list boxes for match, if match, clear ListBox.
    for (i = 0; i < this.treeIDS.length; ++i) {

        if (this.treeIDS[i].getAttribute('id') === id) {
            var lb = new XULTreeInterface(this.treeIDS[i]);
            lb.clearTreeView();
            return;
        }
    }
}

UserInterface.setCookies = function(objID, objValue){
  var params= {'obj':'dbTableObj',
                'method':'setCookies',
                'objID':objID,
                'objValue':objValue
              };
                
    var paramArray = new Array();
    var paramString = "";
    for(var i in params) {
      paramString = i + "=" + escape(params[i]);
      paramArray.push(paramString);
    }

    var callback = {
      cache:false
    };

    YAHOO.util.Connect.asyncRequest('POST', '/content/so.php', callback, paramArray.join('&'));  
}

UserInterface.display = function(msg){
  alert(msg);
}

UserInterface.updateDBInfo = function(url,params, message){
  this.setMsgPanel(message);
  this.setLoading(true);
  var paramArray = new Array();
  var paramString = "";
  for(var i in params) {
    paramString = i + "=" + escape(params[i]);
    paramArray.push(paramString);
  }
  YAHOO.util.Connect.asyncRequest('POST', url, null, paramArray.join('&'));
}

UserInterface.editListBx = function(sourceID,destID,names,allowDup){  
  var sBox = document.getElementById(sourceID);
  var dBox = document.getElementById(destID);
  var items = new Array();
  var count = sBox.getRowCount();

  if(names){
      if(names != "all")
        names = trim(names) + ",";    
      for(i=0; i<count;i++){
        var item = sBox.getItemAtIndex(i);
        if(item !== null){
	    var label = item.childNodes[0].getAttribute('label'); //trim(item.childNodes[0].getAttribute('label'));
          if((label != "") && ((names.indexOf(label + ",")) > -1) || (names == "all")){
              sBox.ensureIndexIsVisible(i);
              sBox.addItemToSelection(item);
              if(!allowDup && (names != "all")){
                names = names.replace(label + ",", "");
              }              
          }
        }
      }
  }
  sBox.scrollToIndex(0);
  if(count > 0)
    items = sBox.selectedItems;
  
  if(!allowDup){
    while(items.length > 0){
      var dCount = dBox.getRowCount();
      var alreadyInsert = false;
      if(dCount > 0){
        for(i=0; i < dCount; i++){
          var dItem = dBox.getItemAtIndex(i);
          if(dItem !== null){
            var dLabel = new Array();
            dLabel.push(dItem.childNodes[0].getAttribute('label').toUpperCase(), items[0].childNodes[0].getAttribute('label').toUpperCase());
            dLabel.sort();
            if(dLabel[0] == items[0].childNodes[0].getAttribute('label').toUpperCase()){
              if(dLabel[0] === dLabel[1]){
                sBox.removeChild(items[0]);
                alreadyInsert = true;
                break;
              }
              dBox.insertBefore(items[0],dItem);
              alreadyInsert = true;
              break;
            }
          }          
        }
      }
      if(!alreadyInsert){
        dBox.insertBefore(items[0],null);
      }
      //items = sBox.selectedItems;
    }
  }
  if(names && (names != "all")){
    var nameArray = names.split(",");
    for(i=0; i < nameArray.length - 1; i++){
        if(nameArray[i]){
          var row = document.createElement('listitem');
          var cell = document.createElement('listcell');
          row.setAttribute("id", nameArray[i]);
          cell.setAttribute('label', nameArray[i]);
          cell.setAttribute('value', nameArray[i]);        
          row.appendChild(cell);  
          dBox.appendChild(row);          
        }
    }
  }
  if(items.length > 0){
    sBox.clearSelection();
  }
}

UserInterface.selectMenuList = function(objID, selectValue, radiogroup,popObj){
  var obj;
  var match = false;
  if(objID === null){
    if((this.popupWindow !== null) && (popObj !== null)){
      obj = this.popupWindow.document.getElementById(popObj);
    }
  }else
    obj = document.getElementById(objID);
  var count = obj.itemCount;
  for(i=0; i < count; i++){
      var item = obj.getItemAtIndex(i);
      var name = item.label;      
      if(radiogroup !== null)
        name = item.value;
      if((name != "") && (name == (typeof selectValue == "number" ? selectValue : trim(selectValue)))){
      
          obj.selectedIndex = i;
	  match = true;
          break;
      }
  }
  if(!match){
	obj.selectedIndex = 0;
  }
}
UserInterface.openDialogBox = function(filename, name, width, height){
  this.popupWindow = window.open(filename,
                              name,
                              'left=600,top=200,width=' + width + ',height=' + height + ',location=no,resizable=no,scrollbars=yes',
                              true);
  this.popupWindow.focus();
}

UserInterface.loadTreeSel = function (objID, IDs){
  var i;
//alert(objID);
  var count = document.getElementById(objID).getElementsByTagName('treeitem').length;
//alert(count);
  for(i=0; i < count; i++){
      var item = document.getElementById(objID).contentView.getItemAtIndex(i);
      id = item.getAttribute('id');
//alert(IDs.search(id));
      if((IDs != "") && ((IDs.search(id)) > -1)){
          document.getElementById(objID).view.selection.rangedSelect(i,i,true);
          document.getElementById(objID).boxObject.ensureRowIsVisible(i);
//alert(document.getElementById(objID).view.selection.count);
      }
  }
}

UserInterface.loadTextBox = function(tx,params,noMsg){
  var tb;
  if(tx !==null){
    tb = document.getElementById(tx);
    if((tb === null) && (this.popupWindow !== null)){
      tb = this.popupWindow.document.getElementById(tx);
    }
  }
  //var tb = document.getElementById(tx);
  
  this.setMsgPanel("RETRIEVING DATA");
  this.setLoading(true);
  
  var paramArray = new Array();
  var paramString = "";
  for(var i in params) {
    paramString = i + "=" + escape(params[i]);
    paramArray.push(paramString);
  }
  var callback = {
    success: function(o) {
      if(o.responseXML){
        this.setMsgPanel("ERROR[24]: Invalid (xml) response recieved from XHR.  Please try again");
      }else if(o.responseText){
        if(UserInterface.evaluateTextResponse(o.responseText)){
          tb.value = o.responseText;
          tb.setAttribute('value',o.responseText);
          if(tx == 'remote_log_textbox'){
            var pos = tb.value.length;
            tb.selectionStart = pos;
            tb.selectionEnd = pos;
          }
          
          this.setMsgPanel("DATA RETRIEVED");
        }
      }else{
        tb.value = "";
        this.setMsgPanel("ERROR[25]: Invalid (empty) response recieved from XHR.  Please try again");
      }
    },
    failure: function(o) { this.setMsgPanel('ERROR[26]: XHR Failure, please re-submit form.');},
    timeout: 600000,
    scope: UserInterface,
    arguments: tb
  }
  YAHOO.util.Connect.asyncRequest('POST', '/content/so.php', callback, paramArray.join('&'));
  this.setLoading(false);
}

UserInterface.loadListBx = function(listbox,params){
  var lb;
  if(listbox.search(/^objPopupList/) > -1){
    if(this.popupWindow !== null){
      lb = new XULListInterface(this.popupWindow.document.getElementById(listbox));
    }
  }else{
    lb = new XULListInterface(document.getElementById(listbox));
  }
  this.setMsgPanel("RETRIEVING DATA");
  this.setLoading(true);
  var paramArray = new Array();
  var paramString = "";
  for(var i in params) {
    paramString = i + "=" + escape(params[i]);
    paramArray.push(paramString);
  }

  var callback = {
    success: function(o) {
      this.setLoading(false);
      if(o.responseXML){
        var response = UserInterface.evaluateXMLResponse(o.responseXML.documentElement,o.argument);
        this.setMsgPanel(response);
        lb.loadListBox(o.responseXML.documentElement);
      }else if(o.responseText){
        if(UserInterface.evaluateTextResponse(o.responseText)){
          //alert(o.responseText);
          this.setMsgPanel("ERROR[27]: Invalid (text) response recieved from XHR.  Please try again");
        }
      }else{
        this.setMsgPanel("ERROR[28]: Invalid (empty) response recieved from XHR.  Please try again");
      }
    },
    failure: function(o) { this.setMsgPanel('ERROR[29]: XHR Failure, please re-submit form.');},
    timeout: 600000,
    scope: this,
    arguments: lb
  }
  this.setLoading(true);
  YAHOO.util.Connect.asyncRequest('POST', '/content/so.php', callback, paramArray.join('&'));
  this.setLoading(false);
}

UserInterface.createTextBox = function(gx, counter, placeHolder , params){
    var row;
 
    row = document.getElementById(gx);

    this.setMsgPanel("RETRIEVING DATA");
    this.setLoading(true);
      
    var paramArray = new Array();
    var data = new Array();
    var paramString = "";
    var reponse = "";
    var innerhtml = '';
    var bulk = new Array();
    bulk['sandybridge1u'] = new Array();
    bulk['sandybridge1u']['Calling Routes'] = '500K';
    bulk['sandybridge1u']['Calling Plans'] = '500K';
    bulk['sandybridge1u']['Vnets'] = '1024';
    bulk['sandybridge1u']['Realms'] = '256';
    bulk['sandybridge1u']['End Points'] = '40000';
    bulk['sandybridge2u'] = new Array();
    bulk['sandybridge2u']['Calling Routes'] = '2M';
    bulk['sandybridge2u']['Calling Plans'] = '2M';
    bulk['sandybridge2u']['Vnets'] = '1024';
    bulk['sandybridge2u']['Realms'] = '1024';
    bulk['sandybridge2u']['End Points'] = '200000';
    
    if (gx == 'callrate') {
        innerhtml += '<label control="callrate">Call Attributes</label><groupbox flex="1"><row><caption label="Test Driver" style="font-size:9pt;font-weight:bold;"/><caption label="Call Rate" style="font-size:9pt;font-weight:bold;"/><caption label="Hold Time" style="font-size:9pt;font-weight:bold;"/></row>';
    } else if (gx == 'loadtargetbuild' || gx == 'regtargetbuild') {
        innerhtml += '<label control="testbulkconfig">Target Build</label><groupbox flex="1">';
    } else {
        innerhtml += '<label control="testbulkconfig">Bulk Configuration</label><groupbox flex="1">';
    }
    for(var i in params) {
        paramString = i + "=" + escape(params[i]);
        paramArray.push(paramString);
    }
    var callback = {
        success: function(o) {
            if(o.responseXML){ 
                this.setMsgPanel("ERROR[24]: Invalid (xml) response received from XHR.  Please try again");
            }else if(o.responseText){
                if(UserInterface.evaluateTextResponse(o.responseText)){
                    reponse = o.responseText;
                    data = reponse.split(',');
                    innerhtml += '<row hidden="true"><textbox id="' + counter + '" value="' + data.length + '"/></row>';
                    for(i=0; i < data.length; i++){
                        var j=i+1;
                        var size = 15;
                        if (gx == 'callrate')
                            size = 10;
                        innerhtml += '<row><textbox  id="' + gx + j + "1" + '" value="' + data[i] + '" readonly="readonly" size="'+ size +'"/>' +
                                      '<textbox  id="' + gx + j + "2" + '" value="" placeholder="' + placeHolder + '" size="'+ size +'"/>';
                        if (gx == 'callrate')
                            innerhtml += '<textbox  id="holdtime' + j + "1" + '" value="" placeholder="Seconds" size="'+ size +'"/>';
                        if (gx == 'jobbulkconfig' && UserInterface.mlv('platform') != "") {
                            innerhtml +='<caption label="[1 - ' + bulk[UserInterface.mlv('platform')][data[i]] + ']" style="font-size:9pt;color:red"/>';
                        }
                        innerhtml += '</row>';
                                     
                    }
                    row.innerHTML = innerhtml + '</groupbox>';
                    this.setMsgPanel("DATA RETRIEVED");
                }
            }else{
                this.setMsgPanel("ERROR[25]: Invalid (empty) response received from XHR.  Please try again");
            }
        },
        failure: function(o) { alert('XHR Failure, please refresh the page');},
        timeout: 600000,
        scope: UserInterface,
        arguments: row
    }
    
    YAHOO.util.Connect.asyncRequest('POST', '/content/so.php', callback, paramArray.join('&'));
}

UserInterface.makeSshConnection = function(params, name){ 

  this.setMsgPanel("RETRIEVING DATA.  PLEASE WAIT, THIS MAY TAKE TIME");
  this.setLoading(true);
  var paramArray = new Array();
  var paramString = "";
  for(var i in params) {
    paramString = i + "=" + escape(params[i]);
    paramArray.push(paramString);
  }
  
  var callback = { 
    success: function(o) {
      this.setLoading(false);
      if(o.responseXML){
          var response = o.responseXML.documentElement;
          var error = response.getElementsByTagName("error");
          var success = response.getElementsByTagName("success");
          var nodes = response.getElementsByTagName("node");
          if(error.length > 0){
            var msg = error[0].getElementsByTagName('message');
          }
          if(success.length > 0) {
            var msg = success[0].getElementsByTagName('message');
            
            if(nodes.length >0 ){
              var data =  nodes[0].getElementsByTagName("data");
              if(data[0].attributes.getNamedItem('title').value !== null){
                UserInterface.openDialogBox('ssh://root@' + data[0].attributes.getNamedItem('title').value, name);
              } else {
                this.setMsgPanel("RETRIEVED DATA CONTAINS NO VALID INFORMATION");
                return;
              }
            }else{
              this.setMsgPanel("RETRIEVED DATA CONTAINS NO VALID INFORMATION");
              return;
            }
          }
          this.setMsgPanel(msg[0].firstChild.data + " (ENTRIES: " + (nodes.length-1) + ")");
          return;
      }else if(o.responseText){
        if(UserInterface.evaluateTextResponse(o.responseText)){
          this.setMsgPanel("Invalid (text) response recieved from XHR.  Please try again");
          return;
        }
      }else{
        this.setMsgPanel("Invalid (empty) response recieved from XHR.  Please try again");
        return;
      }
    }, 
    failure: function(o) { this.setMsgPanel("XHR (COMM) Failure.   Please try again");this.setLoading(false); return;}, 
    timeout: 60000,
    scope: this
  }
  YAHOO.util.Connect.asyncRequest('POST', '/content/so.php', callback, paramArray.join('&'));
}

UserInterface.setElementsValue = function(elements, params, callbackFunction){
    
    this.setMsgPanel("RETRIEVING DATA");
    this.setLoading(true);
    
    var paramArray = new Array();
    var data = new Array();
    var elementId = new Array();
    var elementType = new Array();
    var paramString = "";
    var reponse = "";
    
    for(var i in params) {
        paramString = i + "=" + escape(params[i]);
        paramArray.push(paramString);
    }
    for(var i in elements) {
        elementId.push(i);
        elementType.push(elements[i]);
    }
    
    var callback = {
        success: function(o) {
            this.setLoading(false);
            if(o.responseXML){ 
                this.setMsgPanel("ERROR[24]: Invalid (xml) response received from XHR.  Please try again");
            }else if(o.responseText){
                if(UserInterface.evaluateTextResponse(o.responseText)){
                    reponse = o.responseText;
                    data = reponse.split(',');
                    for(i=0; i < data.length; i++){
                        if (elementType[i] == 'menulist') {
                            UserInterface.selectMenuList(elementId[i],data[i]);
                        } else if ( elementType[i] == 'textbox'){
                            document.getElementById(elementId[i]).value=data[i];
                        }
                    }
                    this.setMsgPanel("DATA RETRIEVED");
                    if (callbackFunction != null)
                        callbackFunction;
                }
            }else{
                this.setMsgPanel("ERROR[25]: Invalid (empty) response received from XHR.  Please try again");
            }
        },
        failure: function(o) { alert('XHR Failure, please refresh the page');this.setLoading(false);},
        timeout: 600000,
        scope: UserInterface
    }
    
    YAHOO.util.Connect.asyncRequest('POST', '/content/so.php', callback, paramArray.join('&'));
}

UserInterface.clearSessionStorage = function() {
        var sessionCount = sessionStorage.length;
        for(var i = (sessionCount - 1); i >= 0; i--) {
            var name = sessionStorage.key(i);
            sessionStorage.removeItem(name);
        }
}

UserInterface.removeFromList = function(id, value) {
    var dropDown = document.getElementById(id);
    for (i=0; i < dropDown.itemCount; i++) {
        if (dropDown.getItemAtIndex(i).value == value) {
            dropDown.removeItemAt(i);
            return true;
        }
    }
}

UserInterface.editTreeItem = function(treeId, Index, params, childNo, callbackFunction, value){  
    var paramArray = new Array();
    var paramString = "";
    var item;
    
    this.setMsgPanel("RETRIEVING DATA");
    this.setLoading(true);
    
    if (childNo > 0 )
        item = document.getElementById(treeId).contentView.getItemAtIndex(Index).firstChild.childNodes[childNo];
    else 
        item = document.getElementById(treeId).contentView.getItemAtIndex(Index).firstChild.childNodes['0'];
    
    if (params.length <= 0 && value != "") {
        item.setAttribute('label', value);
        return true;
    }
    
    for(var i in params) {
        paramString = i + "=" + escape(params[i]);
        paramArray.push(paramString);
    }
    
    var callback = {
        success: function(o) {
            this.setLoading(false);
            if(o.responseXML){ 
                this.setMsgPanel("ERROR[24]: Invalid (xml) response received from XHR.  Please try again");
            }else if(o.responseText){
                if(UserInterface.evaluateTextResponse(o.responseText)){
                    reponse = o.responseText;
                    item.setAttribute('label', reponse);
                    this.setMsgPanel("DATA RETRIEVED");
                    if (callbackFunction != null)
                        callbackFunction;
                }
            }else{
                this.setMsgPanel("ERROR[25]: Invalid (empty) response received from XHR.  Please try again");
            }
        },
        failure: function(o) { alert('XHR Failure, please refresh the page');this.setLoading(false);},
        timeout: 600000,
        scope: UserInterface
    }
    
    YAHOO.util.Connect.asyncRequest('POST', '/content/so.php', callback, paramArray.join('&'));
}

UserInterface.retrieveLBAllAttrib = function(attrib,listBoxID, childNo, nullNo){
  var lb;
  if(listBoxID.search(/^objPopupList/) > -1){
    if(this.popupWindow !== null){
      lb = new XULListInterface(this.popupWindow.document.getElementById(listBoxID));
    }
  }else{
      lb = new XULListInterface(document.getElementById(listBoxID));        
  }
  return lb.getAllIDs(attrib, childNo, nullNo);              
}

UserInterface.setClock = function(clockId) {
    var today=new Date();
    var h=today.getHours();
    var m=today.getMinutes();
    var s=today.getSeconds();
    if (document.getElementById('scheduletime').value == 1) {
        document.getElementById('scheduletime').value = "";
        return;
    }
    document.getElementById(clockId).value = h+":"+m+":"+s;
    var t = setTimeout(function(){UserInterface.setClock(clockId)},500);
}
