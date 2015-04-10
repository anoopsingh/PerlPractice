<?php
  header( "Pragma: private");
  header( "Expires: Mon, 20 Dec 1998 01:00:00 GMT" );
  #header( "Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT" );
  header( "Cache-Control: s-maxage=0, no-store, no-cache, must-revalidate, post-check=0, pre-check=0" );
  header( "Pragma: no-cache" );
  include_once("session.php");
  if(!isset($_SESSION['USERINFO'])){
    header("Cache-control: private");
    session_destroy();
    header("Location: https://" . $_SERVER{'SERVER_NAME'} . "/login.php");
    #Above section works - do not touch
    #session_write_close();
  }
  function __autoload($classname){
    if(preg_match("/_/",$classname)){
      $classFile = str_replace("_","/",$classname) . ".php" ;
    } else {
      $classFile = $classname . ".class.php";
    }
    include($classFile);
  }

?>

<HTML>
<HEAD>
<TITLE></TITLE>
<link href="/inc/sitedb.css" rel="stylesheet" type="text/css">
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=ISO-8859-1">
<META HTTP-EQUIV="Refresh" content="86500">
<!-- set for 100 seconds greater than session.gc_maxlifetime in php.ini - which is 86400 -->
<link type="text/css" rel="stylesheet" href="/yui/current/build/container/assets/container.css"/>
<script type="text/javascript" src="/yui/current/build/yahoo/yahoo-min.js"></script>
<script type="text/javascript" src="/yui/current/build/event/event-min.js"></script>
<script type="text/javascript" src="/yui/current/build/connection/connection-min.js"></script>
<script type="text/javascript" src="/yui/current/build/dom/dom-min.js"></script>

<script type="text/javascript" src="/yui/current/build/animation/animation-min.js"></script>
<script type="text/javascript" src="/yui/current/build/dragdrop/dragdrop-min.js"></script>
<script type="text/javascript" src="/yui/current/build/container/container-min.js"></script>
<!-- <script type="text/javascript" src="/yui/current/build/widget.alert.js"></script> -->

<script type="text/javascript" src="/js/global.js"></script>
<!-- <link type="text/css" rel="stylesheet" href="/yui/current/build/reset/reset-min.css"></link>
<link type="text/css" rel="stylesheet" href="/yui/current/build/fonts/fonts-min.css"></link>
<link type="text/css" rel="stylesheet" href="/yui/current/build/grids/grids-min.css"></link> -->

<script type="text/javascript">
  <?php
   if(!isset($_SESSION['USERINFO'])){
    echo "window.location.href=\"/logout.php\"";
   }
  ?>
  var max_uploads = 10;
  var upload_number = 1;
  function addFileInput(e) {
    if(upload_number < max_uploads) {
 	var d = document.createElement("div");
 	d.setAttribute("style","padding-top:5px");
        var file = document.createElement("input");
 	file.setAttribute("type", "file");
 	file.setAttribute("name", e + '[' + ']');
 	d.appendChild(file);
 	document.getElementById("moreUploads").appendChild(d);
 	upload_number++;
    }
    else{
      alert('Maximum upload limit (10) reached!');
    }
  }
  function submitForm(f, callbackEvalSuccess, callbackEvalFailure, id){
    if(!parent.UserInterface)
      parent = window.opener;

    if(id)
      parent.UserInterface.setMsgPanel("RETRIEVING DATA ... PLEASE WAIT, THIS MAY TAKE TIME");
    else{
      id = null;
      parent.UserInterface.setLoading(true);
    }
    var formObject = f; 
    YAHOO.util.Connect.setForm(formObject,true); 
    var callback = { 
	    success: function(o) {
	      if(o.responseXML){
		evaluteResponse(o.responseXML.documentElement, callbackEvalSuccess, callbackEvalFailure, id);
                parent.UserInterface.setLoading(false);
	      }else if(o.responseText){
		setMsg("ERROR: Invalid (text) response recieved from XHR.  Please try again");
                if ( callbackEvalFailure ) { try { eval(callbackEvalFailure); } catch(e) { alert('Exception: ' + e.description) };  }
		parent.UserInterface.setLoading(false); return;
	      }else{
		setMsg("ERROR: Invalid (empty) response recieved from XHR.  Please try again");
                if ( callbackEvalFailure ) { try { eval(callbackEvalFailure); } catch(e) { alert('Exception: ' + e.description) };  }
		parent.UserInterface.setLoading(false); return;
	      }
	    }, 
	    upload: function(o){
              // eventType has a string value of "uploadEvent". 
              // args[0] is the response object.
              //alert(o.responseText);
		evaluteResponse(o.responseXML.documentElement, callbackEvalSuccess, callbackEvalFailure, id);
              //evaluteResponse(o.responseXML.documentElement);
              parent.UserInterface.setLoading(false); return;
              },
            failure: function(o) {
              parent.UserInterface.setLoading(false);
                if ( callbackEvalFailure ) { try { eval(callbackEvalFailure); } catch(e) { alert('Exception: ' + e.description) };  }
              setMsg("ERROR: XHR Failure [COMM].  Please try again.");
              return;
              },            
	    timeout: 500000
    }
    var cObj = YAHOO.util.Connect.asyncRequest('POST', '/content/so.php', callback);  
  }
  function setMsg(msg) {
    parent.UserInterface.setMsgPanel(msg);
  }
  function evaluteResponse(x, callbackEvalSuccess, callbackEvalFailure, id) {
    var error = x.getElementsByTagName("error");
    var success = x.getElementsByTagName("success");
    var setMsgFlag = true;
    if(error.length > 0){
      // Problem, error has occurred
      var msg = error[0].getElementsByTagName('message');
      
        if ( callbackEvalFailure ) {
            //alert(callbackEvalFailure);
            if(callbackEvalFailure == "displayErrorMsg"){
              document.getElementById("errorMsg").innerHTML = "<pre style='color:dark-red'>" + msg[0].firstChild.data + "</pre>";
              setMsgFlag = false;
            }else if(callbackEvalFailure == "alertErrorMsg"){
              alert(msg[0].firstChild.data);
              setMsgFlag = false;
            }else
              try { eval(callbackEvalFailure); } catch(e) { alert('Exception: ' + e.description) };
        }
    }
    if(success.length > 0) {
      var msg = success[0].getElementsByTagName('message');
      if(id){
//        alert(x.getElementsByTagName('whereStmt')[0].firstChild.data);
          parent.document.getElementById('objList_testcase').setAttribute('whereStmt', x.getElementsByTagName('tc_whereStmt')[0].firstChild.data);
          parent.document.getElementById('objList_resultsset').setAttribute('whereStmt', x.getElementsByTagName('result_whereStmt')[0].firstChild.data);
          parent.document.getElementById('objList_testcase').setAttribute('filter', '');
          parent.document.getElementById('objList_resultsset').setAttribute('filter', '');
          if(parent.document.getElementById('testResultList').collapsed == true)
            parent.document.getElementById('testResultList').setAttribute('collapsed',false);
                
          parent.document.getElementById('objList_testcase_filter_group').selectedIndex=6;   //=4;          
          parent.document.getElementById('showRetired').checked=true;
          parent.sortBy("objList_resultsset", null);
          parent.sortBy("objList_testcase", null);                
      }
      else{
        parent.UserInterface.refreshPage(); /* removed this, as it call the refresh on the hidden testcaseadmin interface*/
        document.location.reload();        
        try{
            if(parent.document.getElementById('testcase_refresh'))
              parent.document.getElementById('testcase_refresh').click();
          }catch(e){ /* Do nothing - this is a best attempt*/ }
      }
        if ( callbackEvalSuccess ) {
            //alert(callbackEvalSuccess);
            try { eval(callbackEvalSuccess); } catch(e) {};
        }
    }
    if(setMsgFlag)
      setMsg(msg[0].firstChild.data);
      
    return;
  }

  function loadFeatureBox(sb,sb2,sb3,params){
    if(sb !== null){
      sbIndex = sb.selectedIndex;
      sbValue = sb.options[sb.selectedIndex].value;
      sbText = sb.options[sb.selectedIndex].text;
      if(sb2){ sb2.options.length=0;}
      if(sb3){ sb3.options.length=0;}
      if(sbValue === ""){
	setMsg("Please select a valid drop down item.  Default selection is not a valid selection");
	return;
      }
    }
    setMsg("RETRIEVING DATA.  PLEASE WAIT, THIS MAY TAKE TIME");
    parent.UserInterface.setLoading(true);
    var paramArray = new Array();
    var paramString = "";
    for(var i in params) {
      paramString = i + "=" + escape(params[i]);
      paramArray.push(paramString);
    }
    var callback = { 
      success: function(o) {
	if(o.responseXML){
	    var response = o.responseXML.documentElement;
	    var error = response.getElementsByTagName("error");
	    var success = response.getElementsByTagName("success");
	    var nodes = response.getElementsByTagName("node");
	    if(error.length > 0){
	      if(sb2){ sb2.options.length=0; }
	      var msg = error[0].getElementsByTagName('message');
	    }
	    if(success.length > 0) {
	      var msg = success[0].getElementsByTagName('message');
	      if(sb2){ sb2.options.length=0;}
	      if(nodes.length >0 ){
		if(sb2){
		  for(i=0;i<nodes.length;i++){
		    var node = nodes[i];
		    var data =  node.getElementsByTagName("data");
		    var v = "";
		    var t = "Error: Feature wihout a title";
		    if(data[0].attributes.getNamedItem('uuid').value !== null){
		      v = data[0].attributes.getNamedItem('uuid').value;
		    }
		    if(data[0].attributes.getNamedItem('title').value !== null){
		      t = data[0].attributes.getNamedItem('title').value;
		    }
		    var option = new Option(t,v);
		    sb2.options[sb2.options.length] = option;
		  }
		}
	      }else{
	        setMsg("RETRIEVED DATA CONTAINS NO VALID INFORMATION");
                parent.UserInterface.setLoading(false);
		return;
	      }
	      
	    }
	    setMsg(msg[0].firstChild.data + " (ENTRIES: " + nodes.length + ")");
            parent.UserInterface.setLoading(false);
	}else if(o.responseText){
	  setMsg("Invalid (text) response recieved from XHR.  Please try again");
          parent.UserInterface.setLoading(false);
	  return;
	}else{
	  setMsg("Invalid (none) response recieved from XHR.  Please try again");
          parent.UserInterface.setLoading(false);
          return;
	}
      }, 
      failure: function(o) { setMsg("XHR (COMM) Failure.   Please try again");}, 
      timeout: 10000
    }
    YAHOO.util.Connect.asyncRequest('POST', '/content/so.php', callback, paramArray.join('&'));
  }
</script>
<?php
  $POSTGET = (!empty($_POST))?$_POST:$_GET;
  if(isset($POSTGET['method']) && ($POSTGET['method'] == "loadGatingInterface2")){
    echo "<style type='text/css'>";
    echo "
#qfams_optional_reviewers {
  font-size: 12px;
  border: 1px solid #698ed1;
  background: #f6f8fb;
  overflow-y: auto;
  height: 8em;
#  width: 12em;
#  border-left:   1px solid #fff;
#  border-top:    1px solid #fff;
#  border-bottom: 1px solid #fff;
#  border-right:  1px solid #fff;
}
#qfams_optional_reviewers label {
  padding-right: 3px;
  display: block;
}
";    
    echo "</style>";
  }
?>
</HEAD>
<BODY>
<div id="content">
<?php
  # To Do : Add Exception class - that extends exception, and display error;
  #exit;
  $POSTGET = (!empty($_POST))?$_POST:$_GET;
  if(isset($POSTGET['obj'])) {
    try {      
      $obj = TaskFactory::getInstance()->generateObj($POSTGET['obj']);
      $obj->setParams($POSTGET);
      if(isset($POSTGET['method'])){
        #$action = strtolower($_GET['method']);
	$action = $POSTGET['method'];
	#echo "<br/>ACTION: $action<br/>";
        if(method_exists($obj, $action)){
          //echo "method exists";
	  $methods = get_class_methods($obj);
	  foreach($methods as $method){
	    #echo "METHOD: $method<br/>";
	  }
          try {
            echo $obj->$action();
          }catch(Exception $e){
            # Call to error object to display html error
            # Error should detail that the supplied method was attempted. ********************
            echo "Not able to retrieve object method 2: " . $POSTGET['method'];
            echo "<br/>ERROR: " . $e->getMessage() . "<br/>";
          }
        }else{
          # This will be an error object creation call
          # Error should detail that the supplied method does not exist. ********************
          echo "Invalid object method: " . $POSTGET['method'];
        }
      }elseif(method_exists($obj, 'add_obj')){
        try {
          echo $obj->add_obj();
        }catch(Exception $e){
          # Call to error object to display html error
          # Error should detail that the default add_obj was attempted. ********************
          echo "Not able to retrieve object method: " . $POSTGET['method'];
          echo "<br/>ERROR: " . $e->getMessage() . "<br/>";
        }
      }
    }catch(Exception $e){
      # Error should detail the exception (object creation failure?). ********************
    }
  }else{
    #echo "SERVICE OBJECT";
    echo "Object instance can not be created without object request";
  }
?>
</div>
<div id="errorMsg"></div>
</BODY>
</HTML>


