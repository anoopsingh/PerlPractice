
function sprintf(){
  if (!arguments || arguments.length < 1 || !RegExp)
  {
          return;
  }
  var str = arguments[0];
  var re = /([^%]*)%('.|0|\x20)?(-)?(\d+)?(\.\d+)?(%|b|c|d|u|f|o|s|x|X)(.*)/;
  var a = b = [], numSubstitutions = 0, numMatches = 0;
  while (a = re.exec(str))
  {
          var leftpart = a[1], pPad = a[2], pJustify = a[3], pMinLength = a[4];
          var pPrecision = a[5], pType = a[6], rightPart = a[7];
          
          //alert(a + '\n' + [a[0], leftpart, pPad, pJustify, pMinLength, pPrecision);

          numMatches++;
          if (pType == '%')
          {
                  subst = '%';
          }
          else
          {
                  numSubstitutions++;
                  if (numSubstitutions >= arguments.length)
                  {
                          alert('Error! Not enough function arguments (' + (arguments.length - 1) + ', excluding the string)\nfor the number of substitution parameters in string (' + numSubstitutions + ' so far).');
                  }
                  var param = arguments[numSubstitutions];
                  var pad = '';
                         if (pPad && pPad.substr(0,1) == "'") pad = leftpart.substr(1,1);
                    else if (pPad) pad = pPad;
                  var justifyRight = true;
                         if (pJustify && pJustify === "-") justifyRight = false;
                  var minLength = -1;
                         if (pMinLength) minLength = parseInt(pMinLength);
                  var precision = -1;
                         if (pPrecision && pType == 'f') precision = parseInt(pPrecision.substring(1));
                  var subst = param;
                         if (pType == 'b') subst = parseInt(param).toString(2);
                    else if (pType == 'c') subst = String.fromCharCode(parseInt(param));
                    else if (pType == 'd') subst = parseInt(param) ? parseInt(param) : 0;
                    else if (pType == 'u') subst = Math.abs(param);
                    else if (pType == 'f') subst = (precision > -1) ? Math.round(parseFloat(param) * Math.pow(10, precision)) / Math.pow(10, precision): parseFloat(param);
                    else if (pType == 'o') subst = parseInt(param).toString(8);
                    else if (pType == 's') subst = param;
                    else if (pType == 'x') subst = ('' + parseInt(param).toString(16)).toLowerCase();
                    else if (pType == 'X') subst = ('' + parseInt(param).toString(16)).toUpperCase();
          }
          str = leftpart + subst + rightPart;
  }
  return str;
}

function addButton()
{
  var aBox = document.getElementById("main-box-content");

  var button = document.createElement("button");
  button.setAttribute("label","A Button");
  aBox.appendChild(button);
}
function loadContentPane(pane, url){
  var infoPane = window.frames[pane];
  infoPane.location.href = url;
}
function loadContentPane2(pane, url){
  var infoPane = document.getElementById(pane);
  infoPane.setAttribute("src",url);
}
function testing(){
 list = document.getElementById('userlist') 
 var item = list.selectedItems[0];  // This gets the first selected row (single select is only one row.
 var node = item.childNodes[0];
 alert(node.getAttribute('label'));  // This returns the NAME of the USER
 alert(item.getAttribute('id'));   // This returns the UUID of the USER
    // alert message appears
 alert(item);
}
function setListNodeAttribute(list, attribute, node, value) {
  list = document.getElementById(list);
  var item = list.selectedItems[0];  // This gets the first selected row (single select is only one row.
  var childnode = item.childNodes[node];
  childnode.setAttribute(attribute, value);;
}



function loadTestCaseList(list, url) {
  alert(list);
  alert(url);
}




function clearListBox(listObject){
  var listbox = document.getElementById(listObject);
  var count = listbox.getRowCount();
  while (count--){
    listbox.removeItemAt(count);
  }
  
}



var xmlhttp
var listObject

function loadListBox(list,url,callback) {
  listObject = list;
  xmlhttp=null;
  // code for Mozilla, etc.
  if (window.XMLHttpRequest)
    {
    xmlhttp=new XMLHttpRequest()
    }
  // code for IE
  else if (window.ActiveXObject)
    {
    xmlhttp=new ActiveXObject("Microsoft.XMLHTTP")
    }
  if (xmlhttp!=null)
    {
    eval("xmlhttp.onreadystatechange=" + callback);
    xmlhttp.open("GET",url,true);
    xmlhttp.send(null);
    }
  else
    {
    alert("Your browser does not support XMLHTTP.")
    }
  
}

function loadXMLDoc(url) 
{
xmlhttp=null
// code for Mozilla, etc.
if (window.XMLHttpRequest)
  {
  xmlhttp=new XMLHttpRequest()
  }
// code for IE
else if (window.ActiveXObject)
  {
  xmlhttp=new ActiveXObject("Microsoft.XMLHTTP")
  }
if (xmlhttp!=null)
  {
  xmlhttp.onreadystatechange=onResponse;
  xmlhttp.open("GET",url,true);
  xmlhttp.send(null);
  }
else
  {
  alert("Your browser does not support XMLHTTP.")
  }
}

function checkReadyState(obj)
{
  if(obj.readyState == 4)
  {
    if(obj.status == 200)
    {
      return true;
    }
    else
    {
      alert("Problem retrieving XML data");
    }
  }
}

function onResponse() 
{
  if(checkReadyState(xmlhttp))
  {
  var response = xmlhttp.responseXML.documentElement;
  txt="<table border='1'>"
  x=response.getElementsByTagName("CD")
  for (i=0;i<x.length;i++)
    {
    txt=txt + "<tr>"
    xx=x[i].getElementsByTagName("TITLE")
      {
      try
        {
        txt=txt + "<td>" + xx[0].firstChild.data + "</td>"
        }
      catch (er)
        {
        txt=txt + "<td> </td>"
        }
      }
    xx=x[i].getElementsByTagName("ARTIST")
      {
      try
        {
        txt=txt + "<td>" + xx[0].firstChild.data + "</td>"
        }
      catch (er)
        {
        txt=txt + "<td> </td>"
        }
      }
    txt=txt + "</tr>"
    }
  txt=txt + "</table>"
  document.getElementById('copy').innerHTML=txt
  }
}


function listboxResponse() {
  if(checkReadyState(xmlhttp)) {
    
    var response = xmlhttp.responseXML.documentElement;
    var listbox = document.getElementById(listObject);
    clearListBox(listObject);
    x=response.getElementsByTagName("testcase");
    for (i=0;i<x.length;i++) {
      var listitem = document.createElement("listitem"); 
      xx=x[i].getElementsByTagName("testcase_uuid")
      {
      try
        {
        listitem.setAttribute("id", xx[0].firstChild.data);
        listitem.setAttribute("context", "testcasepopup");
        }
      catch (er){
        //alert(er);
        }
      }
      xx=x[i].getElementsByTagName("testcase_alias")
      {
      try
        {
        var listcell = document.createElement("listcell");
        listcell.setAttribute("label", xx[0].firstChild.data);
        listitem.appendChild(listcell);
        }
      catch (er){
        //alert(er);
        }
      }
      xx=x[i].getElementsByTagName("testcase_status")
      {
      try
        {
        var listcell = document.createElement("listcell");
        listcell.setAttribute("label", xx[0].firstChild.data);
        listitem.appendChild(listcell);
        }
      catch (er){
        //alert(er);
        }
      }
      listbox.appendChild(listitem);
    }
  }
}


function logOut() {
  window.location.href="/logout.php";
}

function loadTabFrameContent(tabbox, tab, iframe, url){
  //alert(url);
  var tabBox = document.getElementById(tabbox);
  var infoPane = document.getElementById(iframe);
  infoPane.setAttribute("src",url);
  tabBox.selectedTab = document.getElementById(tab);
}
var testadminitflag = false;
function testadmininit(){
  if(!testadminitflag){
    var createBtn = document.getElementById('testadmin_create');
    createBtn.click();
    testadminitflag = true;
  }
}

function myreq (url) {
  var request = YAHOO.util.Connect.asyncRequest('POST',url,callback,null);
}
var callback = {
  success: function(o) {
    alert(o.responseText);
    var tabBox = document.getElementById('contentTabs');
    var infoPane = document.getElementById('workplace_frame');
    //infoPane.setAttribute("src",url);
    infoPane.innerHtml = o.responseText;
    tabBox.selectedTab = document.getElementById('workplace');
    
    },
  failure: function(o) { alert("failure"); }
}

function makeTASmaller(txtarea, callFlowFlag) {
  if(callFlowFlag == ''){
    var ta = document.getElementById(txtarea);
    if (ta.rows>3){
      ta.rows--;
    }
  }else{
    var tas = document.getElementsByTagName('textarea');
    for(var i=0; i < tas.length; i++){
      if(tas[i].id.search(txtarea) > -1){
        if(tas[i].rows > 3)
          tas[i].rows--;
      }
    }
  }
}
function makeTABigger(txtarea, callFlowFlag) {
  if(callFlowFlag == ''){  
    var ta = document.getElementById(txtarea);
    ta.rows++;
  }else{
    var tas = document.getElementsByTagName('textarea');
    for(var i=0; i < tas.length; i++){
      if(tas[i].id.search(txtarea) > -1)
        tas[i].rows++;
    }
  }
}


function splitTA(objID, numberColumns, taID){
        var rows;
        if(document.getElementById(taID + '1'))
          rows = document.getElementById(taID + '1').rows;
        else
          rows = 5;
          
	var cols = 100;
	var html="";
	if(!numberColumns || (numberColumns == 0)){
          numberColumns = 1;
          document.getElementById('messageFlowColumns').value = 1;  
	}
	cols = Math.floor(cols/numberColumns);

        for(var i=1; i <= numberColumns; i++){
          html += "<textarea class=\"textarea\" id=\"" + taID + i + "\" rows=\"" + rows + "\" cols=\"" + cols + "\" wrap=\"SOFT\" name=\"" + taID + i + "\" >";
          if(document.getElementById(taID + i))
            html += document.getElementById(taID + i).value;
          html += "</textarea>";
        }
	document.getElementById(objID).innerHTML = html;        
}
function trim(stringToTrim, unquote) {
        if(unquote){
          return stringToTrim.replace(/^['"]+|['"]+$/g,"");
        }
	return stringToTrim.replace(/^\s+|\s+$/g,"");
}
function ltrim(stringToTrim) {
	return stringToTrim.replace(/^\s+/,"");
}
function rtrim(stringToTrim) {
	return stringToTrim.replace(/\s+$/,"");
}
function isDate (year, month, day) {
// month argument must be in the range 1 - 12
month = month - 1; // javascript month range : 0- 11
var tempDate = new Date(year,month,day);

if ( (tempDate.getFullYear() == year) &&
(month == tempDate.getMonth()) &&
(day == tempDate.getDate()) )
return true;
else
return false
}
