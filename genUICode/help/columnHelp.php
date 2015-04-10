<?php
  header( "Pragma: private");
  header( "Expires: Mon, 20 Dec 1998 01:00:00 GMT" );
  #header( "Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT" );
  header( "Cache-Control: s-maxage=0, no-store, no-cache, must-revalidate, post-check=0, pre-check=0" );
  header( "Pragma: no-cache" );
  function __autoload($classname){
    $classFile = $classname . ".class.php";
    include($classFile);
  }
  
?>
<HTML>
<HEAD>
<TITLE></TITLE>
</BODY>
</HTML>


