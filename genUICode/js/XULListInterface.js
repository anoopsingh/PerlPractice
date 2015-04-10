// This XULListInterface acccepts an XUL Element of type list
function XULListInterface (listBoxObj) {
  this.initflag = false;
  this.debug = false;
  //this.listBoxId = listBoxObj;
  this.listBoxObject = listBoxObj;
  // Broadcaster variables:
  this.serverObject = null;
  this.selectedItems = null;
  this.listBoxObjects = Array();
  this.context = this.listBoxObject.getAttribute('pup');
  
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
  this.clearListBox = function (){
    var count = this.listBoxObject.getRowCount();
    while (count--){
      this.listBoxObject.removeItemAt(count);
    }
  }
  
  this.refreshDataQualified=function(queryid,params,limit, callbackEval) {
    var paramArray = new Array();
    var paramString = "";
    var param;
    paramString = "queryid=" + queryid;
    paramArray.push(paramString);
    for(var i in params) {
        
        //User requested a page to be loaded
        //This paramter is processed locally w/o passing to the server
        if ( i == "page" ) {
            
            var pageNo = 1;
            
            try {
                var pageNoObj = document.getElementById(this.listBoxObject.getAttribute('id') + '_pageNo');
                if (pageNoObj.getAttribute('value')) {
                    pageNo = parseInt(pageNoObj.getAttribute('value'));
                } else {
                    alert("WARNING: Element " + this.listBoxObject.getAttribute('id') + "_pageNo does not have a defined value\n\nContact admin\n");
                }
                
            } catch(e) {
                alert("ERROR: Element " + this.listBoxObject.getAttribute('id') + "_pageNo not found\n\nContact admin\n");
                return;
            }
            
            var pageAll = 1;

            try {
                var pageAllObj = document.getElementById(this.listBoxObject.getAttribute('id') + '_pageAll');
                if (pageAllObj.getAttribute('value')) {
                    pageAll = parseInt(pageAllObj.getAttribute('value'));
                } else {
                    alert("WARNING: Element " + this.listBoxObject.getAttribute('id') + "_pageAll does not have a defined value\n\nContact admin\n");
                }

            } catch(e) {
                alert("ERROR: Element " + this.listBoxObject.getAttribute('id') + "_pageAll not found\n\nContact admin\n");
                return;
            }
            
            try {
                var maxPerPage = parseInt(this.listBoxObject.getAttribute('maxPerPage'));
            } catch(e) {
                alert("WARNING: Attribute maxPerPage for list " + this.listBoxObject.getAttribute('id') + "not defined\n\nContact admin\n");
                var maxPerPage = 200; // Has to be set and different than 0; we devide by it
            }

            var pageAction = params[i];

            var newPage = 0;
            
            if ( pageAction == "first" ) {
                newPage = "1";

            } else if ( pageAction == "prev" ) {
                newPage = (pageNo > 1)? pageNo - 1 : 1;

            } else if ( pageAction == "next" ) {
                newPage = (pageNo < pageAll)? pageNo + 1 : pageAll;

            } else if ( pageAction == "last" ) {
                newPage = pageAll;

            } else if ( pageAction == "current" ) {
                newPage = pageNo;

            } else {
                alert("WARNING: Invalid page parameter value: " + pageAction + "\n\nContact admin\n");
                newPage = pageNo;
            }

            limit = (newPage - 1) * maxPerPage  + "," + maxPerPage;
            
        } else {

            paramString = i + "=" + escape(params[i]);
            paramArray.push(paramString);
        }
    }

    if (!limit) { var limit1 = 10000; } else { var limit1 = limit; }
    paramString = "limit=" + limit1;
    paramArray.push(paramString);

    this.clearListBox();
    
    try {
        var counter = document.getElementById(this.listBoxObject.getAttribute('id') + '_count');
        counter.setAttribute('value',0);
    }catch(e){}
      
    var callback = {

        success: function(o) {
            this.loadListBox(o.responseXML.documentElement);

            //Is paging mechanism enabled for the list?
            if (pageNoObj) {

                var countElements = o.responseXML.documentElement.getElementsByTagName("count");
                if ( countElements.length > 0 ) {
                    var total = parseInt(countElements[0].firstChild.data);
                    
                    var pages = Math.ceil(total / maxPerPage);
    
                    if (pageAllObj) {
                        pageAllObj.setAttribute('value', pages);
                    }
                }
    
                //Set the new page number
                if (pageNoObj) {
                    pageNoObj.setAttribute('value', newPage);
                }
    
                //Enable disable buttons
                
                var disabledValue = 'false';
                
                // First and Previous buttons
                if (newPage > 1) {
                    disabledValue = 'false';
                } else {
                    disabledValue = 'true';
                }
    
                try {
                    document.getElementById('button_' + this.listBoxObject.getAttribute('id') + '_pageFirst').setAttribute('disabled', disabledValue);
                } catch(e) {
                    alert("ERROR: Element " + 'button_' + this.listBoxObject.getAttribute('id') + "_pageFirst not found\n\nContact admin\n");
                }
    
                try {
                    document.getElementById('button_' + this.listBoxObject.getAttribute('id') + '_pagePrev').setAttribute('disabled', disabledValue);
                } catch(e) {
                    alert("ERROR: Element " + 'button_' + this.listBoxObject.getAttribute('id') + "_pagePrev not found\n\nContact admin\n");
                }
    
                // Last and Next buttons
                if (newPage < pages) {
                    disabledValue = 'false';
                } else {
                    disabledValue = 'true';
                }
    
                try {
                    document.getElementById('button_' + this.listBoxObject.getAttribute('id') + '_pageNext').setAttribute('disabled', disabledValue);
                } catch(e) {
                    alert("ERROR: Element " + 'button_' + this.listBoxObject.getAttribute('id') + "_pageNext not found\n\nContact admin\n");
                }
    
                try {
                    document.getElementById('button_' + this.listBoxObject.getAttribute('id') + '_pageLast').setAttribute('disabled', disabledValue);
                } catch(e) {
                    alert("ERROR: Element " + 'button_' + this.listBoxObject.getAttribute('id') + "_pageLast not found\n\nContact admin\n");
                }
            }
            
            if ( callbackEval && callbackEval["success"] ) {
                //alert(callbackEval["success"]);
                try { eval(callbackEval["success"]); } catch(e) {};
            }
        },

        failure: function(o) {
            UserInterface.setMsgPanel("XULListInterface::refreshDataQualified(): XHR Failure");
            if ( callbackEval && callbackEval["failure"] ) {
                //alert(callbackEval["failure"]);
                try { eval(callbackEval["failure"]); } catch(e) {};
            }
        },
      
        scope: this
    };

    //alert('HERE');
    //alert(paramArray.join('&'));
    var request = YAHOO.util.Connect.asyncRequest('POST',"/content/dataengine.php", callback, paramArray.join('&'));
  }


  // Do not include the prefix of '?' for post params, YAHOO seems to be pre-pending this anyway.
  this.refreshData = function(queryid, callbackEval, params) {
    var paramArray = new Array();
    var paramString = "";
    paramString = "queryid=" + queryid;
    paramArray.push(paramString);
    for(var i in params) {
        paramString = i + "=" + escape(params[i]);
        paramArray.push(paramString);
 
    }

    var callback = {
      success: function(o) {
        this.loadListBox(o.responseXML.documentElement);

            if ( callbackEval && callbackEval["success"] ) {
                //alert(callbackEval["success"]);
                try { eval(callbackEval["success"]); } catch(e) {};
            }

        },
      failure: function(o) {
        alert("XULListInterface::refreshData(): XHR Failure");

            if ( callbackEval && callbackEval["failure"] ) {
                //alert(callbackEval["success"]);
                try { eval(callbackEval["failure"]); } catch(e) {};
            }
        },
      scope: this
    };
    var request = YAHOO.util.Connect.asyncRequest('POST',"/content/dataengine.php", callback, paramArray.join('&'));
     
  }

  this.sleep = function(){
      //alert("hello");
  }

  this.getSelectedIDs = function(attrib, childNo, nullNo){
      var paramArray = new Array();

      var count = this.listBoxObject.selectedCount;
      while (count--) {
          if(childNo >= 0) {
              var item = this.listBoxObject.selectedItems[count].childNodes[childNo];
          } else {
              var item = this.listBoxObject.selectedItems[count];
          }
          try { 
              if (item.getAttribute(attrib)) { paramArray.unshift(item.getAttribute(attrib)); }
              else{
                if(nullNo)
                  paramArray.unshift('');
              }
          } catch(e) {};
      }

      return paramArray.join(',');
  }

  this.loadListBox = function(x) {
    var errors = x.getElementsByTagName("error");
    var success = x.getElementsByTagName("success");
    var nodes = x.getElementsByTagName("node");
    var width = new Array();   
    var headers;
    var counter;
    var listName = this.listBoxObject.getAttribute('id');

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
    
    this.clearListBox();
    
    try {
        if ( nodes.length < count ) {
            var counterValue = nodes.length + ' / ' + count;
        } else {
            var counterValue = nodes.length;
        }

        if(listName.search(/^objPopupList/) > -1){
          if(UserInterface.popupWindow !== null)
            counter = UserInterface.popupWindow.document.getElementById(this.listBoxObject.getAttribute('id') + '_count');
//alert(counter); 
        }else{
          counter = document.getElementById(this.listBoxObject.getAttribute('id') + '_count');
        }
        counter.setAttribute('value',counterValue);
    }catch(e){ }
    
    if(nodes.length > 0){
      // Great, data engine has return information from the query request
      headers = this.listBoxObject.getElementsByTagName("listheader");      
      for(i=0;i<nodes.length;i++){

        var node = nodes[i];

        try {
          var listitem = document.createElement("listitem");
          try {
            var id = node.getElementsByTagName("id");

            listitem.setAttribute("id", id[0].firstChild.data);
            listitem.setAttribute("value", id[0].firstChild.data);
            listitem.setAttribute("tooltiptext", "IDENTIFIER: " + id[0].firstChild.data);
            //UserInterface.setMsgPanel("SET INDENTIFIER");     
          }catch (iderr){
            // Do nothing - there is no link to be had
          }
          
        var isRetired = false;
        var isSuspect = false;

          try {
            var retired = node.getElementsByTagName("retired");
            var status = retired[0].firstChild.data;
            if (status.match(/[^A|a]$/)) {
                isRetired = true;
               //listitem.setAttribute("class", "retiredItem");
            }
          }catch (err){
            // Do nothing - there is no link to be had
          }
          
          
          if (isRetired) {
              listitem.setAttribute("class", "retiredItem");
          }
          
          try {
            var link = node.getElementsByTagName("link");
            listitem.setAttribute("link", link[0].firstChild.data);
          }catch (linkerr){
            // Do nothing - there is no link to be had
          }

            try{
                var uuid = node.getElementsByTagName("uuid");
                listitem.setAttribute("uuid", uuid[0].firstChild.data);
            } catch (err) {}            
            // Pawel, for execution plan index results editing
            try {
                var objId = node.getElementsByTagName("objId");
                listitem.setAttribute("objId", objId[0].firstChild.data);
            } catch (err) {}
           
          
          
          if(this.context){
            listitem.setAttribute("context", this.context);
          }

          
                
          var labelCnt;

          if(headers.length > 0){
            for(var k=0; k<headers.length; k++){
              var header = headers[k];
              if(header.getAttribute("id")){
                labelCnt = header.getAttribute("id").replace(listName + "_label", "");
              }else
                labelCnt = k+1;
              //while(node.getElementsByTagName("label" + labelCnt)){
             // alert("label" + labelCnt);
              if(node.getElementsByTagName("label" + labelCnt)){
                var label = node.getElementsByTagName("label" + labelCnt);
                //alert(label);
                var listcell = document.createElement("listcell");
                if(label[0].firstChild !== null){
                    //Pawel - color for test results
                    var value = label[0].firstChild.data;                    
                    //alert(value);
                      listcell.setAttribute("label", label[0].firstChild.data);
                      listcell.setAttribute("type", 'description');
                    
                }else{
                  listcell.setAttribute("label", "");
                }
                
                listitem.appendChild(listcell);
                //labelCnt++;
              }
            }
          }
        }catch (er){
          alert(er);
        }
        this.listBoxObject.appendChild(listitem);      
      }
    }else{
      UserInterface.setMsgPanel("RETRIEVED DATA CONTAINS NO NODES");
    }
    this.listBoxObject.scrollToIndex(0);

    UserInterface.setMsgPanel(msg[0].firstChild.data + " (ENTRIES: " + nodes.length + ")");
  }
  
  this.getAllIDs = function(attrib, childNo, nullNo){
      var paramArray = new Array();

      var count = this.listBoxObject.getRowCount();
      
      for(i=0; i < count; i++){
          if(childNo >= 0) {
              var item = this.listBoxObject.getItemAtIndex(i).childNodes[childNo];
          } else {
              var item = this.listBoxObject.getItemAtIndex(i);
          }
          try { 
              if (item.getAttribute(attrib)) { paramArray.unshift(item.getAttribute(attrib)); }
              else{
                if(nullNo)
                  paramArray.unshift('');
              }
          } catch(e) {};
      }

      return paramArray.join(',');
  }
}




