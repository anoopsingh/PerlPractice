
var countdownTimer=new Object();var commentshowreply=new Object();function getElementsByClassName(classname){if(document.getElementsByTagName){var els=document.getElementsByTagName("*");var c=new RegExp('/b^|'+classname+'|$/b');elem=new Array();var n=0;for(var i=0;i<els.length;i++){if(els[i].className){if(c.test(els[i].className)){elem[n]=els[i];n++;}}}
return elem;}else{return false;}}
function getCookieVal(offset){var endstr=document.cookie.indexOf(";",offset);if(endstr==-1)
endstr=document.cookie.length;return unescape(document.cookie.substring(offset,endstr));}
function GetCookie(name){var arg=name+"=";var alen=arg.length;var clen=document.cookie.length;var i=0;while(i<clen){var j=i+alen;if(document.cookie.substring(i,j)==arg)
return getCookieVal(j);i=document.cookie.indexOf(" ",i)+1;if(i==0)break;}
return null;}
function SetCookie(name,value){ var argv=SetCookie.arguments;var argc=SetCookie.arguments.length;var expires=(argc>2)?argv[2]:null;if(expires){var date=new Date();date.setTime(date.getTime()+(expires*24*60*60*1000));var expires="; expires="+date.toGMTString();}
var path=(argc>3)?argv[3]:null;var domain=(argc>4)?argv[4]:null;var secure=(argc>5)?argv[5]:false;document.cookie=name+"="+escape(value)+
((expires==null)?"":("; expires="+expires))+
((path==null)?"":("; path="+path))+
((domain==null)?"":("; domain="+domain))+
((secure==true)?"; secure":"");}
function startCountdown(element,secondsremaining,showreply){$(element).innerHTML=secondsremaining;commentshowreply[element]=showreply;if(!countdownTimer[element]){countdownTimer[element]=setInterval('updateCountdown(\''+element+'\')',1000);}}
function updateCountdown(element){if(element==null){clearInterval(countdownTimer[element]);countdownTimer[element]=null;}else{var target=$(element);if(target){current=parseInt(target.innerHTML);current--;if(current<=0){target.innerHTML=0;clearInterval(countdownTimer[element]);countdownTimer[element]=null;var id=element.replace(/countdown/,'');var newtarget=$('cbody'+id);var content=newtarget.innerHTML.replace(/^[\s\S]*Seconds\)<\/a>/i,'');content=content.replace(/\<a [^<]*\[reply\].*$/i,'');var newhtml='<div class="c-body-inside" id="cbody-inside-'+id+'">'+content;if(commentshowreply[element]){var replylink=newtarget.innerHTML.replace(/^[\s\S]*setupcreply\(/i,'setupcreply(');replylink=replylink.replace(/\)\)[\s\S]*$/mi,')');newhtml+='<a href="?creplyto='+id+'#creplyform" onclick="return('+replylink+')" class="c-reply">[reply]</a>';}
newhtml+='</div>';newtarget.innerHTML=newhtml;$('c'+id).className='c-normal';}else{target.innerHTML=current;}}}}
function initPreview(){setInterval('updateAll()',1000);}
function updateAll(){$('titlepreview').innerHTML=$('title').value;$('bodytextpreview').innerHTML=$('bodytext').value;charCounter($('title'),75,$('titleCounter'));charCounter($('bodytext'),350,$('bodytextCounter'));}
function charLimit(field,maxLength){var inputLength=field.value.length;if(inputLength>=maxLength){field.value=field.value.substring(0,maxLength);}}
function charCounter(field,maxLength,countTarget){var inputLength=field.value.length;if(inputLength>=maxLength){field.value=field.value.substring(0,maxLength);}
countTarget.innerHTML=maxLength-field.value.length;}
function charCounterPreview(field,maxLength,countTarget,previewTarget,emptyFlag){charCounter(field,maxLength,countTarget);if(emptyFlag&&field.value.length==0){previewTarget.innerHTML='&nbsp;';}else{previewTarget.innerHTML=field.value;}}
function updateTopicPreview(text,cleantext){var target=$('topicpreview');target.innerHTML=text;target.href='/'+cleantext;}
function unpopp(){poppDiv=$('poppDiv');if(poppDiv){poppDiv.parentNode.removeChild(poppDiv);poppDiv=false;}}
function popp(id,text){unpopp();$('enclosure'+id).innerHTML+='<div id="poppDiv" class="inline-warning"><div><p><strong>Make your vote count!</strong> To '+text+' stories, <a href="/login">login</a> or <a href="/register">join Digg</a> for free.</p><a href="javascript:unpopp()"><img src="/img/close.gif" class="close" width="22" height="22" alt="Close" /></a></div></div>';return(false);}
function poppd(id){return(popp(id,'digg'));}
function poppe(id){return(popp(id,'email'));}
function poppr(id){return(popp(id,'bury'));}
function poppb(id){return(popp(id,'blog'));}
function toggleDisclosureWidget(id){var div=document.getElementById(id);if(div.className=='slide-show'){div.className='slide-hide';}else{div.className='slide-show';}
return(false);}
function tdw(id){return(toggleDisclosureWidget(id));}
function toggleLogin(){unpopp();toggleDisclosureWidget('login-form');return(false);}
function topsearch(){var all;if(all=$('search-all')){if(all.checked==true){$('search').action='/search';}
all.disabled=true;$('search-specific').disabled=true;}
$('top-submit').disabled=true;return true;}
