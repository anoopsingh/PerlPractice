// This XULTreeInterface acccepts an XUL Element of type tree
function XULTreeInterface (treeObj) {
  this.initflag = false;
  this.debug = false;
  this.treeObject = treeObj;
  // Broadcaster variables:
  this.serverObject = null;
  this.treeObjects = Array();
  this.context = this.treeObject.getAttribute('context');

//***************************************************    
  // Currently, init does nothing.
  this.init = function() {
    if(!this.initflag){
        //this.listBoxObject = document.getElementById(this.listBoxId);
        if(this.debug){
          //alert("init happened");
        }
        this.initflag = true;
    }
  }

//***************************************************
  this.clearTreeView = function (){
    //UserInterface.setMsgPanel("clearTreeView");

    var children = this.treeObject.getElementsByTagName("treechildren")[0];
    if(children){
      while (children.childNodes.length > 0) {
              children.removeChild(children.firstChild);
      }
    }
  }  

//***************************************************
  this.getSelectedIDs = function(attrib, childNo){
    var paramArray = new Array();

    var count = (this.treeObject.view)?this.treeObject.view.selection.getRangeCount():0;  
    while (count--)  
    {  
       var start = {};  
       var end = {};  
       this.treeObject.view.selection.getRangeAt(count, start, end);  
       for(var c = start.value; c <= end.value; c++)  
       {
          if(childNo >= 0) {
              var item = this.treeObject.contentView.getItemAtIndex(c).firstChild.childNodes[childNo];
          } else {
              var item = this.treeObject.contentView.getItemAtIndex(c);
          }        
          try { 
              if (item.getAttribute(attrib))
                {  paramArray.push(item.getAttribute(attrib));}
          } catch(e) {}; 
       }
    } 
    return paramArray.join(',');      
}

//***************************************************   
  this.refreshTreeView2=function(queryid,params,limit, callbackEval) {
    var paramArray = new Array();
    var paramString = "";
    var param;
    paramString = "queryid=" + queryid;
    paramArray.push(paramString);
    for(var i in params) {
        paramString = i + "=" + escape(params[i]);
        paramArray.push(paramString);
        
    }

    if (!limit) { var limit1 = 10000; } else { var limit1 = limit; }
    paramString = "limit=" + limit1;
    paramArray.push(paramString);

    this.clearTreeView();
    
    try {
        //var counter = document.getElementById(this.treeObject.getAttribute('id') + '_count');
        //counter.setAttribute('value',0);
    }catch(e){UserInterface.setMsgPanel("refreshTreeView Error: " + e);}
    
    var callback = {

        success: function(o) {
            UserInterface.setLoading(false); 
            this.loadTreeView(o.responseXML.documentElement);
            //alert(o.responseXML.documentElement);            
            if ( callbackEval && callbackEval["success"] ) {
                try { eval(callbackEval["success"]); } catch(e) {UserInterface.setMsgPanel("refreshTreeView Callback Success Error: " + e);};
            }
        },

        failure: function(o) {
            UserInterface.setMsgPanel("XULTreeInterface::RefreshTreeView2():XHR Failure");
            if ( callbackEval && callbackEval["failure"] ) {
                //alert(callbackEval["failure"]);
                try { eval(callbackEval["failure"]); } catch(e) {UserInterface.setMsgPanel("refreshTreeView Callback Failure Error: " + e);};
            }
        },
      
        scope: this
    };
    var request = YAHOO.util.Connect.asyncRequest('POST',"/content/dataengine.php", callback, paramArray.join('&'));
  }

//*************************************************** 
  this.loadTreeView = function(x) {
    UserInterface.setMsgPanel("loadTreeView");
    var errors = x.getElementsByTagName("error");
    var success = x.getElementsByTagName("success");
    var nodes = x.getElementsByTagName("node");
    var treeName = this.treeObject.getAttribute('id');
    var openIds = this.treeObject.getAttribute('openIds');
    
    var countElements = x.getElementsByTagName("count");
    if ( countElements.length > 0 ) {
        var count = countElements[0].firstChild.data;
    } else {
        var count = nodes.length;
    }   

    if(errors.length > 0){
      var msg = errors[0].getElementsByTagName('message');
    }
    if(success.length > 0){
      var msg = success[0].getElementsByTagName('message');
    }
    
    this.clearTreeView();

    try {
        if ( nodes.length < count ) {
            var counterValue = nodes.length + ' / ' + count;
        } else {
            var counterValue = nodes.length;
        }

    var counter = document.getElementById(treeName + '_count');
    counter.setAttribute('value',counterValue);
    }catch(e){ UserInterface.setMsgPanel("loadTreeView Error1: " + e);}
    
    if(nodes.length > 0){
      // Great, data engine has return information from the query request
      var treechildren = this.treeObject.getElementsByTagName("treechildren")[0];
      if(!treechildren)
        treechildren = document.createElement("treechildren");
      treechildren.setAttribute("id", treeName + '_treechildren');
      for(i=0;i<nodes.length;i++){

        var node = nodes[i];
        var treeitem = document.createElement("treeitem");
        var treerow = document.createElement("treerow");
        var id = node.getElementsByTagName("id");      
        try {
          try {
            treeitem.setAttribute("id", id[0].firstChild.data);
            treerow.setAttribute("tooltip", "IDENTIFIER: " + id[0].firstChild.data);
            //UserInterface.setMsgPanel("SET IDENTIFIER");     
          }catch (iderr){
            //UserInterface.setMsgPanel("loadTreeView Error2: " . iderr);
            // Do nothing - there is no link to be had
          }
          var sub_feature = false;
          
          if(treeName == "objTree_task1"){
            treeitem.setAttribute("container", "true");
            if((openIds != null) && (openIds.search(id[0].firstChild.data) > -1)){
              treeitem.setAttribute("open", "true");
            }else{
              treeitem.setAttribute("open", "false");
            }
          }
          var isRetired = false;
          var isSuspect = false;
          var isSubFeatureRequirement = false;

          try {
            var retired = node.getElementsByTagName("retired");
            var status = retired[0].firstChild.data;
            if (status.match(/[^A|a]$/)) {
                isRetired = true;
               //listitem.setAttribute("class", "retiredItem");
            }
          }catch (err){
            //UserInterface.setMsgPanel("loadTreeView Error3: " + err);
            // Do nothing - there is no link to be had
          }
          try {
            var link = node.getElementsByTagName("link");
            treeitem.setAttribute("link", link[0].firstChild.data);
            var uuid = node.getElementsByTagName("uuid");
            treeitem.setAttribute("uuid", uuid[0].firstChild.data);
	    var url = node.getElementsByTagName("url");
          }catch (linkerr){
            // Do nothing - there is no link to be had
          }
          
          
          if(this.context2){
            treeitem.setAttribute("context", this.context2);
          }
          try {
            if ((treeName == 'objTree_jobqueue_results') || (treeName == 'objTree_jobqueue')) {
              var link = node.getElementsByTagName("loadjob_name");
              treeitem.setAttribute("loadjob_name", link[0].firstChild.data);
              link = node.getElementsByTagName("loadjob_username");
              treeitem.setAttribute("loadjob_username", link[0].firstChild.data);
              if (treeName == 'objTree_jobqueue') {
                 link = node.getElementsByTagName("loadjob_status");
                 treeitem.setAttribute("loadjob_status", link[0].firstChild.data);
                 link = node.getElementsByTagName("log");
                 treeitem.setAttribute("log", link[0].firstChild.data);
              } else {
                 link = node.getElementsByTagName("starttime");
                 treeitem.setAttribute("starttime", link[0].firstChild.data);
                 link = node.getElementsByTagName("result");
                 treeitem.setAttribute("result", link[0].firstChild.data);
                 link = node.getElementsByTagName("endtime");
                 treeitem.setAttribute("endtime", link[0].firstChild.data);
                 link = node.getElementsByTagName("resulturl");
                 treeitem.setAttribute("resulturl", link[0].firstChild.data);
                 link = node.getElementsByTagName("loadJob_dut");
                 treeitem.setAttribute("loadJob_dut", link[0].firstChild.data)
                 link = node.getElementsByTagName("loadJob_platform");
                 treeitem.setAttribute("loadJob_platform", link[0].firstChild.data)
              }
 
              link = node.getElementsByTagName("dutrelease");
              treeitem.setAttribute("dutrelease", link[0].firstChild.data);
              link = node.getElementsByTagName("build");
              treeitem.setAttribute("build", link[0].firstChild.data);
              link = node.getElementsByTagName("testcase");
              treeitem.setAttribute("testcase", link[0].firstChild.data);
            }
            if (treeName == 'objTree_topology' || treeName == 'objTree_testbedtopology') {
              var link = node.getElementsByTagName("id");
              treeitem.setAttribute("testbedtopology_uuid", link[0].firstChild.data);
              link = node.getElementsByTagName("testbedtopology_name");
              if (treeName == 'objTree_topology' ) {
                treeitem.setAttribute("testbedtopology_name", link[0].firstChild.data);
                link = node.getElementsByTagName("testbedtopology_dut");
                treeitem.setAttribute("testbedtopology_dut", link[0].firstChild.data);
                link = node.getElementsByTagName("testbedtopology_type");
                treeitem.setAttribute("testbedtopology_type", link[0].firstChild.data);
                link = node.getElementsByTagName("testbedtopology_location");
                treeitem.setAttribute("testbedtopology_location", link[0].firstChild.data);
                link = node.getElementsByTagName("testbedtopology_usage");
                treeitem.setAttribute("testbedtopology_usage", link[0].firstChild.data);
                link = node.getElementsByTagName("testbedtopology_category");
                treeitem.setAttribute("testbedtopology_category", link[0].firstChild.data);
              }
              link = node.getElementsByTagName("testbedtopology_testbedelements");
              treeitem.setAttribute("testbedtopology_testbedelements", link[0].firstChild.data);
              link = node.getElementsByTagName("testbedtopology_otherTestbedelements");
              treeitem.setAttribute("testbedtopology_otherTestbedelements", link[0].firstChild.data);
              
            }
            if (treeName == 'objTree_loadtests') { 
              var link = node.getElementsByTagName("id");
              treeitem.setAttribute("loadtest_uuid", link[0].firstChild.data);
              link = node.getElementsByTagName("loadtest_testcaseid");
              treeitem.setAttribute("loadtest_testcaseid", link[0].firstChild.data);
              link = node.getElementsByTagName("loadtest_name");
              treeitem.setAttribute("loadtest_name", link[0].firstChild.data);
              link = node.getElementsByTagName("loadtest_releases");
              treeitem.setAttribute("loadtest_releases", link[0].firstChild.data);
              link = node.getElementsByTagName("loadtest_platforms");
              treeitem.setAttribute("loadtest_platforms", link[0].firstChild.data);
              link = node.getElementsByTagName("loadtest_requiredelements");
              treeitem.setAttribute("loadtest_requiredelements", link[0].firstChild.data);
              link = node.getElementsByTagName("loadtest_description");
              treeitem.setAttribute("loadtest_description", link[0].firstChild.data);
              link = node.getElementsByTagName("loadtest_dut");
              treeitem.setAttribute("loadtest_dut", link[0].firstChild.data);
            }
            if (treeName == 'objTree_user' ) { 
              var link = node.getElementsByTagName("userId");
              treeitem.setAttribute("userId", link[0].firstChild.data);
              link = node.getElementsByTagName("userName");
              treeitem.setAttribute("userName", link[0].firstChild.data);
              link = node.getElementsByTagName("firstName");
              treeitem.setAttribute("firstName", link[0].firstChild.data);
              link = node.getElementsByTagName("lastName");
              treeitem.setAttribute("lastName", link[0].firstChild.data);
              link = node.getElementsByTagName("emailId");
              treeitem.setAttribute("emailId", link[0].firstChild.data);
              link = node.getElementsByTagName("status");
              treeitem.setAttribute("status", link[0].firstChild.data);
              link = node.getElementsByTagName("roleName");
              treeitem.setAttribute("roleName", link[0].firstChild.data);
              link = node.getElementsByTagName("roleId");
              treeitem.setAttribute("roleId", link[0].firstChild.data);
            }
            if (treeName == 'objTree_summaryreports' ) {
              var link = node.getElementsByTagName("reportpath");
              treeitem.setAttribute("reportpath", link[0].firstChild.data);
            }
            if (treeName == 'objTree_regtests') { 
              var link = node.getElementsByTagName("id");
              treeitem.setAttribute("regressionsuite_uuid", link[0].firstChild.data);
              link = node.getElementsByTagName("regressionsuite_name");
              treeitem.setAttribute("regressionsuite_name", link[0].firstChild.data);
              link = node.getElementsByTagName("regressionsuite_dut");
              treeitem.setAttribute("regressionsuite_dut", link[0].firstChild.data);
              link = node.getElementsByTagName("regressionsuite_releases");
              treeitem.setAttribute("regressionsuite_releases", link[0].firstChild.data);
              link = node.getElementsByTagName("regressionsuite_platforms");
              treeitem.setAttribute("regressionsuite_platforms", link[0].firstChild.data);
              link = node.getElementsByTagName("regressionsuite_requiredelements");
              treeitem.setAttribute("regressionsuite_requiredelements", link[0].firstChild.data);
              link = node.getElementsByTagName("regressiontests_testcases");
              treeitem.setAttribute("regressiontests_testcases", link[0].firstChild.data);
              link = node.getElementsByTagName("regressionsuite_type");
              treeitem.setAttribute("regressionsuite_type", link[0].firstChild.data);
              link = node.getElementsByTagName("regressionsuite_description");
              treeitem.setAttribute("regressionsuite_description", link[0].firstChild.data);
              link = node.getElementsByTagName("regressionsuite_path");
              treeitem.setAttribute("regressionsuite_path", link[0].firstChild.data);
              link = node.getElementsByTagName("regressionsuite_env");
              treeitem.setAttribute("regressionsuite_env", link[0].firstChild.data);
              link = node.getElementsByTagName("regressionsuite_bucket");
              treeitem.setAttribute("regressionsuite_bucket", link[0].firstChild.data);
            }
            
          }catch (err) {};
   
          var labelCnt = 1;
          while(node.getElementsByTagName("label" + labelCnt)){
            var label = node.getElementsByTagName("label" + labelCnt);
            var treecell = document.createElement("treecell");
            if(label[0].firstChild !== null){
                var value = label[0].firstChild.data;                            
                  treecell.setAttribute("label", label[0].firstChild.data);
            }else{
              treecell.setAttribute("label", "");
            }

	    

            if (isRetired) {             
                treecell.setAttribute("properties", "retiredItem");
            }
            if(treeName == "objTree_task1"){
              if(labelCnt == 9){
                  treecell.setAttribute("editable", "true");                 
                  if((value == "") || (value == "0000-00-00"))
                    treecell.setAttribute("label", "YYYY-mm-dd");
                  //treeitem.addEventListener("onClick", "alert('testing 123!');", true);  
              }else
                  treecell.setAttribute("editable", "false");
            }            
            treerow.appendChild(treecell);
            labelCnt++;
          }
        }catch (er){
          UserInterface.setMsgPanel(er);
        }
        treeitem.appendChild(treerow);
           
        treechildren.appendChild(treeitem);
      } // end of for loop
      this.treeObject.appendChild(treechildren);
    }else{
      UserInterface.setMsgPanel("RETRIEVED DATA CONTAINS NO NODES");
    }
    UserInterface.setMsgPanel(msg[0].firstChild.data + " (TREE ENTRIES: " + nodes.length + ")");
  }
}
