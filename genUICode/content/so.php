<?php
  header( "Pragma: private");
  header( "Expires: Mon, 20 Dec 1998 01:00:00 GMT" );
  #header( "Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT" );
  header( "Cache-Control: s-maxage=0, no-store, no-cache, must-revalidate, post-check=0, pre-check=0" );
  header( "Pragma: no-cache" );
 
  include_once("session.php");
  include ("Date.php");
  if(isset($_SESSION['USERINFO'])){
    $tmp = $_SESSION['USERINFO'];
    $userInfo = unserialize(base64_decode($tmp));
  }else{
    header("Cache-control: private");
    session_destroy();
    header("Location: /");
    echo "<script type=\"text/javascript\">window.location.href=\"/logout.php\";</script>";
  }
  
  function __autoload($classname){
    if(preg_match("/(Sons_|Tms_|ATS_)/",$classname)){
      $classFile = $classname . ".class.php";
    }
    elseif(preg_match("/_/",$classname)){
      $classFile = str_replace("_","/",$classname) . ".php" ;
    } else {
      $classFile = $classname . ".class.php";
    }
    include($classFile);
  }
  
  #print_r($_POST);
  #print_r($_GET);
  #exit;
if(isset($_GET['obj']) && isset($_GET['method'])){
    try {
        $obj = TaskFactory::getInstance()->generateObj($_GET['obj']);
        $obj->setParams($_GET);
        $obj->setOutput('xml');
        $action = $_GET['method'];
        if(method_exists($obj, $action)){
            try {
               echo $obj->$action();
            }catch(Exception $e){
                echo "Not able to retrieve object method: " . $e->getMessage();
            }
        }else{
            echo "Invalid object method: :" . $_GET['method'];
        }
    }catch(Exception $e) {
        echo $e->getMessage();
    }
}elseif(isset($_POST['obj']) && isset($_POST['method'])) {
    try {
        $obj = TaskFactory::getInstance()->generateObj($_POST['obj']);
        $obj->setParams($_POST);
        $obj->setOutput('xml');
        $action = $_POST['method'];
        if(method_exists($obj, $action)){
            try {
               echo $obj->$action();
            }catch(Exception $e){
              echo "Not able to retrieve object method: " . $e->getMessage();
            }
        }else{
            echo "Invalid object method: :" . $_POST['method'];
        }
    }catch(Exception $e) {
        echo $e->getMessage();
    }
}else{
   #Error Problem. This is the be replaced this an error call.
   echo "ERROR";
   print_r($_POST);
   print_r($_GET);
}
?>
